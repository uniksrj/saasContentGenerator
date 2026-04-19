<?php

namespace App\Helpers;

class TextHelper
{
    public static function normalizeTitle(string $title): string
    {
        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9\s]/', ' ', $title) ?? $title;
        $title = preg_replace('/\s+/', ' ', $title) ?? $title;
        $title = trim($title);

        if ($title === '') {
            return '';
        }

        $stopWords = ['the', 'a', 'an', 'in', 'on', 'of', 'to'];
        $parts = explode(' ', $title);
        $filtered = array_values(array_filter($parts, static function (string $word) use ($stopWords): bool {
            return $word !== '' && !in_array($word, $stopWords, true);
        }));

        return trim(implode(' ', $filtered));
    }

    public static function isSimilarTopic(string $title1, string $title2): bool
    {
        $normalized1 = self::normalizeTitle($title1);
        $normalized2 = self::normalizeTitle($title2);

        if ($normalized1 === '' || $normalized2 === '') {
            return false;
        }

        $percent = 0.0;
        similar_text($normalized1, $normalized2, $percent);

        return $percent > 70.0;
    }

    public static function mergeSimilarTopics(array $topics): array
    {
        $merged = [];

        foreach ($topics as $topic) {
            $title = isset($topic['title']) ? (string) $topic['title'] : '';
            $description = isset($topic['description']) ? (string) $topic['description'] : '';
            $sourceType = isset($topic['source_type']) ? (string) $topic['source_type'] : '';
            $sourceUrl = isset($topic['source_url']) ? (string) $topic['source_url'] : '';
            $engagementScore = isset($topic['engagement_score']) ? (int) $topic['engagement_score'] : 0;

            $mergedIndex = null;

            foreach ($merged as $index => $existing) {
                if (self::isSimilarTopic($title, (string) ($existing['title'] ?? ''))) {
                    $mergedIndex = $index;
                    break;
                }
            }

            if ($mergedIndex !== null) {
                $existing = $merged[$mergedIndex];

                $sourceTypes = isset($existing['source_types']) && is_array($existing['source_types'])
                    ? $existing['source_types']
                    : [];
                if ($sourceType !== '' && !in_array($sourceType, $sourceTypes, true)) {
                    $sourceTypes[] = $sourceType;
                }

                $sourceUrls = isset($existing['source_urls']) && is_array($existing['source_urls'])
                    ? $existing['source_urls']
                    : [];
                if ($sourceUrl !== '' && !in_array($sourceUrl, $sourceUrls, true)) {
                    $sourceUrls[] = $sourceUrl;
                }

                $combinedDescription = trim((string) ($existing['description'] ?? ''));
                if ($description !== '' && stripos($combinedDescription, $description) === false) {
                    $combinedDescription = trim($combinedDescription . ' ' . $description);
                }

                $merged[$mergedIndex] = [
                    'title' => (string) ($existing['title'] ?? $title),
                    'description' => $combinedDescription,
                    'source_types' => $sourceTypes,
                    'source_urls' => $sourceUrls,
                    'source_count' => (int) (count($sourceTypes)),
                    'engagement_score' => max((int) ($existing['engagement_score'] ?? 0), $engagementScore),
                ];
            } else {
                $merged[] = [
                    'title' => $title,
                    'description' => trim($description),
                    'source_types' => $sourceType !== '' ? [$sourceType] : [],
                    'source_urls' => $sourceUrl !== '' ? [$sourceUrl] : [],
                    'source_count' => $sourceType !== '' ? 1 : 0,
                    'engagement_score' => $engagementScore,
                ];
            }
        }

        return $merged;
    }

    public static function calculateScore(array $topic, ?array &$breakdown = null): int
    {
        $score = 0;
        $breakdown = [
            'source' => 0,
            'engagement' => 0,
            'multi_source' => 0,
            'freshness' => 0,
        ];

        $sourceType = isset($topic['source_type']) ? strtolower((string) $topic['source_type']) : '';
        if ($sourceType === 'trends') {
            $score += 5;
            $breakdown['source'] = 5;
        } elseif ($sourceType === 'reddit') {
            $score += 2;
            $breakdown['source'] = 2;
        } elseif ($sourceType === 'rss') {
            $score += 1;
            $breakdown['source'] = 1;
        }

        $engagement = isset($topic['engagement_score']) ? (int) $topic['engagement_score'] : 0;
        if ($engagement > 10000) {
            $score += 5;
            $breakdown['engagement'] = 5;
        } elseif ($engagement > 5000) {
            $score += 4;
            $breakdown['engagement'] = 4;
        } elseif ($engagement > 1000) {
            $score += 3;
            $breakdown['engagement'] = 3;
        } elseif ($engagement > 100) {
            $score += 2;
            $breakdown['engagement'] = 2;
        }

        $sourceCount = isset($topic['source_count']) ? (int) $topic['source_count'] : 0;
        if ($sourceCount > 1) {
            $score += 3;
            $breakdown['multi_source'] = 3;
        }

        $publishedAt = $topic['published_at'] ?? $topic['created_at'] ?? $topic['date'] ?? null;
        $timestamp = null;
        if (is_int($publishedAt)) {
            $timestamp = $publishedAt;
        } elseif (is_string($publishedAt) && $publishedAt !== '') {
            $parsed = strtotime($publishedAt);
            if ($parsed !== false) {
                $timestamp = $parsed;
            }
        }

        if ($timestamp !== null) {
            $hoursAgo = (time() - $timestamp) / 3600;
            if ($hoursAgo < 6) {
                $score += 3;
                $breakdown['freshness'] = 3;
            } elseif ($hoursAgo < 24) {
                $score += 2;
                $breakdown['freshness'] = 2;
            } else {
                $score -= 1;
                $breakdown['freshness'] = -1;
            }
        } else {
            $score -= 1;
            $breakdown['freshness'] = -1;
        }

        return $score;
    }
}
