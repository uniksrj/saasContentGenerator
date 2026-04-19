<?php

namespace App\Services\Saas;

use App\Helpers\TextHelper;
use Illuminate\Support\Collection;

class TopicScoringService
{
    /**
     * @param array<int, array<string, mixed>> $topics
     * @return Collection<int, array<string, mixed>>
     */
    public function scoreAndFilter(array $topics, int $minScore = 4): Collection
    {
        return collect($topics)
            ->map(function (array $topic): array {
                $breakdown = [];
                $score = TextHelper::calculateScore($topic, $breakdown);

                $topic['score'] = $score;
                $topic['score_breakdown'] = $breakdown;

                return $topic;
            })
            ->filter(fn (array $topic): bool => (int) ($topic['score'] ?? 0) >= $minScore)
            ->sortByDesc(fn (array $topic): int => (int) ($topic['score'] ?? 0))
            ->values();
    }
}

