<?php

namespace App\Services\Saas;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SubscriptionService
{
    /**
     * @return array<int, string>
     */
    public function activeStatuses(): array
    {
        return ['active', 'trialing'];
    }

    public function hasActiveSubscription(User $user): bool
    {
        return $user->hasActiveSubscription();
    }

    /**
     * @return array{mode:string,url:?string,message:string}
     */
    public function startCheckout(User $user, Plan $plan): array
    {
        if (!$plan->is_active) {
            throw new RuntimeException('This plan is not currently available.');
        }

        if ((int) $plan->price_cents === 0) {
            $this->activateLocalSubscription($user, $plan);

            return [
                'mode' => 'local',
                'url' => null,
                'message' => 'Subscription activated successfully.',
            ];
        }

        if (blank($plan->stripe_price_id)) {
            throw new RuntimeException('This paid plan is missing a Stripe price ID.');
        }

        $customerId = $this->resolveStripeCustomer($user);
        $response = $this->stripeRequest('post', 'checkout/sessions', [
            'mode' => 'subscription',
            'customer' => $customerId,
            'success_url' => route('saas.settings.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('saas.settings.subscription.cancelled'),
            'client_reference_id' => (string) $user->id,
            'metadata[user_id]' => (string) $user->id,
            'metadata[plan_id]' => (string) $plan->id,
            'line_items[0][price]' => $plan->stripe_price_id,
            'line_items[0][quantity]' => '1',
        ]);

        $sessionUrl = (string) $response->json('url');
        if ($sessionUrl === '') {
            throw new RuntimeException('Stripe checkout session URL was not returned.');
        }

        return [
            'mode' => 'stripe',
            'url' => $sessionUrl,
            'message' => 'Redirecting to Stripe checkout.',
        ];
    }

    /**
     * @return array{mode:string,subscription_id:?string,client_secret:?string,message:string}
     */
    public function startEmbeddedSubscription(User $user, Plan $plan): array
    {
        if (!$plan->is_active) {
            throw new RuntimeException('This plan is not currently available.');
        }

        if ((int) $plan->price_cents === 0) {
            $this->activateLocalSubscription($user, $plan);

            return [
                'mode' => 'local',
                'subscription_id' => null,
                'client_secret' => null,
                'message' => 'Subscription activated successfully.',
            ];
        }

        if (blank($plan->stripe_price_id)) {
            throw new RuntimeException('This paid plan is missing a Stripe price ID.');
        }

        $customerId = $this->resolveStripeCustomer($user);
        $response = $this->stripeRequest('post', 'subscriptions', [
            'customer' => $customerId,
            'items[0][price]' => $plan->stripe_price_id,
            'payment_behavior' => 'default_incomplete',
            'payment_settings[save_default_payment_method]' => 'on_subscription',
            'metadata[user_id]' => (string) $user->id,
            'metadata[plan_id]' => (string) $plan->id,
            'expand[0]' => 'latest_invoice.payment_intent',
        ]);

        $subscriptionId = (string) $response->json('id');
       
        $clientSecret = data_get($response->json(), 'latest_invoice.payment_intent.client_secret');

        if ($subscriptionId === '' || $clientSecret === '') {
            throw new RuntimeException('Stripe subscription confirmation secret was not returned.');
        }

        return [
            'mode' => 'stripe',
            'subscription_id' => $subscriptionId,
            'client_secret' => $clientSecret,
            'message' => 'Payment form ready.',
        ];
    }

    public function cancelSubscription(User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if (filled($user->stripe_subscription_id) && filled(config('services.stripe.secret'))) {
            $this->stripeRequest('delete', 'subscriptions/' . $user->stripe_subscription_id, []);
        }

        $user->forceFill([
            'subscription_status' => 'cancelled',
            'subscription_ends_at' => now(),
            'current_period_ends_at' => now(),
        ])->save();
    }

    public function syncCheckoutSession(string $sessionId): ?User
    {
        $response = $this->stripeRequest('get', 'checkout/sessions/' . $sessionId, [
            'expand[]' => 'subscription',
            'expand[]' => 'customer',
        ]);

        $payload = $response->json();
        $userId = (int) data_get($payload, 'metadata.user_id', data_get($payload, 'client_reference_id', 0));
        $user = User::query()->find($userId);

        if ($user === null) {
            return null;
        }

        $this->syncUserFromStripePayload($user, $payload);

        return $user->fresh();
    }

    public function syncSubscription(string $subscriptionId): ?User
    {
        $response = $this->stripeRequest('get', 'subscriptions/' . $subscriptionId, [
            'expand[]' => 'customer',
        ]);

        $payload = $response->json();
        $customerId = (string) data_get($payload, 'customer.id', data_get($payload, 'customer', ''));

        $user = User::query()
            ->where('stripe_subscription_id', $subscriptionId)
            ->orWhere('stripe_customer_id', $customerId)
            ->first();

        if ($user === null) {
            $userId = (int) data_get($payload, 'metadata.user_id', 0);
            $user = $userId > 0 ? User::query()->find($userId) : null;
        }

        if ($user === null) {
            return null;
        }

        $this->syncUserFromStripePayload($user, $payload);

        return $user->fresh();
    }

    /**
     * @param array<string, mixed> $event
     */
    public function handleWebhookEvent(array $event): void
    {
        $type = (string) ($event['type'] ?? '');
        $object = (array) data_get($event, 'data.object', []);

        if (in_array($type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            $userId = (int) data_get($object, 'metadata.user_id', data_get($object, 'client_reference_id', 0));
            $user = User::query()->find($userId);
            if ($user !== null) {
                $this->syncUserFromStripePayload($user, $object);
            }

            return;
        }

        if (in_array($type, ['invoice.paid', 'invoice.payment_failed'], true)) {
            $subscriptionId = (string) data_get($object, 'subscription', '');
            if ($subscriptionId !== '') {
                $this->syncSubscription($subscriptionId);
            }

            return;
        }

        if (in_array($type, ['customer.subscription.updated', 'customer.subscription.deleted'], true)) {
            $user = User::query()
                ->where('stripe_subscription_id', (string) ($object['id'] ?? ''))
                ->orWhere('stripe_customer_id', (string) ($object['customer'] ?? ''))
                ->first();

            if ($user !== null) {
                $this->syncUserFromStripePayload($user, [
                    'subscription' => $object,
                    'customer' => $object['customer'] ?? null,
                    'metadata' => $object['metadata'] ?? [],
                ]);
            }
        }
    }

    public function verifyWebhookSignature(string $payload, ?string $signatureHeader): bool
    {
        $secret = trim((string) config('services.stripe.webhook_secret'));
        if ($secret === '') {
            return true;
        }

        if (blank($signatureHeader)) {
            return false;
        }

        $parts = collect(explode(',', (string) $signatureHeader))
            ->mapWithKeys(function (string $chunk): array {
                [$key, $value] = array_pad(explode('=', $chunk, 2), 2, null);
                return [trim((string) $key) => trim((string) $value)];
            });

        $timestamp = $parts->get('t');
        $signature = $parts->get('v1');

        if (blank($timestamp) || blank($signature)) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, (string) $signature);
    }

    public function activateLocalSubscription(User $user, Plan $plan): void
    {
        $now = now();

        $user->forceFill([
            'plan_id' => $plan->id,
            'stripe_price_id' => $plan->stripe_price_id,
            'subscription_status' => 'active',
            'subscribed_at' => $user->subscribed_at ?? $now,
            'current_period_starts_at' => $now->copy()->startOfMonth(),
            'current_period_ends_at' => $now->copy()->endOfMonth(),
            'subscription_ends_at' => null,
        ])->save();
    }

    private function resolveStripeCustomer(User $user): string
    {
        if (filled($user->stripe_customer_id)) {
            return (string) $user->stripe_customer_id;
        }

        $response = $this->stripeRequest('post', 'customers', [
            'email' => $user->email,
            'name' => $user->name,
            'metadata[user_id]' => (string) $user->id,
        ]);

        $customerId = (string) $response->json('id');
        if ($customerId === '') {
            throw new RuntimeException('Unable to create Stripe customer.');
        }

        $user->forceFill(['stripe_customer_id' => $customerId])->save();

        return $customerId;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function syncUserFromStripePayload(User $user, array $payload): void
    {
        $subscription = data_get($payload, 'subscription');
        if (is_string($subscription) && $subscription !== '') {
            $subscription = $this->stripeRequest('get', 'subscriptions/' . $subscription, [])->json();
        }

        $customerId = (string) data_get($payload, 'customer.id', data_get($payload, 'customer', data_get($subscription, 'customer', '')));
        $subscriptionId = (string) data_get($subscription, 'id', '');
        $priceId = (string) data_get($subscription, 'items.data.0.price.id', data_get($payload, 'metadata.price_id', ''));
        $planId = (int) data_get($payload, 'metadata.plan_id', 0);

        if ($planId === 0 && $priceId !== '') {
            $planId = (int) Plan::query()->where('stripe_price_id', $priceId)->value('id');
        }

        $status = (string) data_get($subscription, 'status', data_get($payload, 'payment_status', 'inactive'));
        if ($status === 'paid') {
            $status = 'active';
        }

        $periodStart = data_get($subscription, 'current_period_start');
        $periodEnd = data_get($subscription, 'current_period_end');
        $cancelAt = data_get($subscription, 'cancel_at');
        $canceledAt = data_get($subscription, 'canceled_at');

        $user->forceFill([
            'plan_id' => $planId ?: $user->plan_id,
            'stripe_customer_id' => $customerId !== '' ? $customerId : $user->stripe_customer_id,
            'stripe_subscription_id' => $subscriptionId !== '' ? $subscriptionId : $user->stripe_subscription_id,
            'stripe_price_id' => $priceId !== '' ? $priceId : $user->stripe_price_id,
            'subscription_status' => $status !== '' ? $status : $user->subscription_status,
            'subscribed_at' => $user->subscribed_at ?? now(),
            'current_period_starts_at' => $periodStart ? Carbon::createFromTimestamp((int) $periodStart) : $user->current_period_starts_at,
            'current_period_ends_at' => $periodEnd ? Carbon::createFromTimestamp((int) $periodEnd) : $user->current_period_ends_at,
            'subscription_ends_at' => $cancelAt
                ? Carbon::createFromTimestamp((int) $cancelAt)
                : ($canceledAt ? Carbon::createFromTimestamp((int) $canceledAt) : null),
        ])->save();
    }

    /**
     * @param array<string, string> $payload
     */
    private function stripeRequest(string $method, string $uri, array $payload): Response
    {
        $secret = trim((string) config('services.stripe.secret'));
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $request = Http::baseUrl('https://api.stripe.com/v1')
            ->withBasicAuth($secret, '')
            ->acceptJson();

        if (in_array(strtolower($method), ['post', 'delete'], true)) {
            $request = $request->asForm();
        }

        $response = match (strtolower($method)) {
            'get' => $request->get($uri, $payload),
            'delete' => $request->delete($uri, $payload),
            default => $request->post($uri, $payload),
        };

        if (!$response->successful()) {
            $message = (string) Arr::first(array_filter([
                data_get($response->json(), 'error.message'),
                $response->body(),
            ]));

            throw new RuntimeException($message !== '' ? $message : 'Stripe request failed.');
        }

        return $response;
    }
}
