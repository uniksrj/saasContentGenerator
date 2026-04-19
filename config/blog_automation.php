<?php

return [
    'default_limit' => (int) env('AI_BLOG_DEFAULT_LIMIT', 5),
    'per_category_limit' => (int) env('AI_BLOG_PER_CATEGORY_LIMIT', 2),
    'min_description_length' => (int) env('AI_BLOG_MIN_DESCRIPTION_LENGTH', 50),
    'long_description_length' => (int) env('AI_BLOG_LONG_DESCRIPTION_LENGTH', 220),
    'fallback_category_slug' => (string) env('AI_BLOG_FALLBACK_CATEGORY', 'news-updates'),
    'schedule_time' => (string) env('AI_BLOG_SCHEDULE_TIME', '06:00'),
    'log_channel' => (string) env('AI_BLOG_LOG_CHANNEL', 'ai_blog'),
    'log_response_body_limit' => (int) env('AI_BLOG_LOG_RESPONSE_BODY_LIMIT', 1200),
    'cache_ttl_minutes' => (int) env('AI_BLOG_CACHE_TTL_MINUTES', 20),
    'max_dynamic_categories' => (int) env('AI_BLOG_MAX_DYNAMIC_CATEGORIES', 4),
    'max_candidates_multiplier' => (int) env('AI_BLOG_MAX_CANDIDATES_MULTIPLIER', 5),
    'high_engagement_threshold' => (int) env('AI_BLOG_HIGH_ENGAGEMENT_THRESHOLD', 100),

    'allowed_keywords' => array_values(array_filter(array_map(
        static fn (string $keyword): string => strtolower(trim($keyword)),
        explode(',', (string) env('AI_BLOG_ALLOWED_KEYWORDS', 'ai,tech,travel,productivity,guide,tips,trends'))
    ))),

    'score_weights' => [
        'trends' => (int) env('AI_BLOG_SCORE_TRENDS', 3),
        'reddit' => (int) env('AI_BLOG_SCORE_REDDIT', 2),
        'rss' => (int) env('AI_BLOG_SCORE_RSS', 1),
    ],

    'scoring_bonus_keywords' => array_values(array_filter(array_map(
        static fn (string $keyword): string => strtolower(trim($keyword)),
        explode(',', (string) env('AI_BLOG_BONUS_KEYWORDS', 'ai,2026'))
    ))),
    'scoring_bonus_points' => (int) env('AI_BLOG_SCORE_BONUS_KEYWORDS', 2),
    'scoring_long_description_points' => (int) env('AI_BLOG_SCORE_LONG_DESCRIPTION', 1),
    'scoring_category_keyword_points' => (int) env('AI_BLOG_SCORE_CATEGORY_KEYWORDS', 2),
    'scoring_multi_source_points' => (int) env('AI_BLOG_SCORE_MULTI_SOURCE', 3),
    'scoring_high_engagement_points' => (int) env('AI_BLOG_SCORE_HIGH_ENGAGEMENT', 1),

    'category_keywords' => [
        'technology' => [
            'artificial intelligence tools',
            'latest software trends',
            'app development guide',
            'coding best practices',
            'automation tools for business'
        ],
        'travel' => [
            'travel guide',
            'best places to visit',
            'budget travel tips',
            'international travel rules',
            'destination recommendations'
        ],
        'life-style' => [
            'healthy lifestyle habits',
            'daily routine tips',
            'self improvement ideas',
            'work life balance',
            'wellness and health tips'
        ],
        'digital-trends' => [
            'latest internet trends',
            'viral social media content',
            'digital marketing trends',
            'online trends analysis',
            'social media growth tips'
        ],
        'productivity' => [
            'time management techniques',
            'how to stay focused',
            'increase work efficiency',
            'productivity tools',
            'daily productivity hacks'
        ],
        'news-updates' => [
            'latest news updates',
            'breaking news today',
            'current events analysis',
            'global news trends',
            'industry news insights'
        ],
        'stories-experiences' => [
            'real life story',
            'personal journey experience',
            'life lessons learned',
            'inspiring real stories',
            'travel experiences story'
        ],
        'creativity-inspiration' => [
            'creative ideas inspiration',
            'how to stay creative',
            'motivation for success',
            'inspiration for work',
            'creative thinking techniques'
        ],
    ],

    'category_tones' => [
    'technology' => 'expert, analytical, forward-thinking',
    'travel' => 'descriptive, immersive, engaging',
    'life-style' => 'conversational, relatable, practical',
    'digital-trends' => 'insightful, analytical, trend-focused',
    'productivity' => 'actionable, structured, result-oriented',
    'news-updates' => 'neutral, factual, concise',
    'stories-experiences' => 'storytelling, emotional, personal',
    'creativity-inspiration' => 'motivational, uplifting, thought-provoking',
    ],

    'category_source_map' => [
        'technology' => ['rss', 'reddit', 'hackernews', 'github', 'devto', 'newsapi'],
        'digital-trends' => ['rss', 'reddit', 'hackernews', 'github', 'devto', 'newsapi'],
        'travel' => ['rss', 'reddit', 'newsapi'],
        'life-style' => ['rss', 'reddit', 'medium', 'newsapi'],
        'productivity' => ['rss', 'reddit', 'devto', 'medium', 'stackoverflow'],
        'news-updates' => ['rss', 'trends', 'newsapi', 'gdelt'],
        'stories-experiences' => ['rss', 'reddit', 'medium'],
        'creativity-inspiration' => ['rss', 'reddit', 'medium', 'devto'],
    ],

    'trends' => [
        'enabled' => env('AI_BLOG_TRENDS_ENABLED', true),
        'url' => (string) env(
            'AI_BLOG_TRENDS_URL',
            'https://trends.google.com/trends/trendingsearches/daily/rss?geo=IN'
        ),
        'category' => (string) env('AI_BLOG_TRENDS_CATEGORY', 'news-updates'),
    ],

    'reddit' => [
        'enabled' => env('AI_BLOG_REDDIT_ENABLED', true),
        'user_agent' => (string) env('AI_BLOG_REDDIT_USER_AGENT', 'myblog-bot/1.0 (+https://example.com)'),
        'subreddits' => array_values(array_filter(array_map(
            static fn (string $subreddit): string => strtolower(trim($subreddit)),
            explode(',', (string) env('AI_BLOG_REDDIT_SUBREDDITS', 'technology,travel,productivity'))
        ))),
        'limit_per_subreddit' => (int) env('AI_BLOG_REDDIT_LIMIT_PER_SUBREDDIT', 8),
        'category_map' => [
            'technology' => 'technology',
            'travel' => 'travel',
            'productivity' => 'productivity',
        ],
    ],

    'hackernews' => [
        'enabled' => env('AI_BLOG_HN_ENABLED', true),
        'top_stories_url' => (string) env('AI_BLOG_HN_TOP_URL', 'https://hacker-news.firebaseio.com/v0/topstories.json'),
        'item_url_pattern' => (string) env('AI_BLOG_HN_ITEM_URL_PATTERN', 'https://hacker-news.firebaseio.com/v0/item/{id}.json'),
        'limit' => (int) env('AI_BLOG_HN_LIMIT', 10),
    ],

    'github' => [
        'enabled' => env('AI_BLOG_GITHUB_ENABLED', true),
        'search_url' => (string) env('AI_BLOG_GITHUB_SEARCH_URL', 'https://api.github.com/search/repositories'),
        'query' => (string) env('AI_BLOG_GITHUB_QUERY', 'stars:>1000'),
        'sort' => (string) env('AI_BLOG_GITHUB_SORT', 'stars'),
        'order' => (string) env('AI_BLOG_GITHUB_ORDER', 'desc'),
        'per_page' => (int) env('AI_BLOG_GITHUB_PER_PAGE', 10),
    ],

    'devto' => [
        'enabled' => env('AI_BLOG_DEVTO_ENABLED', true),
        'url' => (string) env('AI_BLOG_DEVTO_URL', 'https://dev.to/api/articles'),
        'per_page' => (int) env('AI_BLOG_DEVTO_PER_PAGE', 10),
    ],

    'medium' => [
        'enabled' => env('AI_BLOG_MEDIUM_ENABLED', true),
        'tag_feed_pattern' => (string) env('AI_BLOG_MEDIUM_TAG_FEED_PATTERN', 'https://medium.com/feed/tag/{tag}'),
        'tags' => array_values(array_filter(array_map(
            static fn (string $tag): string => strtolower(trim($tag)),
            explode(',', (string) env('AI_BLOG_MEDIUM_TAGS', 'technology,travel,lifestyle,productivity,creativity'))
        ))),
    ],

    'newsapi' => [
        'enabled' => env('AI_BLOG_NEWSAPI_ENABLED', false),
        'key' => (string) env('NEWSAPI_KEY', ''),
        'url' => (string) env('AI_BLOG_NEWSAPI_URL', 'https://newsapi.org/v2/top-headlines'),
        'language' => (string) env('AI_BLOG_NEWSAPI_LANGUAGE', 'en'),
        'country' => (string) env('AI_BLOG_NEWSAPI_COUNTRY', 'us'),
        'page_size' => (int) env('AI_BLOG_NEWSAPI_PAGE_SIZE', 10),
    ],

    'stackoverflow' => [
        'enabled' => env('AI_BLOG_STACKOVERFLOW_ENABLED', true),
        'url' => (string) env('AI_BLOG_STACKOVERFLOW_URL', 'https://api.stackexchange.com/2.3/questions'),
        'site' => (string) env('AI_BLOG_STACKOVERFLOW_SITE', 'stackoverflow'),
        'pagesize' => (int) env('AI_BLOG_STACKOVERFLOW_PAGESIZE', 10),
    ],

    'gdelt' => [
        'enabled' => env('AI_BLOG_GDELT_ENABLED', true),
        'url' => (string) env('AI_BLOG_GDELT_URL', 'https://api.gdeltproject.org/api/v2/doc/doc'),
        'query' => (string) env('AI_BLOG_GDELT_QUERY', 'technology OR travel OR productivity'),
        'mode' => (string) env('AI_BLOG_GDELT_MODE', 'artlist'),
        'format' => (string) env('AI_BLOG_GDELT_FORMAT', 'json'),
        'maxrecords' => (int) env('AI_BLOG_GDELT_MAXRECORDS', 10),
    ],

    'feeds' => [

        [
            'topic' => 'technology',
            'category' => 'technology',
            'url' => env('AI_BLOG_TECH_FEED'),
        ],

        [
            'topic' => 'travel',
            'category' => 'travel',
            'url' => env('AI_BLOG_TRAVEL_FEED'),
        ],

        [
            'topic' => 'productivity',
            'category' => 'productivity',
            'url' => 'https://news.google.com/rss/search?q=productivity&hl=en-IN&gl=IN&ceid=IN:en',
        ],

        [
            'topic' => 'lifestyle',
            'category' => 'life-style',
            'url' => 'https://news.google.com/rss/search?q=lifestyle&hl=en-IN&gl=IN&ceid=IN:en',
        ],

        [
            'topic' => 'digital trends',
            'category' => 'digital-trends',
            'url' => 'https://news.google.com/rss/search?q=digital+trends&hl=en-IN&gl=IN&ceid=IN:en',
        ],

        [
            'topic' => 'news',
            'category' => 'news-updates',
            'url' => 'https://news.google.com/rss?hl=en-IN&gl=IN&ceid=IN:en',
        ],

        [
            'topic' => 'inspiration',
            'category' => 'creativity-inspiration',
            'url' => 'https://news.google.com/rss/search?q=inspiration&hl=en-IN&gl=IN&ceid=IN:en',
        ],

        [
            'topic' => 'stories',
            'category' => 'stories-experiences',
            'url' => 'https://news.google.com/rss/search?q=life+stories&hl=en-IN&gl=IN&ceid=IN:en',
        ],
    ],

    'allowed_category_slugs' => [
        'technology',
        'travel',
        'life-style',
        'digital-trends',
        'productivity',
        'news-updates',
        'stories-experiences',
        'creativity-inspiration',
    ],
];
