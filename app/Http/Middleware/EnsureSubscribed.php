<?php

namespace App\Http\Middleware;

use App\Services\Saas\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        if ($this->subscriptionService->hasActiveSubscription($user)) {
            return $next($request);
        }

        $message = 'An active subscription is required before generating AI content.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 402);
        }

        return redirect()
            ->route('saas.settings.index')
            ->withErrors(['subscription' => $message]);
    }
}
