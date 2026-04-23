<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Saas\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BillingController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $user->loadMissing('plan');

        $plans = Plan::query()
            ->where('is_active', true)
            ->orderBy('price_cents')
            ->get()
            ->map(function (Plan $plan) use ($user): array {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'monthly_article_limit' => $plan->monthly_article_limit,
                    'monthly_token_limit' => $plan->monthly_token_limit,
                    'price_cents' => $plan->price_cents,
                    'currency' => $plan->currency,
                    'billing_interval' => $plan->billing_interval,
                    'stripe_price_id' => $plan->stripe_price_id,
                    'features' => $plan->features ?? [],
                    'is_current' => $user->plan_id === $plan->id,
                    'supports_upi' => strtoupper((string) $plan->currency) === 'INR',
                ];
            });

        return response()->json([
            'data' => [
                'stripe_publishable_key' => (string) config('services.stripe.key'),
                'subscription_status' => $user->subscription_status,
                'current_plan_id' => $user->plan_id,
                'current_plan' => $user->plan ? [
                    'id' => $user->plan->id,
                    'name' => $user->plan->name,
                    'slug' => $user->plan->slug,
                    'currency' => $user->plan->currency,
                    'price_cents' => $user->plan->price_cents,
                ] : null,
                'plans' => $plans,
            ],
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'payment_method' => ['nullable', 'string', 'in:card,upi'],
        ]);

        $plan = Plan::query()->where('is_active', true)->findOrFail((int) $validated['plan_id']);
        $paymentMethod = (string) ($validated['payment_method'] ?? 'card');

        if ($paymentMethod === 'upi') {
            throw ValidationException::withMessages([
                'payment_method' => ['UPI is not available for recurring Stripe subscriptions in this setup. Please use card.'],
            ]);
        }

        $result = $this->subscriptionService->startEmbeddedSubscription($user, $plan);

        return response()->json([
            'message' => $result['message'],
            'mode' => $result['mode'],
            'subscription_id' => $result['subscription_id'],
            'client_secret' => $result['client_secret'],
        ], 201);
    }

    public function sync(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $validated = $request->validate([
            'subscription_id' => ['required', 'string'],
        ]);

        $syncedUser = $this->subscriptionService->syncSubscription($validated['subscription_id']);

        if ($syncedUser === null) {
            return response()->json([
                'message' => 'Subscription not found or could not be synced yet.',
            ], 404);
        }

        return response()->json([
            'message' => 'Subscription synced successfully.',
            'data' => [
                'user' => $syncedUser->loadMissing('plan', 'currentProject'),
            ],
        ]);
    }
}
