<?php

namespace App\Services\Saas;

use App\Models\Article;
use App\Models\Topic;
use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function record(int|User $user, ?int $projectId, string $action, int $units = 1, array $metadata = []): UsageLog
    {
        return UsageLog::query()->create([
            'user_id' => $this->resolveUserId($user),
            'project_id' => $projectId,
            'action' => $action,
            'units' => $units,
            'metadata' => $metadata,
            'used_on' => now()->toDateString(),
        ]);
    }

    public function recentForUser(int|User $user, int $perPage = 25): LengthAwarePaginator
    {
        $logs = UsageLog::query()
            ->where('user_id', $this->resolveUserId($user))
            ->with([
                'user:id,name,email',
                'project:id,name,slug',
            ])
            ->latest()
            ->paginate($perPage);

        $topicIds = collect($logs->items())
            ->map(fn (UsageLog $log): int => (int) data_get($log->metadata, 'topic_id', 0))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $articleIds = collect($logs->items())
            ->map(fn (UsageLog $log): int => (int) data_get($log->metadata, 'article_id', 0))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $topicsById = Topic::query()
            ->whereIn('id', $topicIds)
            ->pluck('title', 'id');

        $articlesById = Article::query()
            ->whereIn('id', $articleIds)
            ->pluck('title', 'id');

        return $logs->through(function (UsageLog $log) use ($topicsById, $articlesById): array {
            $topicId = (int) data_get($log->metadata, 'topic_id', 0);
            $articleId = (int) data_get($log->metadata, 'article_id', 0);

            return [
                'id' => $log->id,
                'action' => $log->action,
                'units' => $log->units,
                'used_on' => $log->used_on?->toDateString(),
                'created_at' => $log->created_at?->toISOString(),
                'metadata' => $log->metadata ?? [],
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                    'email' => $log->user->email,
                ] : null,
                'project' => $log->project ? [
                    'id' => $log->project->id,
                    'name' => $log->project->name,
                    'slug' => $log->project->slug,
                ] : null,
                'topic' => $topicId > 0 ? [
                    'id' => $topicId,
                    'title' => (string) ($topicsById[$topicId] ?? data_get($log->metadata, 'topic_title', 'Topic')),
                ] : null,
                'article' => $articleId > 0 ? [
                    'id' => $articleId,
                    'title' => (string) ($articlesById[$articleId] ?? data_get($log->metadata, 'article_title', 'Article')),
                ] : null,
            ];
        });
    }

    private function resolveUserId(int|User $user): int
    {
        return $user instanceof User ? $user->id : $user;
    }
}
