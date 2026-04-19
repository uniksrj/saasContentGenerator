<?php

namespace App\Services\Saas;

use App\Models\User;
use App\Models\UsageLog;

class UsageLimitService
{
    public function canGenerateArticle(int|User $user, int $units = 1): bool
    {
        $snapshot = $this->generationSnapshot($user);

        return $snapshot['can_generate']
            && $snapshot['remaining_articles'] >= $units
            && $snapshot['remaining_tokens'] > 0;
    }

    public function remainingArticleCredits(int|User $user): int
    {
        $limit = $this->monthlyArticleLimit($user);
        $used = $this->usedThisMonth($user);

        return max(0, $limit - $used);
    }

    public function remainingTokenCredits(int|User $user): int
    {
        $limit = $this->monthlyTokenLimit($user);
        $used = $this->usedTokensThisMonth($user);

        return max(0, $limit - $used);
    }

    public function usedThisMonth(int|User $user): int
    {
        $userId = $this->resolveUser($user)->id;

        return (int) UsageLog::query()
            ->where('user_id', $userId)
            ->where('action', 'article_generated')
            ->whereBetween('used_on', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('units');
    }

    public function usedTokensThisMonth(int|User $user): int
    {
        $userId = $this->resolveUser($user)->id;

        return (int) UsageLog::query()
            ->where('user_id', $userId)
            ->where('action', 'tokens_used')
            ->whereBetween('used_on', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('units');
    }

    public function monthlyArticleLimit(int|User $user): int
    {
        $user = $this->resolveUser($user);

        if ($user->article_limit_override !== null) {
            return (int) $user->article_limit_override;
        }

        if ($user->plan?->monthly_article_limit !== null) {
            return (int) $user->plan->monthly_article_limit;
        }

        return (int) config('blog.saas.default_monthly_article_limit', 30);
    }

    public function monthlyTokenLimit(int|User $user): int
    {
        $user = $this->resolveUser($user);

        if ($user->token_limit_override !== null) {
            return (int) $user->token_limit_override;
        }

        if ($user->plan?->monthly_token_limit !== null) {
            return (int) $user->plan->monthly_token_limit;
        }

        return (int) config('blog.saas.default_monthly_token_limit', 120000);
    }

    /**
     * @return array<string, bool|int|string>
     */
    public function generationSnapshot(int|User $user): array
    {
        $user = $this->resolveUser($user);
        $remainingArticles = $this->remainingArticleCredits($user);
        $remainingTokens = $this->remainingTokenCredits($user);

        if (!$user->hasActiveSubscription()) {
            return [
                'can_generate' => false,
                'remaining_articles' => $remainingArticles,
                'remaining_tokens' => $remainingTokens,
                'message' => 'You need an active subscription before generating content.',
            ];
        }

        if ($remainingArticles <= 0) {
            return [
                'can_generate' => false,
                'remaining_articles' => 0,
                'remaining_tokens' => $remainingTokens,
                'message' => 'Your monthly article limit has been reached.',
            ];
        }

        if ($remainingTokens <= 0) {
            return [
                'can_generate' => false,
                'remaining_articles' => $remainingArticles,
                'remaining_tokens' => 0,
                'message' => 'Your monthly token limit has been reached.',
            ];
        }

        return [
            'can_generate' => true,
            'remaining_articles' => $remainingArticles,
            'remaining_tokens' => $remainingTokens,
            'message' => 'Generation available.',
        ];
    }

    private function resolveUser(int|User $user): User
    {
        if ($user instanceof User) {
            return $user->relationLoaded('plan') ? $user : $user->loadMissing('plan');
        }

        return User::query()->with('plan')->findOrFail($user);
    }
}
