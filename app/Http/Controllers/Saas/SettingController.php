<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Saas\SubscriptionService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private readonly UsageLimitService $usageLimitService,
        private readonly SubscriptionService $subscriptionService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        $user->loadMissing('plan', 'currentProject');

        $plans = Plan::query()->where('is_active', true)->orderBy('price_cents')->get();

        return view('saas.settings.index', [
            'user' => $user,
            'plans' => $plans,
            'usageThisMonth' => $this->usageLimitService->usedThisMonth($user),
            'usageLimit' => $this->usageLimitService->monthlyArticleLimit($user),
            'tokenUsageThisMonth' => $this->usageLimitService->usedTokensThisMonth($user),
            'tokenUsageLimit' => $this->usageLimitService->monthlyTokenLimit($user),
            'generationSnapshot' => $this->usageLimitService->generationSnapshot($user),
            'hasActiveSubscription' => $this->subscriptionService->hasActiveSubscription($user),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated.');
    }
}
