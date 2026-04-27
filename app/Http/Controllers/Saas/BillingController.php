<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Saas\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RuntimeException;

class BillingController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ]);

        try {
            $plan = Plan::query()->where('is_active', true)->findOrFail((int) $validated['plan_id']);
            $result = $this->subscriptionService->startCheckout($user, $plan);
        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'subscription' => $exception->getMessage(),
            ]);
        }

        if ($result['mode'] === 'stripe' && filled($result['url'])) {
            return redirect()->away((string) $result['url']);
        }

        return redirect()->route('saas.settings.index')->with('success', $result['message']);
    }

    public function embeddedSubscribe(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ]);

        try {
            $plan = Plan::query()->where('is_active', true)->findOrFail((int) $validated['plan_id']);
            $result = $this->subscriptionService->startEmbeddedSubscription($user, $plan);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'mode' => $result['mode'],
            'subscription_id' => $result['subscription_id'],
            'client_secret' => $result['client_secret'],
        ]);
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
        ]);
    }

    public function success(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');
        if ($sessionId !== '') {
            $this->subscriptionService->syncCheckoutSession($sessionId);
        }

        return redirect()->route('saas.settings.index')->with('success', 'Subscription confirmed successfully.');
    }

    public function cancelled(): RedirectResponse
    {
        return redirect()->route('saas.settings.index')->withErrors([
            'subscription' => 'Stripe checkout was cancelled before the subscription was completed.',
        ]);
    }

    public function cancel(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        try {
            $this->subscriptionService->cancelSubscription($user);
        } catch (RuntimeException $exception) {
            return back()->withErrors([
                'subscription' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('saas.settings.index')->with('success', 'Subscription cancelled.');
    }

    public function webhook(Request $request): Response
    {
        $payload = (string) $request->getContent();
        $signature = $request->header('Stripe-Signature');

        abort_unless(
            $this->subscriptionService->verifyWebhookSignature($payload, $signature),
            400,
            'Invalid Stripe signature.'
        );

        $event = json_decode($payload, true);
        if (is_array($event)) {
            $this->subscriptionService->handleWebhookEvent($event);
        }

        return response('Webhook handled.', 200);
    }
}
