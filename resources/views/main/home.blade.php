@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $siteName = 'Unik Muse';
    $metaTitle = $query
        ? 'Search results for "' . $query . '" | ' . $siteName
        : $siteName . ' | Fresh Stories on Tech, Travel, Lifestyle and Creativity';

    $metaDescription = $query
        ? 'Browse curated blog posts matching "' . $query . '" on ' . $siteName . '.'
        : 'Discover practical guides, deep insights, and inspiring stories across technology, travel, lifestyle, digital trends, and productivity.';

    $canonicalUrl = url()->current();
    if (!empty($query)) {
        $canonicalUrl .= '?q=' . urlencode($query);
    }

    $fallbackImage = asset('assets/snow.webp');

    $resolveImage = function ($post, string $size = 'medium') use ($fallbackImage) {
        if (empty($post) || empty($post->file_path)) {
            return $fallbackImage;
        }

        $images = is_array($post->file_path)
            ? $post->file_path
            : json_decode((string) $post->file_path, true);

        if (!is_array($images)) {
            return $fallbackImage;
        }

        if (!empty($images[$size]['webp'])) {
            return asset('storage/' . ltrim($images[$size]['webp'], '/'));
        }

        foreach (['large', 'medium', 'thumb', 'original'] as $candidate) {
            if (!empty($images[$candidate]['webp'])) {
                return asset('storage/' . ltrim($images[$candidate]['webp'], '/'));
            }
            if (!empty($images[$candidate]['jpeg'])) {
                return asset('storage/' . ltrim($images[$candidate]['jpeg'], '/'));
            }
        }

        if (!empty($images[0]) && is_string($images[0])) {
            return asset('storage/' . ltrim($images[0], '/'));
        }

        return $fallbackImage;
    };

    $estimateReadTime = function (?string $content) {
        $words = str_word_count(strip_tags((string) $content));
        return max(1, (int) ceil($words / 200));
    };

    $formatCategory = function (?string $slug) {
        return Str::of((string) $slug)->replace('-', ' ')->title();
    };

    $categoryPalette = [
        'technology' => 'from-blue-500/20 to-cyan-500/20 border-blue-500/30 text-blue-700',
        'travel' => 'from-emerald-500/20 to-teal-500/20 border-emerald-500/30 text-emerald-700',
        'life-style' => 'from-amber-500/20 to-orange-500/20 border-amber-500/30 text-amber-700',
        'digital-trends' => 'from-indigo-500/20 to-violet-500/20 border-indigo-500/30 text-indigo-700',
        'productivity' => 'from-rose-500/20 to-pink-500/20 border-rose-500/30 text-rose-700',
        'tutorials' => 'from-sky-500/20 to-blue-500/20 border-sky-500/30 text-sky-700',
        'news-updates' => 'from-red-500/20 to-rose-500/20 border-red-500/30 text-red-700',
        'stories-experiences' => 'from-purple-500/20 to-fuchsia-500/20 border-purple-500/30 text-purple-700',
        'creativity-inspiration' => 'from-yellow-500/20 to-amber-500/20 border-yellow-500/30 text-yellow-700',
    ];

    $latestForSchema = $latestPosts->take(5)->values()->map(function ($post, $index) {
        $postUrl = route('post.show', ['slugOrId' => $post->slug ?: $post->id]);
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => $postUrl,
            'name' => $post->title,
        ];
    })->all();

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => url('/'),
        'description' => $metaDescription,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $itemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Latest Blog Posts',
        'itemListElement' => $latestForSchema,
    ];
@endphp
<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $featuredPost ? $resolveImage($featuredPost, 'large') : $fallbackImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $featuredPost ? $resolveImage($featuredPost, 'large') : $fallbackImage }}">

    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if (!empty($latestForSchema))
        <script type="application/ld+json">{!! json_encode($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')

    <main>
        <section class="relative overflow-hidden bg-slate-950 text-white">
            <div class="absolute -top-16 -left-16 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="absolute top-10 right-0 h-80 w-80 rounded-full bg-pink-500/20 blur-3xl"></div>
            <div class="container-unik relative py-14 md:py-20">
                <div class="grid gap-10 lg:grid-cols-5 lg:items-center">
                    <div class="lg:col-span-3">
                        <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs uppercase tracking-[0.16em] text-white">
                            Professional Content Hub
                        </p>
                        <h1 class="mb-5 text-4xl font-bold leading-tight md:text-5xl lg:text-6xl">
                            Stories and Insights That Build Better Decisions
                        </h1>
                        <p class="max-w-2xl text-base text-slate-200 md:text-lg">
                            Explore high-quality articles on technology, travel, lifestyle, productivity, and digital trends.
                            Every post is crafted to be useful, readable, and search-friendly.
                        </p>

                        @if (!empty($query))
                            <div class="mt-6 inline-flex flex-wrap items-center gap-3 rounded-unik-md border border-white/20 bg-white/10 px-4 py-3 text-sm">
                                <span>Showing results for:</span>
                                <strong class="text-cyan-200">{{ $query }}</strong>
                                <a href="{{ route('home') }}" class="rounded-md border border-white/30 px-3 py-1 text-xs uppercase tracking-wide hover:bg-white/10">
                                    Clear Search
                                </a>
                            </div>
                        @endif

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('blog.index') }}" class="rounded-unik-md bg-cyan-400 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-cyan-300">
                                Read All Articles
                            </a>
                            <a href="{{ route('categories.index') }}" class="rounded-unik-md border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Browse Categories
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        @if ($featuredPost)
                            <article class="overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur-sm" itemscope itemtype="https://schema.org/BlogPosting">
                                <img src="{{ $resolveImage($featuredPost, 'large') }}" alt="{{ $featuredPost->title }}" class="h-52 w-full object-cover" loading="eager" itemprop="image">
                                <div class="p-5">
                                    <p class="mb-2 text-xs uppercase tracking-[0.16em] text-cyan-200">
                                        Featured Story
                                    </p>
                                    <h2 class="mb-2 text-2xl font-semibold leading-snug text-white" itemprop="headline">
                                        {{ Str::limit($featuredPost->title, 78) }}
                                    </h2>
                                    <p class="mb-4 text-sm text-slate-200" itemprop="description">
                                        {{ Str::limit(strip_tags($featuredPost->description), 130) }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-slate-300">
                                        <span>{{ $estimateReadTime($featuredPost->description) }} min read</span>
                                        <span>{{ Carbon::parse($featuredPost->created_date ?? $featuredPost->created_at)->format('M d, Y') }}</span>
                                    </div>
                                    <a href="{{ route('post.show', ['slugOrId' => $featuredPost->slug ?: $featuredPost->id]) }}" class="mt-5 inline-flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-100" itemprop="mainEntityOfPage">
                                        Read Featured Story <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                    <meta itemprop="datePublished" content="{{ Carbon::parse($featuredPost->created_date ?? $featuredPost->created_at)->toIso8601String() }}">
                                </div>
                            </article>
                        @else
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-sm text-slate-300">
                                Featured content will appear here once your first post is published.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="container-unik py-12 md:py-16">
            <div class="mb-8 flex items-center justify-between gap-4">
                <h2 class="mb-0 text-2xl font-bold text-slate-900 md:text-3xl">Top Stories</h2>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-unik-primary">View all posts</a>
            </div>

            @if ($topStories->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($topStories as $post)
                        <article class="group overflow-hidden rounded-2xl border border-unik-border bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg" itemscope itemtype="https://schema.org/BlogPosting">
                            <div class="overflow-hidden">
                                <img src="{{ $resolveImage($post) }}" alt="{{ $post->title }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" itemprop="image">
                            </div>
                            <div class="p-5">
                                <div class="mb-3 flex items-center justify-between text-xs text-unik-muted">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $formatCategory($post->category) }}</span>
                                    <span>{{ Carbon::parse($post->created_date ?? $post->created_at)->diffForHumans() }}</span>
                                </div>
                                <h3 class="mb-3 text-xl font-semibold leading-snug text-slate-900 line-clamp-2" itemprop="headline">
                                    {{ $post->title }}
                                </h3>
                                <p class="mb-4 text-sm text-slate-600 line-clamp-3" itemprop="description">
                                    {{ Str::limit(strip_tags($post->description), 120) }}
                                </p>
                                <a href="{{ route('post.show', ['slugOrId' => $post->slug ?: $post->id]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-unik-primary" itemprop="mainEntityOfPage">
                                    Read article <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-unik-border bg-white p-10 text-center text-unik-muted">
                    No stories found yet. Publish posts from your admin panel and they will appear here.
                </div>
            @endif
        </section>

        <section class="bg-white/70 py-12 md:py-14">
            <div class="container-unik">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <h2 class="mb-1 text-2xl font-bold text-slate-900 md:text-3xl">Trending Categories</h2>
                        <p class="mb-0 text-sm text-slate-600">Clear navigation for readers and better topical SEO.</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-unik-primary">See all categories</a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($categoryStats as $category)
                        @php
                            $slug = $category->category;
                            $colorClasses = $categoryPalette[$slug] ?? 'from-slate-500/10 to-slate-600/10 border-slate-300 text-slate-700';
                        @endphp
                        <a href="{{ route('blog.index', ['category' => $slug]) }}" class="rounded-xl border bg-gradient-to-br p-5 transition hover:-translate-y-1 hover:shadow-md {{ $colorClasses }}">
                            <p class="mb-2 text-sm font-semibold uppercase tracking-wide">{{ $formatCategory($slug) }}</p>
                            <p class="mb-0 text-3xl font-bold">{{ $category->total }}</p>
                            <p class="mb-0 mt-1 text-xs">Published posts</p>
                        </a>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-unik-border bg-white p-8 text-center text-slate-500">
                            Category analytics will appear when posts are added.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="container-unik py-12 md:py-16">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="mb-6 text-2xl font-bold text-slate-900 md:text-3xl">Latest Articles</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        @forelse ($latestPosts as $post)
                            <article class="overflow-hidden rounded-2xl border border-unik-border bg-white shadow-sm" itemscope itemtype="https://schema.org/BlogPosting">
                                <img src="{{ $resolveImage($post) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover" loading="lazy" itemprop="image">
                                <div class="p-5">
                                    <div class="mb-3 flex items-center justify-between text-xs text-unik-muted">
                                        <span>{{ Carbon::parse($post->created_date ?? $post->created_at)->format('M d, Y') }}</span>
                                        <span>{{ $estimateReadTime($post->description) }} min read</span>
                                    </div>
                                    <h3 class="mb-3 text-xl font-semibold text-slate-900 line-clamp-2" itemprop="headline">{{ $post->title }}</h3>
                                    <p class="mb-4 text-sm text-slate-600 line-clamp-3" itemprop="description">{{ Str::limit(strip_tags($post->description), 130) }}</p>
                                    <a href="{{ route('post.show', ['slugOrId' => $post->slug ?: $post->id]) }}" class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800" itemprop="mainEntityOfPage">
                                        Continue reading
                                    </a>
                                    <meta itemprop="datePublished" content="{{ Carbon::parse($post->created_date ?? $post->created_at)->toIso8601String() }}">
                                </div>
                            </article>
                        @empty
                            <div class="md:col-span-2 rounded-xl border border-dashed border-unik-border bg-white p-10 text-center text-unik-muted">
                                No articles match this search. Try another keyword.
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-2xl border border-unik-border bg-slate-900 p-6 text-white">
                        <p class="mb-2 text-xs uppercase tracking-[0.16em] text-cyan-300">Newsletter</p>
                        <h3 class="mb-2 text-2xl font-semibold">Grow With Better Ideas</h3>
                        <p class="mb-4 text-sm text-slate-300">Get curated writing and practical tips in your inbox.</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-md bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-cyan-300">
                            Subscribe Now
                        </a>
                    </div>

                    <div class="rounded-2xl border border-unik-border bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-xl font-semibold text-slate-900">Most Read</h3>
                        <div class="space-y-4">
                            @forelse ($popularPosts as $index => $post)
                                <a href="{{ route('post.show', ['slugOrId' => $post->slug ?: $post->id]) }}" class="group flex items-start gap-3 rounded-lg p-2 transition hover:bg-slate-50">
                                    <span class="mt-1 inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">{{ $index + 1 }}</span>
                                    <span class="text-sm font-medium leading-6 text-slate-700 group-hover:text-slate-900">{{ Str::limit($post->title, 72) }}</span>
                                </a>
                            @empty
                                <p class="mb-0 text-sm text-slate-500">Popular posts will appear once traffic starts growing.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-unik-border bg-white p-6 shadow-sm">
                        <h3 class="mb-3 text-xl font-semibold text-slate-900">Why This Homepage Works</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>Clear headline and hierarchy for users and search engines.</li>
                            <li>Structured metadata and semantic article markup.</li>
                            <li>Fast-loading image blocks and readable content cards.</li>
                            <li>Strong internal links to categories and article pages.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    @include('common.footer')
</body>

</html>
