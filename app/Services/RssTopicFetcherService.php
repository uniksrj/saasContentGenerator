<?php

namespace App\Services;

use App\Helpers\TextHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RssTopicFetcherService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTrendingTopics(?int $limit = null): array
    {
        $limit = max(1, (int) ($limit ?? $this->blogConfig('default_limit', 5)));
        $maxCandidates = max($limit, $limit * max(2, (int) $this->blogConfig('max_candidates_multiplier', 5)));

        $baseTopics = array_merge(
            $this->fetchRssTopics($limit * 2),
            $this->fetchGoogleTrendsTopics($limit * 2),
            $this->fetchRedditTopics($limit * 2)
        );

        $priorityCategories = $this->selectPriorityCategories($baseTopics);
        $categoryDrivenTopics = $this->fetchCategoryDrivenTopics($priorityCategories, $maxCandidates);

        $allTopics = array_merge($baseTopics, $categoryDrivenTopics);

        if (empty($allTopics)) {
            return [];
        }

        $mergedTopics = $this->mergeSimilarTopics($allTopics);

        return $this->curateTopics($mergedTopics, $limit);
    }

    /**
     * @param array<int, array<string, mixed>> $baseTopics
     * @return array<int, string>
     */
    private function selectPriorityCategories(array $baseTopics): array
    {
        $scores = [];

        foreach ($baseTopics as $topic) {
            $category = $this->resolveCategorySlug(
                (string) ($topic['category_slug'] ?? ''),
                (string) (($topic['title'] ?? '') . ' ' . ($topic['description'] ?? '')),
                (string) ($topic['source_type'] ?? 'rss')
            );

            $scores[$category] = (int) ($scores[$category] ?? 0) + 1;
        }

        arsort($scores);

        $maxDynamicCategories = max(1, (int) $this->blogConfig('max_dynamic_categories', 4));
        $selected = array_slice(array_keys($scores), 0, $maxDynamicCategories);

        if (!empty($selected)) {
            return $selected;
        }

        return array_slice($this->allowedCategorySlugs(), 0, $maxDynamicCategories);
    }

    /**
     * @param array<int, string> $categories
     * @return array<int, array<string, mixed>>
     */
    private function fetchCategoryDrivenTopics(array $categories, int $maxCandidates): array
    {
        if (empty($categories)) {
            return [];
        }

        $topics = [];
        $perCategoryLimit = max(2, (int) ceil($maxCandidates / max(1, count($categories))));

        foreach ($categories as $category) {
            $sources = $this->sourcesForCategory($category);
            if (empty($sources)) {
                continue;
            }

            $perSourceLimit = max(2, (int) ceil($perCategoryLimit / max(1, count($sources))));

            foreach ($sources as $source) {
                $sourceTopics = $this->fetchCategorySourceTopics($source, $category, $perSourceLimit);
                if (!empty($sourceTopics)) {
                    $topics = array_merge($topics, $sourceTopics);
                }

                if (count($topics) >= $maxCandidates) {
                    return array_slice($topics, 0, $maxCandidates);
                }
            }
        }

        return $topics;
    }

    /**
     * @return array<int, string>
     */
    private function sourcesForCategory(string $category): array
    {
        $sources = $this->blogConfig("category_source_map.{$category}", []);
        return is_array($sources) ? array_values(array_unique(array_map('strtolower', $sources))) : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchCategorySourceTopics(string $source, string $category, int $limit): array
    {
        return match (strtolower($source)) {
            'rss' => $this->fetchRssTopicsByCategory($category, $limit),
            'trends' => $this->fetchGoogleTrendsTopics($limit),
            'reddit' => $this->fetchRedditTopicsForCategory($category, $limit),
            'hackernews' => $this->fetchHackerNewsTopics($category, $limit),
            'github' => $this->fetchGitHubTopics($category, $limit),
            'devto' => $this->fetchDevToTopics($category, $limit),
            'medium' => $this->fetchMediumTopics($category, $limit),
            'newsapi' => $this->fetchNewsApiTopics($category, $limit),
            'stackoverflow' => $this->fetchStackOverflowTopics($category, $limit),
            'gdelt' => $this->fetchGdeltTopics($category, $limit),
            default => [],
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRssTopics(int $limit): array
    {
        $feeds = $this->blogConfig('feeds', []);
        if (!is_array($feeds) || empty($feeds)) {
            return [];
        }

        $maxPerFeed = max(1, (int) ceil($limit / max(1, count($feeds))));
        $topics = [];

        foreach ($feeds as $feed) {
            $url = trim((string) ($feed['url'] ?? ''));
            $topic = trim((string) ($feed['topic'] ?? 'technology'));
            $category = trim((string) ($feed['category'] ?? 'technology'));

            if ($url === '') {
                continue;
            }

            $parsedItems = $this->fetchRssFromUrl($url, $topic, $category, 'rss');
            $topics = array_merge($topics, array_slice($parsedItems, 0, $maxPerFeed));
        }

        return $topics;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRssTopicsByCategory(string $category, int $limit): array
    {
        $feeds = $this->blogConfig('feeds', []);
        if (!is_array($feeds) || empty($feeds)) {
            return [];
        }

        $topics = [];
        foreach ($feeds as $feed) {
            $feedCategory = strtolower(trim((string) ($feed['category'] ?? '')));
            if ($feedCategory !== strtolower($category)) {
                continue;
            }

            $url = trim((string) ($feed['url'] ?? ''));
            if ($url === '') {
                continue;
            }

            $topicHint = trim((string) ($feed['topic'] ?? $category));
            $topics = array_merge($topics, $this->fetchRssFromUrl($url, $topicHint, $category, 'rss'));
        }

        return array_slice($topics, 0, max(1, $limit));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchGoogleTrendsTopics(int $limit): array
    {
        if (!$this->blogConfig('trends.enabled', true)) {
            return [];
        }

        $url = trim((string) $this->blogConfig('trends.url', ''));
        if ($url === '') {
            return [];
        }

        $category = trim((string) $this->blogConfig('trends.category', 'news-updates'));
        return array_slice($this->fetchRssFromUrl($url, 'trends', $category, 'trends'), 0, max(1, $limit));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRedditTopics(int $limit): array
    {
        $subreddits = $this->blogConfig('reddit.subreddits', ['technology', 'travel', 'productivity']);
        if (!is_array($subreddits) || empty($subreddits)) {
            return [];
        }

        return $this->fetchRedditBySubreddits($subreddits, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRedditTopicsForCategory(string $category, int $limit): array
    {
        $subreddits = $this->subredditsForCategory($category);
        if (empty($subreddits)) {
            return [];
        }

        return $this->fetchRedditBySubreddits($subreddits, $limit);
    }

    /**
     * @param array<int, string> $subreddits
     * @return array<int, array<string, mixed>>
     */
    private function fetchRedditBySubreddits(array $subreddits, int $limit): array
    {
        if (!$this->blogConfig('reddit.enabled', true)) {
            return [];
        }

        $topics = [];
        $userAgent = trim((string) $this->blogConfig('reddit.user_agent', 'myblog-bot/1.0 (+https://example.com)'));
        $configLimit = max(1, (int) $this->blogConfig('reddit.limit_per_subreddit', 8));
        $perSubredditLimit = min($configLimit, max(1, (int) ceil($limit / max(1, count($subreddits)))));
        foreach ($subreddits as $subreddit) {
            $subreddit = strtolower(trim((string) $subreddit));
            if ($subreddit === '') {
                continue;
            }

            try {
                $children = Cache::remember(
                    'ai_blog_reddit_' . md5($subreddit . '|' . $perSubredditLimit),
                    now()->addMinutes($this->cacheTtlMinutes()),
                    function () use ($subreddit, $userAgent, $perSubredditLimit) {
                        $response = Http::timeout(20)
                            ->acceptJson()
                            ->withHeaders(['User-Agent' => $userAgent])
                            ->get("https://www.reddit.com/r/{$subreddit}/top.json", [
                                'limit' => $perSubredditLimit,
                                't' => 'day',
                            ]);

                        if (!$response->successful()) {
                            throw new \RuntimeException('Reddit fetch failed: ' . $response->status());
                        }

                        return data_get($response->json(), 'data.children', []);
                    }
                );
            } catch (\Throwable $exception) {
                Log::warning('Reddit fetch exception', [
                    'subreddit' => $subreddit,
                    'error' => $exception->getMessage(),
                ]);
                continue;
            }

            if (!is_array($children)) {
                continue;
            }

            foreach ($children as $child) {
                $post = data_get($child, 'data', []);
                if (!is_array($post)) {
                    continue;
                }

                $title = trim((string) data_get($post, 'title', ''));
                if ($title === '') {
                    continue;
                }

                $selfText = trim(strip_tags((string) data_get($post, 'selftext', '')));
                $description = html_entity_decode($selfText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($description === '') {
                    $description = "Top Reddit discussion from r/{$subreddit}: {$title}. Community members are sharing practical insights and current perspectives.";
                }

                $permalink = trim((string) data_get($post, 'permalink', ''));
                $directUrl = trim((string) data_get($post, 'url_overridden_by_dest', data_get($post, 'url', '')));
                $sourceUrl = $permalink !== '' ? 'https://www.reddit.com' . $permalink : $directUrl;

                $topics[] = [
                    'title' => $title,
                    'description' => Str::limit($description, 500),
                    'source_url' => $sourceUrl,
                    'source_type' => 'reddit',
                    'category_slug' => $this->resolveCategorySlug(
                        (string) $this->blogConfig("reddit.category_map.{$subreddit}", $subreddit),
                        $title . ' ' . $description,
                        'reddit'
                    ),
                    'topic_hint' => $subreddit,
                    'engagement_score' => (int) data_get($post, 'ups', 0),
                    'published_at' => (int) data_get($post, 'created_utc', 0) > 0
                        ? date('Y-m-d H:i:s', (int) data_get($post, 'created_utc'))
                        : now()->toDateTimeString(),
                ];
            }
        }

        return $topics;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchHackerNewsTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('hackernews.enabled', true)) {
            return [];
        }

        try {
            $topUrl = (string) $this->blogConfig('hackernews.top_stories_url', 'https://hacker-news.firebaseio.com/v0/topstories.json');
            $itemUrlPattern = (string) $this->blogConfig('hackernews.item_url_pattern', 'https://hacker-news.firebaseio.com/v0/item/{id}.json');
            $maxIds = max((int) $this->blogConfig('hackernews.limit', 10), $limit * 2);

            $ids = Cache::remember('ai_blog_hn_top_ids', now()->addMinutes($this->cacheTtlMinutes()), function () use ($topUrl) {
                $response = Http::timeout(20)->acceptJson()->get($topUrl);
                if (!$response->successful()) {
                    throw new \RuntimeException('HN topstories fetch failed: ' . $response->status());
                }
                return $response->json();
            });

            if (!is_array($ids)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($ids, 0, $maxIds) as $id) {
                $id = (int) $id;
                if ($id <= 0) {
                    continue;
                }

                $item = Cache::remember('ai_blog_hn_item_' . $id, now()->addMinutes($this->cacheTtlMinutes()), function () use ($itemUrlPattern, $id) {
                    $response = Http::timeout(20)->acceptJson()->get(str_replace('{id}', (string) $id, $itemUrlPattern));
                    if (!$response->successful()) {
                        return null;
                    }
                    return $response->json();
                });

                if (!is_array($item)) {
                    continue;
                }

                $title = trim((string) data_get($item, 'title', ''));
                if ($title === '') {
                    continue;
                }

                $description = trim((string) data_get($item, 'text', ''));
                $description = $description !== ''
                    ? trim(strip_tags(html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8')))
                    : "Hacker News discussion: {$title}";

                $sourceUrl = trim((string) data_get($item, 'url', ''));
                if ($sourceUrl === '') {
                    $sourceUrl = 'https://news.ycombinator.com/item?id=' . $id;
                }

                $topics[] = [
                    'title' => $title,
                    'description' => Str::limit($description, 500),
                    'source_url' => $sourceUrl,
                    'source_type' => 'hackernews',
                    'category_slug' => $this->resolveCategorySlug($category, $title . ' ' . $description, 'hackernews'),
                    'topic_hint' => 'hackernews',
                    'engagement_score' => (int) data_get($item, 'score', 0),
                    'published_at' => (int) data_get($item, 'time', 0) > 0
                        ? date('Y-m-d H:i:s', (int) data_get($item, 'time'))
                        : now()->toDateTimeString(),
                ];

                if (count($topics) >= $limit) {
                    break;
                }
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('Hacker News fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchGitHubTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('github.enabled', true)) {
            return [];
        }

        try {
            $url = (string) $this->blogConfig('github.search_url', 'https://api.github.com/search/repositories');
            $query = (string) $this->blogConfig('github.query', 'stars:>1000');
            $categoryQuery = $this->keywordQueryForCategory($category);
            if ($categoryQuery !== '') {
                $query .= ' ' . $categoryQuery;
            }

            $perPage = min(25, max(1, (int) $this->blogConfig('github.per_page', 10), $limit));
            $payload = Cache::remember('ai_blog_github_' . md5($query . '|' . $perPage), now()->addMinutes($this->cacheTtlMinutes()), function () use ($url, $query, $perPage) {
                $response = Http::timeout(20)
                    ->acceptJson()
                    ->withHeaders(['User-Agent' => 'myblog-bot/1.0'])
                    ->get($url, [
                        'q' => $query,
                        'sort' => (string) $this->blogConfig('github.sort', 'stars'),
                        'order' => (string) $this->blogConfig('github.order', 'desc'),
                        'per_page' => $perPage,
                    ]);

                if (!$response->successful()) {
                    throw new \RuntimeException('GitHub fetch failed: ' . $response->status());
                }

                return $response->json();
            });

            $items = data_get($payload, 'items', []);
            if (!is_array($items)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($items, 0, $limit) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $name = trim((string) data_get($item, 'full_name', data_get($item, 'name', '')));
                if ($name === '') {
                    continue;
                }

                $description = trim((string) data_get($item, 'description', ''));
                if ($description === '') {
                    $description = 'Trending GitHub project related to ' . $category . '.';
                }

                $topics[] = [
                    'title' => Str::limit($name . ': ' . $description, 160),
                    'description' => Str::limit($description, 500),
                    'source_url' => trim((string) data_get($item, 'html_url', '')),
                    'source_type' => 'github',
                    'category_slug' => $this->resolveCategorySlug($category, $name . ' ' . $description, 'github'),
                    'topic_hint' => 'github',
                    'engagement_score' => (int) data_get($item, 'stargazers_count', 0),
                    'published_at' => trim((string) data_get($item, 'updated_at', now()->toDateTimeString())),
                ];
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('GitHub fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchDevToTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('devto.enabled', true)) {
            return [];
        }

        try {
            $url = (string) $this->blogConfig('devto.url', 'https://dev.to/api/articles');
            $tag = $this->devtoTagForCategory($category);
            $perPage = min(25, max(1, (int) $this->blogConfig('devto.per_page', 10), $limit));

            $items = Cache::remember('ai_blog_devto_' . md5($category . '|' . $tag . '|' . $perPage), now()->addMinutes($this->cacheTtlMinutes()), function () use ($url, $tag, $perPage) {
                $query = ['per_page' => $perPage];
                if ($tag !== '') {
                    $query['tag'] = $tag;
                }

                $response = Http::timeout(20)
                    ->acceptJson()
                    ->withHeaders(['User-Agent' => 'myblog-bot/1.0'])
                    ->get($url, $query);

                if (!$response->successful()) {
                    throw new \RuntimeException('Dev.to fetch failed: ' . $response->status());
                }

                return $response->json();
            });

            if (!is_array($items)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($items, 0, $limit) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $title = trim((string) data_get($item, 'title', ''));
                if ($title === '') {
                    continue;
                }

                $description = trim((string) data_get($item, 'description', data_get($item, 'body_markdown', '')));
                if ($description === '') {
                    $description = 'Trending Dev.to article related to ' . $category . '.';
                }

                $topics[] = [
                    'title' => $title,
                    'description' => Str::limit(strip_tags($description), 500),
                    'source_url' => trim((string) data_get($item, 'url', '')),
                    'source_type' => 'devto',
                    'category_slug' => $this->resolveCategorySlug($category, $title . ' ' . $description, 'devto'),
                    'topic_hint' => 'devto',
                    'engagement_score' => (int) data_get($item, 'public_reactions_count', 0) + (int) data_get($item, 'comments_count', 0),
                    'published_at' => trim((string) data_get($item, 'published_at', now()->toDateTimeString())),
                ];
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('Dev.to fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchMediumTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('medium.enabled', true)) {
            return [];
        }

        $pattern = (string) $this->blogConfig('medium.tag_feed_pattern', 'https://medium.com/feed/tag/{tag}');
        $tags = $this->mediumTagsForCategory($category);
        if (empty($tags)) {
            return [];
        }

        $topics = [];
        $perTagLimit = max(1, (int) ceil($limit / count($tags)));

        foreach ($tags as $tag) {
            $url = str_replace('{tag}', urlencode($tag), $pattern);
            $tagTopics = $this->fetchRssFromUrl($url, $tag, $category, 'medium');
            $topics = array_merge($topics, array_slice($tagTopics, 0, $perTagLimit));
        }

        return array_slice($topics, 0, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchNewsApiTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('newsapi.enabled', false)) {
            return [];
        }

        $apiKey = trim((string) $this->blogConfig('newsapi.key', ''));
        if ($apiKey === '') {
            return [];
        }

        try {
            $url = (string) $this->blogConfig('newsapi.url', 'https://newsapi.org/v2/top-headlines');
            $pageSize = min(25, max(1, (int) $this->blogConfig('newsapi.page_size', 10), $limit));
            $query = $this->keywordQueryForCategory($category);

            $payload = Cache::remember('ai_blog_newsapi_' . md5($category . '|' . $query . '|' . $pageSize), now()->addMinutes($this->cacheTtlMinutes()), function () use ($url, $apiKey, $pageSize, $category, $query) {
                $params = [
                    'apiKey' => $apiKey,
                    'language' => (string) $this->blogConfig('newsapi.language', 'en'),
                    'country' => (string) $this->blogConfig('newsapi.country', 'in'),
                    'pageSize' => $pageSize,
                ];

                if ($category === 'technology' || $category === 'digital-trends') {
                    $params['category'] = 'technology';
                }

                if ($query !== '') {
                    $params['q'] = $query;
                }

                $response = Http::timeout(20)->acceptJson()->get($url, $params);
                if (!$response->successful()) {
                    throw new \RuntimeException('NewsAPI fetch failed: ' . $response->status());
                }

                return $response->json();
            });

            $articles = data_get($payload, 'articles', []);
            if (!is_array($articles)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($articles, 0, $limit) as $article) {
                if (!is_array($article)) {
                    continue;
                }

                $title = trim((string) data_get($article, 'title', ''));
                if ($title === '') {
                    continue;
                }

                $description = trim((string) data_get($article, 'description', data_get($article, 'content', '')));
                if ($description === '') {
                    $description = 'Latest update related to ' . $category . '.';
                }

                $topics[] = [
                    'title' => $title,
                    'description' => Str::limit($description, 500),
                    'source_url' => trim((string) data_get($article, 'url', '')),
                    'source_type' => 'newsapi',
                    'category_slug' => $this->resolveCategorySlug($category, $title . ' ' . $description, 'newsapi'),
                    'topic_hint' => 'newsapi',
                    'engagement_score' => 0,
                    'published_at' => trim((string) data_get($article, 'publishedAt', now()->toDateTimeString())),
                ];
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('NewsAPI fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchStackOverflowTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('stackoverflow.enabled', true)) {
            return [];
        }

        try {
            $url = (string) $this->blogConfig('stackoverflow.url', 'https://api.stackexchange.com/2.3/questions');
            $pagesize = min(25, max(1, (int) $this->blogConfig('stackoverflow.pagesize', 10), $limit));
            $tagged = $this->stackOverflowTagsForCategory($category);

            $payload = Cache::remember('ai_blog_stackoverflow_' . md5($category . '|' . $tagged . '|' . $pagesize), now()->addMinutes($this->cacheTtlMinutes()), function () use ($url, $pagesize, $tagged) {
                $params = [
                    'order' => 'desc',
                    'sort' => 'votes',
                    'site' => (string) $this->blogConfig('stackoverflow.site', 'stackoverflow'),
                    'pagesize' => $pagesize,
                ];

                if ($tagged !== '') {
                    $params['tagged'] = $tagged;
                }

                $response = Http::timeout(20)->acceptJson()->get($url, $params);
                if (!$response->successful()) {
                    throw new \RuntimeException('StackOverflow fetch failed: ' . $response->status());
                }

                return $response->json();
            });

            $items = data_get($payload, 'items', []);
            if (!is_array($items)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($items, 0, $limit) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $title = html_entity_decode(trim((string) data_get($item, 'title', '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($title === '') {
                    continue;
                }

                $topics[] = [
                    'title' => $title,
                    'description' => 'Highly voted Stack Overflow discussion about ' . $title . '.',
                    'source_url' => trim((string) data_get($item, 'link', '')),
                    'source_type' => 'stackoverflow',
                    'category_slug' => $this->resolveCategorySlug($category, $title, 'stackoverflow'),
                    'topic_hint' => 'stackoverflow',
                    'engagement_score' => (int) data_get($item, 'score', 0) + (int) data_get($item, 'answer_count', 0),
                    'published_at' => (int) data_get($item, 'creation_date', 0) > 0
                        ? date('Y-m-d H:i:s', (int) data_get($item, 'creation_date'))
                        : now()->toDateTimeString(),
                ];
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('StackOverflow fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchGdeltTopics(string $category, int $limit): array
    {
        if (!$this->blogConfig('gdelt.enabled', true)) {
            return [];
        }

        try {
            $url = (string) $this->blogConfig('gdelt.url', 'https://api.gdeltproject.org/api/v2/doc/doc');
            $query = $this->keywordQueryForCategory($category);
            if ($query === '') {
                $query = (string) $this->blogConfig('gdelt.query', 'technology OR travel OR productivity');
            }

            $maxRecords = min(25, max(1, (int) $this->blogConfig('gdelt.maxrecords', 10), $limit));
            $payload = Cache::remember('ai_blog_gdelt_' . md5($category . '|' . $query . '|' . $maxRecords), now()->addMinutes($this->cacheTtlMinutes()), function () use ($url, $query, $maxRecords) {
                $response = Http::timeout(20)->acceptJson()->get($url, [
                    'query' => $query,
                    'mode' => (string) $this->blogConfig('gdelt.mode', 'artlist'),
                    'format' => (string) $this->blogConfig('gdelt.format', 'json'),
                    'maxrecords' => $maxRecords,
                ]);

                if (!$response->successful()) {
                    throw new \RuntimeException('GDELT fetch failed: ' . $response->status());
                }

                return $response->json();
            });

            $articles = data_get($payload, 'articles', []);
            if (!is_array($articles)) {
                return [];
            }

            $topics = [];
            foreach (array_slice($articles, 0, $limit) as $article) {
                if (!is_array($article)) {
                    continue;
                }

                $title = trim((string) data_get($article, 'title', ''));
                if ($title === '') {
                    continue;
                }

                $topics[] = [
                    'title' => $title,
                    'description' => 'GDELT trend update: ' . $title,
                    'source_url' => trim((string) data_get($article, 'url', '')),
                    'source_type' => 'gdelt',
                    'category_slug' => $this->resolveCategorySlug($category, $title, 'gdelt'),
                    'topic_hint' => 'gdelt',
                    'engagement_score' => 0,
                    'published_at' => trim((string) data_get($article, 'seendate', now()->toDateTimeString())),
                ];
            }

            return $topics;
        } catch (\Throwable $exception) {
            Log::warning('GDELT fetch exception', [
                'category' => $category,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRssFromUrl(
        string $url,
        string $topicHint,
        string $categorySlug,
        string $sourceType
    ): array {
        try {
            $xml = Cache::remember(
                'ai_blog_rss_' . md5($url . '|' . $sourceType),
                now()->addMinutes($this->cacheTtlMinutes()),
                function () use ($url) {
                    $response = Http::timeout(20)
                        ->accept('application/rss+xml, application/xml, text/xml')
                        ->get($url);

                    if (!$response->successful()) {
                        throw new \RuntimeException('RSS fetch failed: ' . $response->status());
                    }

                    return $response->body();
                }
            );

            return $this->parseFeedItems((string) $xml, $topicHint, $categorySlug, $sourceType);
        } catch (\Throwable $exception) {
            Log::warning('RSS/Trends fetch exception', [
                'source_type' => $sourceType,
                'url' => $url,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseFeedItems(string $xml, string $topicHint, string $categorySlug, string $sourceType): array
    {
        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($feed === false) {
            return [];
        }

        $nodes = [];
        if (isset($feed->channel->item)) {
            $nodes = $feed->channel->item;
        } elseif (isset($feed->entry)) {
            $nodes = $feed->entry;
        }

        if (empty($nodes)) {
            return [];
        }

        $items = [];

        foreach ($nodes as $item) {
            $title = trim((string) ($item->title ?? ''));
            $description = trim(strip_tags((string) ($item->description ?? '')));
            $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($description === '' && isset($item->summary)) {
                $description = trim(strip_tags((string) $item->summary));
            }

            if ($description === '' && isset($item->content)) {
                $description = trim(strip_tags((string) $item->content));
            }

            if ($description === '') {
                $description = "Latest {$sourceType} topic: {$title}.";
            }

            if ($title === '') {
                continue;
            }

            $link = trim((string) ($item->link ?? ''));
            if ($link === '' && isset($item->link)) {
                $attributes = $item->link->attributes();
                $link = trim((string) ($attributes['href'] ?? ''));
            }

            $items[] = [
                'title' => $title,
                'description' => Str::limit($description, 500),
                'source_url' => $link,
                'source_type' => $sourceType,
                'category_slug' => $this->resolveCategorySlug($categorySlug, $title . ' ' . $description, $sourceType),
                'topic_hint' => $topicHint,
                'engagement_score' => 0,
                'published_at' => trim((string) ($item->pubDate ?? now()->toDateTimeString())),
            ];
        }

        return $items;
    }

    /**
     * @param array<int, array<string, mixed>> $topics
     * @return array<int, array<string, mixed>>
     */
    private function mergeSimilarTopics(array $topics): array
    {
        $merged = [];

        foreach ($topics as $topic) {
            $title = trim((string) ($topic['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $mergeKey = $this->normalizeTitle($title);
            if ($mergeKey === '') {
                continue;
            }

            if (!isset($merged[$mergeKey])) {
                $topic['source_types'] = [strtolower(trim((string) ($topic['source_type'] ?? 'rss')))];
                $topic['source_urls'] = array_values(array_filter([trim((string) ($topic['source_url'] ?? ''))]));
                $topic['source_count'] = 1;
                $topic['engagement_score'] = (int) ($topic['engagement_score'] ?? 0);
                $merged[$mergeKey] = $topic;
                continue;
            }

            $existing = $merged[$mergeKey];
            $existing['description'] = $this->mergeDescriptions(
                (string) ($existing['description'] ?? ''),
                (string) ($topic['description'] ?? '')
            );

            $existingSources = is_array($existing['source_types'] ?? null) ? $existing['source_types'] : [];
            $existingSources[] = strtolower(trim((string) ($topic['source_type'] ?? 'rss')));
            $existing['source_types'] = array_values(array_unique(array_filter($existingSources)));

            $existingUrls = is_array($existing['source_urls'] ?? null) ? $existing['source_urls'] : [];
            $incomingUrl = trim((string) ($topic['source_url'] ?? ''));
            if ($incomingUrl !== '') {
                $existingUrls[] = $incomingUrl;
            }
            $existing['source_urls'] = array_values(array_unique(array_filter($existingUrls)));

            $existing['engagement_score'] = max(
                (int) ($existing['engagement_score'] ?? 0),
                (int) ($topic['engagement_score'] ?? 0)
            );

            if (empty($existing['source_url']) && $incomingUrl !== '') {
                $existing['source_url'] = $incomingUrl;
            }

            $merged[$mergeKey] = $existing;
        }

        foreach ($merged as $key => $topic) {
            $sourceTypes = is_array($topic['source_types'] ?? null) ? $topic['source_types'] : [];
            $sourceCount = count($sourceTypes);
            $topic['source_count'] = max(1, $sourceCount);
            $topic['source_type'] = $sourceCount > 1
                ? 'multi'
                : ((string) ($sourceTypes[0] ?? $topic['source_type'] ?? 'rss'));
            $topic['source_types_csv'] = implode(',', $sourceTypes);
            $merged[$key] = $topic;
        }

        return array_values($merged);
    }

    /**
     * @param array<int, array<string, mixed>> $topics
     * @return array<int, array<string, mixed>>
     */
    private function curateTopics(array $topics, int $limit): array
    {
        $seenUrls = [];
        $seenTitles = [];
        $filtered = [];
        $minDescriptionLength = max(1, (int) $this->blogConfig('min_description_length', 50));

        foreach ($topics as $topic) {
            $title = trim((string) ($topic['title'] ?? ''));
            $description = trim((string) ($topic['description'] ?? ''));
            $sourceUrl = trim((string) ($topic['source_url'] ?? ''));
            $sourceUrlKey = $this->normalizeSourceUrl($sourceUrl);
            $engagementScore = (int) ($topic['engagement_score'] ?? 0);
            $sourceType = strtolower(trim((string) ($topic['source_type'] ?? 'rss')));
            $sourceTypes = is_array($topic['source_types'] ?? null)
                ? array_map(static fn ($type) => strtolower(trim((string) $type)), $topic['source_types'])
                : [];
            $isFromTrends = $sourceType === 'trends' || in_array('trends', $sourceTypes, true);

            if ($title === '' || mb_strlen($description) < $minDescriptionLength) {
                continue;
            }

            if ($engagementScore < 50 && !$isFromTrends) {
                continue;
            }

            $keywordText = Str::lower($title . ' ' . $description);
            if (!$this->containsAllowedKeyword($keywordText)) {
                continue;
            }

            $normalizedTitle = $this->normalizeTitle($title);

            if ($sourceUrlKey !== '' && isset($seenUrls[$sourceUrlKey])) {
                continue;
            }

            if ($normalizedTitle !== '' && isset($seenTitles[$normalizedTitle])) {
                continue;
            }

            if ($sourceUrlKey !== '') {
                $seenUrls[$sourceUrlKey] = true;
            }
            if ($normalizedTitle !== '') {
                $seenTitles[$normalizedTitle] = true;
            }

            $categorySlug = $this->resolveCategorySlug(
                (string) ($topic['category_slug'] ?? ''),
                $keywordText,
                (string) ($topic['source_type'] ?? 'rss')
            );

            $topic['category_slug'] = $categorySlug;
            $scoreData = $this->calculateScore($topic);
            if (is_array($scoreData)) {
                $topic['score'] = (int) ($scoreData['score'] ?? 0);
                $topic['score_breakdown'] = $scoreData['score_breakdown'] ?? [];
            } else {
                $topic['score'] = (int) $scoreData;
            }
            $filtered[] = $topic;
        }

        usort($filtered, function (array $a, array $b): int {
            $scoreCompare = ((int) ($b['score'] ?? 0)) <=> ((int) ($a['score'] ?? 0));
            if ($scoreCompare !== 0) {
                return $scoreCompare;
            }

            return strtotime((string) ($b['published_at'] ?? 'now')) <=> strtotime((string) ($a['published_at'] ?? 'now'));
        });

        $selected = [];
        $categoryCounts = [];
        $perCategoryLimit = max(1, (int) $this->blogConfig('per_category_limit', 2));

        foreach ($filtered as $topic) {
            $categorySlug = (string) ($topic['category_slug'] ?? $this->fallbackCategorySlug());
            $count = (int) ($categoryCounts[$categorySlug] ?? 0);

            if ($count >= $perCategoryLimit) {
                continue;
            }

            $selected[] = $topic;
            $categoryCounts[$categorySlug] = $count + 1;

            if (count($selected) >= $limit) {
                break;
            }
        }

        return $selected;
    }

    private function containsAllowedKeyword(string $text): bool
    {
        $keywords = $this->blogConfig('allowed_keywords', []);
        $categoryKeywords = $this->blogConfig('category_keywords', []);

        if (is_array($categoryKeywords)) {
            foreach ($categoryKeywords as $keywordList) {
                if (!is_array($keywordList)) {
                    continue;
                }
                $keywords = array_merge($keywords, $keywordList);
            }
        }

        $keywords = array_values(array_unique(array_map(
            static fn ($keyword) => strtolower(trim((string) $keyword)),
            is_array($keywords) ? $keywords : []
        )));

        if (!is_array($keywords) || empty($keywords)) {
            return true;
        }

        foreach ($keywords as $keyword) {
            if ($keyword === '') {
                continue;
            }

            if (Str::contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $topic
     */
    /**
     * @return array{score:int, score_breakdown: array<string, int>}|int
     */
    private function calculateScore(array $topic): array|int
    {
        $breakdown = [];
        $score = TextHelper::calculateScore($topic, $breakdown);

        return [
            'score' => $score,
            'score_breakdown' => $breakdown,
        ];
    }

    private function resolveCategorySlug(string $candidate, string $text = '', string $sourceType = 'rss'): string
    {
        $allowed = $this->allowedCategorySlugs();
        $candidate = strtolower(trim($candidate));

        $text = strtolower(trim($text));
        $scores = [];

        foreach ($allowed as $slug) {
            $scores[$slug] = 0;

            if ($candidate !== '' && $candidate === $slug) {
                $scores[$slug] += 1;
            }

            $scores[$slug] += $this->categoryKeywordMatchCount($slug, $text) * 2;

            if (in_array(strtolower($sourceType), $this->sourcesForCategory($slug), true)) {
                $scores[$slug] += 1;
            }
        }

        arsort($scores);
        $best = array_key_first($scores);
        if (is_string($best) && (int) ($scores[$best] ?? 0) > 0) {
            return $best;
        }

        return $this->fallbackCategorySlug();
    }

    private function fallbackCategorySlug(): string
    {
        $fallback = strtolower(trim((string) $this->blogConfig('fallback_category_slug', 'news-updates')));
        $allowed = $this->allowedCategorySlugs();

        if (in_array($fallback, $allowed, true)) {
            return $fallback;
        }

        return $allowed[0] ?? 'news-updates';
    }

    /**
     * @return array<int, string>
     */
    private function allowedCategorySlugs(): array
    {
        $allowed = $this->blogConfig('allowed_category_slugs', []);
        if (!is_array($allowed) || empty($allowed)) {
            return ['news-updates'];
        }

        return array_values(array_map(
            static fn ($slug) => strtolower(trim((string) $slug)),
            $allowed
        ));
    }

    private function categoryKeywordMatchCount(string $categorySlug, string $text): int
    {
        $keywords = $this->blogConfig("category_keywords.{$categorySlug}", []);
        if (!is_array($keywords) || empty($keywords)) {
            return 0;
        }

        $count = 0;
        foreach ($keywords as $keyword) {
            $keyword = strtolower(trim((string) $keyword));
            if ($keyword === '') {
                continue;
            }

            if (Str::contains($text, $keyword)) {
                $count++;
            }
        }

        return $count;
    }

    private function mergeDescriptions(string $current, string $incoming): string
    {
        $current = trim($current);
        $incoming = trim($incoming);

        if ($current === '') {
            return $incoming;
        }

        if ($incoming === '' || Str::contains(Str::lower($current), Str::lower($incoming))) {
            return $current;
        }

        return Str::limit(trim($current . ' ' . $incoming), 900, '');
    }

    /**
     * @return array<int, string>
     */
    private function subredditsForCategory(string $category): array
    {
        $map = $this->blogConfig('reddit.category_map', []);
        $result = [];

        if (is_array($map)) {
            foreach ($map as $subreddit => $slug) {
                if (strtolower(trim((string) $slug)) === strtolower($category)) {
                    $result[] = strtolower(trim((string) $subreddit));
                }
            }
        }

        if (!empty($result)) {
            return array_values(array_unique($result));
        }

        return match ($category) {
            'technology' => ['technology', 'programming'],
            'digital-trends' => ['technology', 'futurology'],
            'travel' => ['travel'],
            'life-style' => ['lifestyle'],
            'productivity' => ['productivity'],
            'news-updates' => ['worldnews', 'news'],
            'stories-experiences' => ['self', 'stories'],
            'creativity-inspiration' => ['creativity', 'getmotivated'],
            default => ['technology'],
        };
    }

    private function keywordQueryForCategory(string $category): string
    {
        $keywords = $this->blogConfig("category_keywords.{$category}", []);
        if (!is_array($keywords) || empty($keywords)) {
            return '';
        }

        return implode(' OR ', array_slice(array_values(array_filter(array_map(
            static fn ($k) => trim((string) $k),
            $keywords
        ))), 0, 3));
    }

    private function devtoTagForCategory(string $category): string
    {
        return match ($category) {
            'technology' => 'ai',
            'digital-trends' => 'webdev',
            'travel' => 'travel',
            'life-style' => 'lifestyle',
            'productivity' => 'productivity',
            'stories-experiences' => 'career',
            'creativity-inspiration' => 'creativity',
            default => '',
        };
    }

    /**
     * @return array<int, string>
     */
    private function mediumTagsForCategory(string $category): array
    {
        $configured = $this->blogConfig('medium.tags', []);
        $default = match ($category) {
            'technology' => ['technology', 'ai'],
            'travel' => ['travel'],
            'life-style' => ['lifestyle', 'health'],
            'digital-trends' => ['technology', 'social-media'],
            'productivity' => ['productivity'],
            'news-updates' => ['news'],
            'stories-experiences' => ['stories'],
            'creativity-inspiration' => ['creativity', 'motivation'],
            default => ['technology'],
        };

        if (!is_array($configured) || empty($configured)) {
            return $default;
        }

        return array_values(array_unique(array_merge($default, array_map(
            static fn ($tag) => strtolower(trim((string) $tag)),
            $configured
        ))));
    }

    private function stackOverflowTagsForCategory(string $category): string
    {
        return match ($category) {
            'technology' => 'python;php;javascript',
            'digital-trends' => 'javascript;reactjs;node.js',
            'productivity' => 'git;vscode;bash',
            default => '',
        };
    }

    private function cacheTtlMinutes(): int
    {
        return max(1, (int) $this->blogConfig('cache_ttl_minutes', 20));
    }

    private function normalizeTitle(string $title): string
    {
        return (string) Str::of($title)
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->squish();
    }

    private function normalizeSourceUrl(string $url): string
    {
        return rtrim(Str::lower(trim($url)), '/');
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
