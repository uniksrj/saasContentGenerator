<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Project;
use App\Models\Topic;
use App\Models\UsageLog;
use App\Services\Saas\TopicScoringService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AiBlogDraftGeneratorService
{
    public function __construct(
        private readonly RssTopicFetcherService $rssTopicFetcher,
        private readonly OpenAiBlogGeneratorService $openAiGenerator,
        private readonly TopicScoringService $topicScoringService,
        private readonly UsageLimitService $usageLimitService,
    ) {
    }

    /**
     * Backward compatible entry point.
     * If user/project are not provided, returns without generating.
     *
     * @return array<string, int>
     */
    public function run(?int $limit = null, ?int $userId = null, ?int $projectId = null): array
    {
        if ($userId === null || $projectId === null) {
            return [
                'fetched' => 0,
                'qualified' => 0,
                'generated' => 0,
                'duplicates' => 0,
                'failed' => 0,
            ];
        }

        return $this->generateForProject($userId, $projectId, $limit);
    }

    /**
     * @return array<string, int>
     */
    public function generateForProject(int $userId, int $projectId, ?int $generateLimit = null): array
    {
        $generateLimit = max(1, (int) ($generateLimit ?? $this->blogConfig('default_limit', 5)));
        $fetchLimit = max($generateLimit * 3, (int) $this->blogConfig('saas.fetch_limit', 20));

        $project = Project::query()->ownedBy($userId)->findOrFail($projectId);
        $access = $this->usageLimitService->generationSnapshot($userId);

        $storedTopics = $this->fetchAndStoreTopics($userId, $project, $fetchLimit);
        $allowedGenerationCount = max(0, min($generateLimit, (int) $access['remaining_articles']));

        if ($allowedGenerationCount === 0) {
            return [
                'fetched' => $storedTopics->count(),
                'qualified' => $storedTopics->count(),
                'generated' => 0,
                'duplicates' => 0,
                'failed' => 0,
                'tokens_used' => 0,
                'message' => (string) $access['message'],
            ];
        }

        $candidates = Topic::query()
            ->where('user_id', $userId)
            ->where('project_id', $project->id)
            ->whereIn('status', ['fetched', 'failed'])
            ->orderByDesc('score')
            ->orderByDesc('published_at')
            ->limit($allowedGenerationCount)
            ->get();

        $result = [
            'fetched' => $storedTopics->count(),
            'qualified' => $storedTopics->count(),
            'generated' => 0,
            'duplicates' => 0,
            'failed' => 0,
            'tokens_used' => 0,
            'message' => 'Generation run completed.',
        ];

        foreach ($candidates as $topic) {
            $currentAccess = $this->usageLimitService->generationSnapshot($userId);
            if (!($currentAccess['can_generate'] ?? false)) {
                $result['message'] = (string) $currentAccess['message'];
                break;
            }

            if ($this->isDuplicateArticle($project->id, $topic)) {
                $topic->update(['status' => 'duplicate']);
                $result['duplicates']++;
                continue;
            }

            $generated = $this->openAiGenerator->generate($topic->raw_payload ?? $topic->toArray());
            if ($generated === null) {
                $topic->update(['status' => 'failed']);
                $result['failed']++;
                continue;
            }

            $article = $this->storeGeneratedArticle($userId, $project->id, $topic, $generated);
            if ($article === null) {
                $topic->update(['status' => 'failed']);
                $result['failed']++;
                continue;
            }

            $topic->update(['status' => 'processed']);
            $result['tokens_used'] += $this->logUsage($userId, $project->id, $article->id, $topic->id, $generated['usage'] ?? []);
            $result['generated']++;
        }

        return $result;
    }

    /**
     * @param array<int, array<string, mixed>> $topics
     * @return array<string, int>
     */
    public function runFromProvidedTopics(array $topics, ?int $limit = null, ?int $userId = null, ?int $projectId = null): array
    {
        if ($userId === null || $projectId === null) {
            return [
                'fetched' => 0,
                'qualified' => 0,
                'generated' => 0,
                'duplicates' => 0,
                'failed' => 0,
            ];
        }

        $project = Project::query()->ownedBy($userId)->findOrFail($projectId);
        $limit = max(1, (int) ($limit ?? count($topics) ?: 1));
        $access = $this->usageLimitService->generationSnapshot($userId);

        $storedTopics = $this->storeProvidedTopics($userId, $project, $topics);
        $allowedGenerationCount = max(0, min($limit, (int) $access['remaining_articles']));
        $selected = $storedTopics->sortByDesc('score')->take($allowedGenerationCount)->values();

        $result = [
            'fetched' => $storedTopics->count(),
            'qualified' => $storedTopics->count(),
            'generated' => 0,
            'duplicates' => 0,
            'failed' => 0,
            'tokens_used' => 0,
            'message' => $allowedGenerationCount > 0 ? 'Generation run completed.' : (string) $access['message'],
        ];

        if ($allowedGenerationCount === 0) {
            return $result;
        }

        foreach ($selected as $topic) {
            $currentAccess = $this->usageLimitService->generationSnapshot($userId);
            if (!($currentAccess['can_generate'] ?? false)) {
                $result['message'] = (string) $currentAccess['message'];
                break;
            }

            if ($this->isDuplicateArticle($project->id, $topic)) {
                $topic->update(['status' => 'duplicate']);
                $result['duplicates']++;
                continue;
            }

            $generated = $this->openAiGenerator->generate($topic->raw_payload ?? $topic->toArray());
            if ($generated === null) {
                $topic->update(['status' => 'failed']);
                $result['failed']++;
                continue;
            }

            $article = $this->storeGeneratedArticle($userId, $project->id, $topic, $generated);
            if ($article === null) {
                $topic->update(['status' => 'failed']);
                $result['failed']++;
                continue;
            }

            $topic->update(['status' => 'processed']);
            $result['tokens_used'] += $this->logUsage($userId, $project->id, $article->id, $topic->id, $generated['usage'] ?? []);
            $result['generated']++;
        }

        return $result;
    }

    /**
     * @return EloquentCollection<int, Topic>
     */
    public function fetchAndStoreTopics(int $userId, Project $project, ?int $limit = null): EloquentCollection
    {
        $limit = max(1, (int) ($limit ?? $this->blogConfig('saas.fetch_limit', 20)));
        $minScore = max(0, (int) $this->blogConfig('saas.min_topic_score', 4));

        $rawTopics = $this->rssTopicFetcher->fetchTrendingTopics($limit);
        $qualifiedTopics = $this->topicScoringService->scoreAndFilter($rawTopics, $minScore);

        return $this->storeProvidedTopics($userId, $project, $qualifiedTopics->all());
    }

    /**
     * @param array<int, array<string, mixed>> $topics
     * @return EloquentCollection<int, Topic>
     */
    private function storeProvidedTopics(int $userId, Project $project, array $topics): EloquentCollection
    {
        $stored = [];

        foreach ($topics as $topic) {
            $title = trim((string) ($topic['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $sourceUrl = trim((string) ($topic['source_url'] ?? ''));

            $stored[] = DB::transaction(function () use ($userId, $project, $topic, $title, $sourceUrl): Topic {
                return Topic::query()->updateOrCreate(
                    [
                        'user_id' => $userId,
                        'project_id' => $project->id,
                        'title' => $title,
                        'source_url' => $sourceUrl !== '' ? $sourceUrl : null,
                    ],
                    [
                        'description' => trim((string) ($topic['description'] ?? '')),
                        'source_type' => trim((string) ($topic['source_type'] ?? 'rss')),
                        'engagement_score' => (int) ($topic['engagement_score'] ?? 0),
                        'score' => (int) ($topic['score'] ?? 0),
                        'score_breakdown' => $topic['score_breakdown'] ?? null,
                        'published_at' => $topic['published_at'] ?? null,
                        'status' => 'fetched',
                        'raw_payload' => $topic,
                    ]
                );
            });
        }

        return new EloquentCollection($stored);
    }

    private function isDuplicateArticle(int $projectId, Topic $topic): bool
    {
        $normalizedTitle = Str::lower(trim($topic->title));
        $slug = Str::slug($topic->title);
        $sourceUrl = trim((string) $topic->source_url);

        return Article::query()
            ->where('project_id', $projectId)
            ->where(function ($query) use ($normalizedTitle, $slug, $sourceUrl) {
                if ($normalizedTitle !== '') {
                    $query->whereRaw('LOWER(title) = ?', [$normalizedTitle]);
                }

                if ($slug !== '') {
                    $query->orWhere('slug', $slug);
                }

                if ($sourceUrl !== '') {
                    $query->orWhere('source_url', $sourceUrl);
                }
            })
            ->exists();
    }

    /**
     * @param array<string, mixed> $generated
     */
    private function storeGeneratedArticle(int $userId, int $projectId, Topic $topic, array $generated): ?Article
    {
        $title = trim((string) ($generated['title'] ?? ''));
        $content = trim((string) ($generated['content'] ?? ''));
        $metaDescription = trim((string) ($generated['meta_description'] ?? ''));

        if ($title === '' || $content === '') {
            return null;
        }

        $category = $this->openAiGenerator->resolveCategorySlug(
            (string) ($generated['category_slug'] ?? ''),
            $topic->raw_payload ?? [],
            $title . ' ' . $metaDescription
        );

        $wordCount = str_word_count(strip_tags($content));

        return DB::transaction(function () use ($userId, $projectId, $topic, $title, $content, $metaDescription, $category, $wordCount): Article {
            return Article::query()->create([
                'user_id' => $userId,
                'project_id' => $projectId,
                'topic_id' => $topic->id,
                'title' => $title,
                'slug' => $this->generateUniqueArticleSlug($projectId, $title),
                'meta_title' => Str::limit($title, 255, ''),
                'meta_description' => $metaDescription,
                'content' => $content,
                'category' => $category,
                'source_type' => $topic->source_type,
                'source_url' => $topic->source_url,
                'status' => 'draft',
                'is_published' => false,
                'is_featured' => false,
                'tags' => array_values(array_unique([$category, 'ai-generated', (string) $topic->source_type])),
                'table_of_contents' => $this->extractTableOfContents($content),
                'word_count' => $wordCount,
                'reading_time' => max(1, (int) ceil($wordCount / 200)),
            ]);
        });
    }

    private function logUsage(int $userId, int $projectId, int $articleId, int $topicId, array $usage = []): int
    {
        UsageLog::query()->create([
            'user_id' => $userId,
            'project_id' => $projectId,
            'action' => 'article_generated',
            'units' => 1,
            'metadata' => [
                'article_id' => $articleId,
                'topic_id' => $topicId,
            ],
            'used_on' => now()->toDateString(),
        ]);

        $totalTokens = (int) ($usage['total_tokens'] ?? 0);
        if ($totalTokens > 0) {
            UsageLog::query()->create([
                'user_id' => $userId,
                'project_id' => $projectId,
                'action' => 'tokens_used',
                'units' => $totalTokens,
                'metadata' => [
                    'article_id' => $articleId,
                    'topic_id' => $topicId,
                    'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? 0),
                    'completion_tokens' => (int) ($usage['completion_tokens'] ?? 0),
                ],
                'used_on' => now()->toDateString(),
            ]);
        }

        return $totalTokens;
    }

    private function generateUniqueArticleSlug(int $projectId, string $title): string
    {
        $base = Str::slug($title);
        $base = $base !== '' ? $base : 'ai-article';
        $slug = $base;
        $counter = 1;

        while (Article::query()->where('project_id', $projectId)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    private function extractTableOfContents(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//h2 | //h3 | //h4');

        if ($nodes === false) {
            return [];
        }

        $toc = [];
        $index = 1;

        foreach ($nodes as $node) {
            $text = trim($node->textContent ?? '');
            if ($text === '') {
                continue;
            }

            $toc[] = [
                'id' => 'section-' . $index,
                'title' => $text,
                'level' => (int) str_replace('h', '', strtolower($node->nodeName)),
                'slug' => Str::slug($text) ?: 'section-' . $index,
                'order' => $index,
            ];

            $index++;
        }

        return $toc;
    }

    private function blogConfig(string $key, mixed $default = null): mixed
    {
        $blogValue = config("blog.{$key}");
        if ($blogValue !== null) {
            return $blogValue;
        }

        return config("blog_automation.{$key}", $default);
    }
}
