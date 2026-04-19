<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Saas\SubscriptionService;
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
