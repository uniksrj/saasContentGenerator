<?php

namespace App\Services;

use App\Models\newpost_details;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OpenAiBlogGeneratorService
{
    /**
     * @return array<int, string>
     */
    public function allowedCategorySlugs(): array
    {
        $allowed = $this->blogConfig('allowed_category_slugs', []);
        if (!is_array($allowed) || empty($allowed)) {
            return ['news-updates'];
        }

        return array_values(array_map(
            static fn($slug) => Str::lower(trim((string) $slug)),
            $allowed
        ));
    }

    /**
     * @param array<string, mixed> $topic
     * @return array<string, mixed>|null
     */
    public function generate(array $topic): ?array
    {
        $requestId = (string) Str::uuid();
        $logger = $this->aiLogger();
        $apiKey = trim((string) config('services.openai.api_key'));
        if ($apiKey === '') {
            $logger->warning('OpenAI API key missing. Skipping AI generation.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
            ]);
            return null;
        }

        $allowed = $this->allowedCategorySlugs();
        $categorySlug = $this->resolveCategorySlug(
            (string) ($topic['category_slug'] ?? ''),
            $topic,
            trim((string) (($topic['title'] ?? '') . ' ' . ($topic['description'] ?? '')))
        );
        $tone = $this->toneForCategory($categorySlug);
        $relatedLinks = $this->relatedPublishedPosts($categorySlug, (string) ($topic['title'] ?? ''), 3);

        $systemPrompt = "You are a professional SEO blog writer with strong editorial judgment. Return only valid JSON. Do not wrap in markdown."
            . " JSON keys required: title, content, meta_description, category_slug."
            . " content must be original, 800-1200 words, high-value, and valid HTML using <h1>, <h2>, <h3>, <p>, <ul>, <ol> when useful."
            . " category_slug must be exactly one from: " . implode(', ', $allowed) . ".";

        $userPrompt = [
            'topic_title' => (string) ($topic['title'] ?? ''),
            'topic_description' => (string) ($topic['description'] ?? ''),
            'topic_source_summary' => (string) ($topic['source_types_csv'] ?? ($topic['source_type'] ?? 'rss')),
            'topic_source_urls' => $topic['source_urls'] ?? [($topic['source_url'] ?? '')],
            'topic_hint' => (string) ($topic['topic_hint'] ?? 'technology'),
            'source_type' => (string) ($topic['source_type'] ?? 'rss'),
            'category_slug_hint' => $categorySlug,
            'tone' => $tone,
            'content_structure' => [
                'Engaging introduction',
                'Why it matters',
                'Key insights',
                'Real-world use cases',
                'Tips or takeaways',
                'Conclusion',
            ],
            'seo_rules' => [
                'Primary keyword in title, H1 and introduction',
                'Include 2-3 natural long-tail keyword variations',
                'Use clear H2 and H3 hierarchy',
                'Meta description between 150 and 160 characters',
                'Use title optimization with power words like Best, Ultimate, Complete, Top, Latest',
            ],
            'related_internal_links' => array_map(
                static fn(array $row): array => [
                    'url' => '/blog/' . $row['slug'],
                    'title' => $row['title'],
                ],
                $relatedLinks
            ),
            'rules' => [
                'Do not copy source text. Write original content.',
                'Use practical headings, readable paragraphs, and helpful examples.',
                'Blend multi-source insights where relevant.',
                'Write in human tone and add value beyond source summaries.',
                'If no category is certain, use news-updates.',
            ],
        ];

        try {
            $model = (string) config('services.openai.draft_model', config('services.openai.model', 'gpt-4o-mini'));
            $logger->info('Starting AI draft generation request.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'model' => $model,
                'topic_title' => (string) ($topic['title'] ?? ''),
                'source_type' => (string) ($topic['source_type'] ?? 'rss'),
                'source_url' => (string) ($topic['source_url'] ?? ''),
            ]);

            $decoded = $this->requestJsonCompletion(
                $systemPrompt,
                json_encode($userPrompt, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                $model,
                0.7,
                120,
                [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                ]
            );

            if ($decoded === null) {
                $logger->warning('OpenAI draft generation returned null payload.', [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                ]);
                return null;
            }

            $normalizedCategory = $this->resolveCategorySlug(
                (string) ($decoded['category_slug'] ?? ''),
                $topic,
                trim(((string) ($decoded['title'] ?? '')) . ' ' . ((string) ($decoded['meta_description'] ?? '')))
            );

            $generated = [
                'title' => trim((string) ($decoded['title'] ?? '')),
                'content' => trim((string) ($decoded['content'] ?? '')),
                'meta_description' => trim((string) ($decoded['meta_description'] ?? '')),
                'category_slug' => $normalizedCategory,
                'usage' => is_array($decoded['_usage'] ?? null) ? $decoded['_usage'] : [],
            ];

            if ($generated['title'] === '' || $generated['content'] === '' || $generated['meta_description'] === '') {
                $logger->warning('OpenAI payload missing required keys for draft generation.', [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                    'payload_keys' => array_keys($decoded),
                ]);
                return null;
            }

            $generated['title'] = $this->ensurePowerWordTitle($generated['title']);
            $generated['content'] = $this->injectInternalLinks(
                $generated['content'],
                $normalizedCategory,
                $generated['title'],
                $relatedLinks
            );

            $logger->info('AI draft generation completed successfully.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'generated_title' => $generated['title'],
                'category_slug' => $generated['category_slug'],
            ]);

            return $generated;
        } catch (\Throwable $exception) {
            $logger->error('OpenAI draft generation exception.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            return null;
        }
    }


    /**
     * Final publishing step: rewrite draft into high-quality SEO article.
     *
     * @return array<string, mixed>|null
     */
    public function optimizeForPublishing(string $title, string $draftContent, string $category): ?array
    {
        $requestId = (string) Str::uuid();
        $logger = $this->aiLogger();
        $apiKey = trim((string) config('services.openai.api_key'));
        if ($apiKey === '') {
            $logger->warning('OpenAI API key missing. Skipping final publish optimization.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        $resolvedCategory = $this->resolveCategorySlug($category, [
            'title' => $title,
            'description' => $draftContent,
            'topic_hint' => $category,
        ], $title . ' ' . $draftContent);
        $tone = $this->toneForCategory($resolvedCategory);
        $relatedLinks = $this->relatedPublishedPosts($resolvedCategory, $title, 3);

        $systemPrompt = 'You are an expert SEO content writer and blog strategist.';

        $userPrompt = "This is the FINAL publishing step. Transform the draft into a high-quality, human-like, SEO-optimized article that can rank on Google and provide real value.\n\n"
            . "========================================\n"
            . "INPUT\n"
            . "========================================\n"
            . "Blog Title: {$title}\n"
            . "Draft Content: {$draftContent}\n"
            . "Category: {$resolvedCategory}\n"
            . "Tone: {$tone}\n\n"
            . "========================================\n"
            . "OBJECTIVE\n"
            . "========================================\n"
            . "Rewrite and enhance the blog to:\n"
            . "- Rank on search engines\n"
            . "- Engage human readers\n"
            . "- Provide real value (not generic AI content)\n\n"
            . "========================================\n"
            . "CONTENT REQUIREMENTS\n"
            . "========================================\n"
            . "- Length: 1000-1500 words\n"
            . "- Use clean HTML formatting: <h1>, <h2>, <h3>, <p>, <ul>, <ol>\n"
            . "- Strong introduction in first 2-3 lines\n"
            . "- Keyword-rich headings\n"
            . "- Short readable paragraphs\n"
            . "- Bullet points where useful\n"
            . "- Real-world examples or practical insights\n"
            . "- Clear and satisfying conclusion\n\n"
            . "========================================\n"
            . "SEO OPTIMIZATION\n"
            . "========================================\n"
            . "- Naturally include primary keyword from title\n"
            . "- Add related keywords (LSI)\n"
            . "- Add 2-3 long-tail keyword variations\n"
            . "- Avoid keyword stuffing\n"
            . "- Optimize readability\n"
            . "- Optimize for featured snippets and People Also Ask\n\n"
            . "========================================\n"
            . "INTERNAL LINKING\n"
            . "========================================\n"
            . "Integrate up to 3 relevant internal links naturally in different sections.\n"
            . "Use meaningful anchor text and avoid raw URLs.\n"
            . "Related links JSON: " . json_encode(array_map(
                static fn(array $row): array => ['url' => '/blog/' . $row['slug'], 'title' => $row['title']],
                $relatedLinks
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\n"
            . "========================================\n"
            . "FAQ SECTION (MANDATORY)\n"
            . "========================================\n"
            . "At the end add <h2>Frequently Asked Questions</h2> and include 3-5 relevant FAQs.\n\n"
            . "========================================\n"
            . "META DESCRIPTION\n"
            . "========================================\n"
            . "Generate a compelling 150-160 character SEO-friendly meta description.\n\n"
            . "========================================\n"
            . "OUTPUT FORMAT (STRICT JSON)\n"
            . "========================================\n"
            . "Return ONLY valid JSON with keys: title, content, meta_description.\n"
            . "Do NOT output markdown.";

        $model = (string) config(
            'services.openai.publish_model',
            config('services.openai.draft_model', config('services.openai.model', 'gpt-4o-mini'))
        );

        $logger->info('Starting AI publish optimization request.', [
            'request_id' => $requestId,
            'stage' => 'publish_optimization',
            'model' => $model,
            'title' => $title,
            'category' => $category,
        ]);

        $decoded = $this->requestJsonCompletion(
            $systemPrompt,
            $userPrompt,
            $model,
            0.65,
            180,
            [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]
        );

        if ($decoded === null) {
            $logger->warning('OpenAI publish optimization returned null payload.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        $optimized = [
            'title' => trim((string) ($decoded['title'] ?? $title)),
            'content' => trim((string) ($decoded['content'] ?? '')),
            'meta_description' => trim((string) ($decoded['meta_description'] ?? '')),
            'usage' => is_array($decoded['_usage'] ?? null) ? $decoded['_usage'] : [],
        ];

        if ($optimized['content'] === '') {
            $logger->warning('Final publish optimization returned empty content.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        if (!Str::contains(Str::lower($optimized['content']), '<h1')) {
            $optimized['content'] = '<h1>' . e($optimized['title']) . '</h1>' . $optimized['content'];
        }

        $optimized['content'] = $this->ensureFaqSection($optimized['content'], $optimized['title']);

        if ($optimized['meta_description'] === '') {
            $optimized['meta_description'] = Str::limit(trim(strip_tags($optimized['content'])), 160, '');
        }

        if (Str::length($optimized['meta_description']) > 160) {
            $optimized['meta_description'] = trim(Str::limit($optimized['meta_description'], 160, ''));
        }

        $optimized['title'] = $this->ensurePowerWordTitle($optimized['title']);
        $optimized['content'] = $this->injectInternalLinks(
            $optimized['content'],
            $resolvedCategory,
            $optimized['title'],
            $relatedLinks
        );

        $logger->info('AI publish optimization completed successfully.', [
            'request_id' => $requestId,
            'stage' => 'publish_optimization',
            'optimized_title' => $optimized['title'],
        ]);

        return $optimized;
    }

    /**
     * @param array<string, mixed> $topic
     */
    public function resolveCategorySlug(string $candidate, array $topic, string $text = ''): string
    {
        $allowed = $this->allowedCategorySlugs();
        $candidate = Str::lower(trim($candidate));

        if (in_array($candidate, $allowed, true)) {
            return $candidate;
        }

        $textToAnalyze = Str::lower(trim($text . ' ' . (($topic['title'] ?? '') . ' ' . ($topic['description'] ?? ''))));

        $keywordMap = $this->blogConfig('category_keywords', [
            'technology' => [
                'ai',
                'artificial intelligence',
                'software',
                'app',
                'application',
                'coding',
                'programming',
                'automation',
                'tech',
                'development',
                'tools'
            ],
            'travel' => [
                'travel',
                'trip',
                'journey',
                'tour',
                'destination',
                'vacation',
                'tourism',
                'flight',
                'hotel',
                'guide',
                'itinerary',
                'explore'
            ],
            'life-style' => [
                'lifestyle',
                'life style',
                'health',
                'wellness',
                'habits',
                'routine',
                'daily routine',
                'self care',
                'fitness',
                'living'
            ],
            'digital-trends' => [
                'viral',
                'trend',
                'trending',
                'social media',
                'internet trends',
                'online trends',
                'digital trends',
                'reels',
                'tiktok',
                'instagram'
            ],
            'productivity' => [
                'productivity',
                'focus',
                'work',
                'efficiency',
                'time management',
                'deep work',
                'task management',
                'workflow',
                'performance'
            ],
            'news-updates' => [
                'news',
                'update',
                'latest',
                'breaking',
                'headline',
                'current events',
                'report',
                'announcement'
            ],
            'stories-experiences' => [
                'story',
                'stories',
                'journey',
                'experience',
                'real story',
                'personal experience',
                'life story',
                'case study'
            ],
            'creativity-inspiration' => [
                'creativity',
                'creative',
                'ideas',
                'inspiration',
                'motivation',
                'innovative',
                'thinking',
                'brainstorm',
                'imagination'
            ],
        ]);

        foreach ($keywordMap as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($textToAnalyze, $keyword)) {
                    return $slug;
                }
            }
        }

        $fallback = Str::lower(trim((string) $this->blogConfig('fallback_category_slug', 'news-updates')));
        if (in_array($fallback, $allowed, true)) {
            return $fallback;
        }

        return $allowed[0] ?? 'news-updates';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJson(string $raw): ?array
    {
        $clean = trim($raw);
        $clean = preg_replace('/^```json\s*/i', '', $clean) ?? $clean;
        $clean = preg_replace('/```$/', '', $clean) ?? $clean;

        $decoded = json_decode($clean, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function requestJsonCompletion(
        string $systemPrompt,
        string $userPrompt,
        string $model,
        float $temperature,
        int $timeoutSeconds,
        array $context = []
    ): ?array {
        $logger = $this->aiLogger();
        $logContext = array_merge([
            'model' => $model,
            'temperature' => $temperature,
            'timeout_seconds' => $timeoutSeconds,
        ], $context);

        try {
            $logger->info('Sending OpenAI API request.', $logContext);

            $response = Http::timeout($timeoutSeconds)
                ->withToken((string) config('services.openai.api_key'))
                ->acceptJson()
                ->post(rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/') . '/chat/completions', [
                    'model' => $model,
                    'temperature' => $temperature,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                ]);

            if (!$response->successful()) {
                $logger->warning('OpenAI request failed.', array_merge($logContext, [
                    'status' => $response->status(),
                    'body' => Str::limit(
                        $response->body(),
                        (int) $this->blogConfig('log_response_body_limit', 1200)
                    ),
                ]));
                return null;
            }

            $payload = $response->json();
            $content = data_get($payload, 'choices.0.message.content');

            if (!is_string($content) || trim($content) === '') {
                $logger->warning('OpenAI returned empty content.', array_merge($logContext, [
                    'response' => $payload,
                ]));
                return null;
            }

            $decoded = $this->decodeJson($content);
            if ($decoded === null) {
                $logger->warning('OpenAI returned invalid JSON payload.', array_merge($logContext, [
                    'content' => Str::limit(
                        $content,
                        (int) $this->blogConfig('log_response_body_limit', 1200)
                    ),
                ]));
                return null;
            }

            $logger->info('OpenAI API request succeeded.', array_merge($logContext, [
                'finish_reason' => data_get($payload, 'choices.0.finish_reason'),
                'usage' => data_get($payload, 'usage'),
            ]));

            $decoded['_usage'] = [
                'prompt_tokens' => (int) data_get($payload, 'usage.prompt_tokens', 0),
                'completion_tokens' => (int) data_get($payload, 'usage.completion_tokens', 0),
                'total_tokens' => (int) data_get($payload, 'usage.total_tokens', 0),
            ];

            return $decoded;
        } catch (\Throwable $exception) {
            $logger->error('OpenAI request exception.', array_merge($logContext, [
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]));
            return null;
        }
    }

    private function toneForCategory(string $categorySlug): string
    {
        return (string) $this->blogConfig("category_tones.{$categorySlug}", 'neutral, factual');
    }

    private function ensurePowerWordTitle(string $title): string
    {
        $title = trim($title);
        if ($title === '') {
            return $title;
        }

        $powerWords = [
            'best',
            'ultimate',
            'complete',
            'top',
            'latest',
            'proven',
            'essential',
            'smart',
            'effective',
            'easy',
            'simple',
            'quick',
            'advanced',
            'must-know',
            'insider',
            'secret',
            'hidden',
            'step-by-step',
            'real',
            'honest',
            'updated',
            'expert',
            'beginner-friendly',
            'practical'
        ];
        $lower = Str::lower($title);

        foreach ($powerWords as $powerWord) {
            if (Str::contains($lower, $powerWord)) {
                return $title;
            }
        }

        if (Str::length($title) < 55) {
            return 'Complete ' . $title;
        }

        return $title;
    }

    /**
     * @return array<int, array{title:string,slug:string}>
     */
    private function relatedPublishedPosts(string $categorySlug, string $excludeTitle, int $limit = 3): array
    {
        $limit = max(0, min(3, $limit));
        if ($limit === 0) {
            return [];
        }

        $query = newpost_details::query()
            ->published()
            ->where('category', $categorySlug)
            ->whereNotNull('slug')
            ->where('slug', '<>', '');

        $excludeTitle = trim($excludeTitle);
        if ($excludeTitle !== '') {
            $query->whereRaw('LOWER(title) <> ?', [Str::lower($excludeTitle)]);
        }

        return $query->orderByDesc('created_date')
            ->limit($limit)
            ->get(['title', 'slug'])
            ->map(fn($post): array => [
                'title' => (string) $post->title,
                'slug' => (string) $post->slug,
            ])
            ->filter(fn(array $row): bool => $row['title'] !== '' && $row['slug'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param array<int, array{title:string,slug:string}> $relatedLinks
     */
    private function injectInternalLinks(string $content, string $categorySlug, string $title, array $relatedLinks = []): string
    {
        if (trim($content) === '') {
            return $content;
        }

        if (empty($relatedLinks)) {
            $relatedLinks = $this->relatedPublishedPosts($categorySlug, $title, 3);
        }

        if (empty($relatedLinks)) {
            return $content;
        }

        $uniqueLinks = [];
        foreach ($relatedLinks as $row) {
            $slug = trim((string) ($row['slug'] ?? ''));
            $linkTitle = trim((string) ($row['title'] ?? ''));
            if ($slug === '' || $linkTitle === '') {
                continue;
            }

            $href = '/blog/' . $slug;
            if (Str::contains($content, $href) || Str::contains($content, '/posts/' . $slug)) {
                continue;
            }

            $uniqueLinks[$slug] = [
                'title' => $linkTitle,
                'slug' => $slug,
                'href' => $href,
            ];

            if (count($uniqueLinks) >= 3) {
                break;
            }
        }

        if (empty($uniqueLinks)) {
            return $content;
        }

        if (!preg_match_all('/<p\b[^>]*>.*?<\/p>/is', $content, $matches, PREG_OFFSET_CAPTURE)) {
            return $content;
        }

        $paragraphs = $matches[0];
        $paragraphCount = count($paragraphs);
        if ($paragraphCount === 0) {
            return $content;
        }

        $targetIndexes = [];
        foreach ([0.30, 0.60, 0.85] as $ratio) {
            $targetIndexes[] = min($paragraphCount - 1, max(0, (int) floor(($paragraphCount - 1) * $ratio)));
        }
        $targetIndexes = array_values(array_unique($targetIndexes));

        $replacements = [];
        $linkRows = array_values($uniqueLinks);
        $insertionCount = min(count($targetIndexes), count($linkRows));

        for ($i = 0; $i < $insertionCount; $i++) {
            $index = $targetIndexes[$i];
            $paragraphHtml = (string) $paragraphs[$index][0];
            $offset = (int) $paragraphs[$index][1];
            $link = $linkRows[$i];

            $anchor = $this->anchorTextFromTitle($link['title']);
            $sentence = ' For deeper context, see <a href="' . e($link['href']) . '">' . e($anchor) . '</a>.';
            $updatedParagraph = preg_replace('/<\/p>\s*$/i', $sentence . '</p>', $paragraphHtml, 1, $replaced);
            if (!$replaced) {
                $updatedParagraph = $paragraphHtml . $sentence;
            }

            $replacements[] = [
                'offset' => $offset,
                'length' => strlen($paragraphHtml),
                'html' => $updatedParagraph,
            ];
        }

        usort($replacements, fn(array $a, array $b): int => $b['offset'] <=> $a['offset']);

        foreach ($replacements as $replacement) {
            $content = substr_replace($content, $replacement['html'], $replacement['offset'], $replacement['length']);
        }

        return $content;
    }

    private function anchorTextFromTitle(string $title): string
    {
        $clean = trim((string) Str::of($title)
            ->replaceMatches('/[^\pL\pN\s-]/u', ' ')
            ->squish());

        if ($clean === '') {
            return 'related insights';
        }

        $parts = explode(' ', Str::lower($clean));
        $stopWords = ['the', 'a', 'an', 'and', 'of', 'to', 'for', 'in', 'on', 'with', 'how', 'what'];
        $meaningful = array_values(array_filter($parts, fn(string $word): bool => strlen($word) > 2 && !in_array($word, $stopWords, true)));

        if (!empty($meaningful)) {
            return implode(' ', array_slice($meaningful, 0, 4));
        }

        return Str::lower(Str::limit($clean, 45, ''));
    }

    private function ensureFaqSection(string $content, string $title): string
    {
        if (Str::contains(Str::lower($content), 'frequently asked questions')) {
            return $content;
        }

        $topic = trim($title) !== '' ? $title : 'this topic';

        return rtrim($content) . "\n"
            . '<h2>Frequently Asked Questions</h2>'
            . '<h3>What is the main benefit of ' . e($topic) . '?</h3>'
            . '<p>The main benefit is practical clarity: readers can make better decisions faster by understanding what works, why it works, and how to apply it correctly.</p>'
            . '<h3>How can beginners apply these ideas effectively?</h3>'
            . '<p>Start with one actionable step, measure the result, and iterate weekly. This creates momentum and avoids overwhelm.</p>'
            . '<h3>What common mistake should readers avoid?</h3>'
            . '<p>Avoid copying tactics without context. Choose strategies that fit your goals, resources, and audience.</p>';
    }

    private function aiLogger()
    {
        return Log::channel((string) $this->blogConfig('log_channel', 'ai_blog'));
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
