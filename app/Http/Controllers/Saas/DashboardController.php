<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\UsageLog;
use App\Services\Saas\CurrentProjectResolver;
use App\Services\Saas\SubscriptionService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UsageLimitService $usageLimitService,
        private readonly CurrentProjectResolver $projectResolver,
        private readonly SubscriptionService $subscriptionService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        $user->loadMissing('plan');

        $project = $this->projectResolver->resolveForUser($user);
        $projectId = $project?->id;

        $articleQuery = Article::query()->where('user_id', $user->id);
        if ($projectId !== null) {
            $articleQuery->where('project_id', $projectId);
        }

        $totalArticles = (clone $articleQuery)->count();
        $draftArticles = (clone $articleQuery)->where('status', 'draft')->count();
        $publishedArticles = (clone $articleQuery)->where('status', 'published')->count();

        $recentActivity = UsageLog::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(8)
            ->get();

        $recentArticles = Article::query()
            ->where('user_id', $user->id)
            ->when($projectId !== null, fn ($query) => $query->where('project_id', $projectId))
            ->latest()
            ->limit(6)
            ->get();

        $usageThisMonth = $this->usageLimitService->usedThisMonth($user);
        $usageLimit = $this->usageLimitService->monthlyArticleLimit($user);
        $tokenUsageThisMonth = $this->usageLimitService->usedTokensThisMonth($user);
        $tokenUsageLimit = $this->usageLimitService->monthlyTokenLimit($user);
        $generationSnapshot = $this->usageLimitService->generationSnapshot($user);

        return view('saas.dashboard.index', [
            'user' => $user,
            'currentProject' => $project,
            'projectCount' => Project::query()->ownedBy($user->id)->count(),
            'totalArticles' => $totalArticles,
            'draftArticles' => $draftArticles,
            'publishedArticles' => $publishedArticles,
            'usageThisMonth' => $usageThisMonth,
            'usageLimit' => $usageLimit,
            'remainingCredits' => $this->usageLimitService->remainingArticleCredits($user),
            'usagePercent' => $usageLimit > 0 ? min(100, (int) round(($usageThisMonth / $usageLimit) * 100)) : 0,
            'tokenUsageThisMonth' => $tokenUsageThisMonth,
            'tokenUsageLimit' => $tokenUsageLimit,
            'tokenUsagePercent' => $tokenUsageLimit > 0 ? min(100, (int) round(($tokenUsageThisMonth / $tokenUsageLimit) * 100)) : 0,
            'generationSnapshot' => $generationSnapshot,
            'hasActiveSubscription' => $this->subscriptionService->hasActiveSubscription($user),
            'recentArticles' => $recentArticles,
            'recentActivity' => $recentActivity,
        ]);
    }
}
