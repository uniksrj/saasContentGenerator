<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UsageLog;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly UsageLimitService $usageLimitService)
    {
    }

    public function index(): View
    {
        $users = User::query()
            ->with(['plan', 'currentProject'])
            ->withCount(['projects', 'articles'])
            ->latest()
            ->get()
            ->map(function (User $user): User {
                $user->setAttribute('usage_snapshot', $this->usageLimitService->generationSnapshot($user));
                $user->setAttribute('used_articles', $this->usageLimitService->usedThisMonth($user));
                $user->setAttribute('used_tokens', $this->usageLimitService->usedTokensThisMonth($user));
                return $user;
            });

        $plans = Plan::query()->orderBy('price_cents')->get();

        $usageByAction = UsageLog::query()
            ->select('action', DB::raw('SUM(units) as total_units'))
            ->whereBetween('used_on', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->groupBy('action')
            ->pluck('total_units', 'action');

        return view('saas.admin.index', [
            'users' => $users,
            'plans' => $plans,
            'recentUsage' => UsageLog::query()->with(['user', 'project'])->latest()->limit(20)->get(),
            'adminStats' => [
                'users' => User::query()->count(),
                'admins' => User::query()->where('role', 'admin')->count(),
                'active_subscriptions' => User::query()->whereIn('subscription_status', ['active', 'trialing'])->count(),
                'articles_generated' => (int) ($usageByAction['article_generated'] ?? 0),
                'tokens_used' => (int) ($usageByAction['tokens_used'] ?? 0),
            ],
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,user'],
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'subscription_status' => ['required', 'in:inactive,active,trialing,past_due,cancelled'],
            'article_limit_override' => ['nullable', 'integer', 'min:0'],
            'token_limit_override' => ['nullable', 'integer', 'min:0'],
            'current_period_ends_at' => ['nullable', 'date'],
        ]);

        $user->update([
            'role' => $validated['role'],
            'plan_id' => $validated['plan_id'] ?? null,
            'subscription_status' => $validated['subscription_status'],
            'article_limit_override' => $validated['article_limit_override'] ?? null,
            'token_limit_override' => $validated['token_limit_override'] ?? null,
            'subscribed_at' => in_array($validated['subscription_status'], ['active', 'trialing'], true)
                ? ($user->subscribed_at ?? now())
                : null,
            'current_period_ends_at' => $validated['current_period_ends_at'] ?? $user->current_period_ends_at,
            'subscription_ends_at' => $validated['subscription_status'] === 'cancelled' ? now() : null,
        ]);

        return back()->with('success', 'User access updated.');
    }

    public function updatePlan(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'monthly_article_limit' => ['required', 'integer', 'min:0'],
            'monthly_token_limit' => ['required', 'integer', 'min:0'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'billing_interval' => ['required', 'string', 'in:month,year'],
            'is_active' => ['nullable', 'boolean'],
            'features' => ['nullable', 'string'],
        ]);

        $features = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['features'] ?? '')))
            ->map(fn (?string $line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();

        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'monthly_article_limit' => (int) $validated['monthly_article_limit'],
            'monthly_token_limit' => (int) $validated['monthly_token_limit'],
            'price_cents' => (int) $validated['price_cents'],
            'currency' => strtoupper($validated['currency']),
            'stripe_price_id' => $validated['stripe_price_id'] ?: null,
            'billing_interval' => $validated['billing_interval'],
            'is_active' => $request->boolean('is_active'),
            'features' => $features,
        ]);

        return back()->with('success', 'Plan updated.');
    }
}
