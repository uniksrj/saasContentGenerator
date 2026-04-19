<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryName ?? 'Category' }} - Latest Articles & Insights | Unik Muse</title>
    <meta name="description"
        content="Explore comprehensive {{ $categoryName ?? '' }} content including guides, tutorials, news, and expert insights. {{ $posts->total() ?? 0 }}+ curated articles.">
    <meta name="keywords" content="{{ $categoryName ?? '' }}, articles, guides, tutorials, news, insights">
    <meta property="og:title" content="{{ $categoryName ?? 'Category' }} - Expert Insights & Articles | Unik Muse">
    <meta property="og:description"
        content="Dive into our collection of {{ $posts->total() ?? 0 }}+ {{ $categoryName ?? '' }} articles curated for enthusiasts and professionals.">
    <meta property="og:image"
        content="{{ asset('storage/' . (optional($posts->first())->image ?? 'assets/category-banner.jpg')) }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Structured Data for SEO -->
    @if ($posts->isNotEmpty())
        <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $categoryName }} Articles",
        "description": "Comprehensive collection of {{ $categoryName }} articles and resources",
        "publisher": {
            "@type": "Organization",
            "name": "Unik Muse",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('assets/unikmusewhite.webp') }}"
            }
        },
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $posts->total() }},
            "itemListElement": [
                @foreach($posts->take(5) as $index => $post)
                {
                    "@type": "ListItem",
                    "position": {{ $index + 1 }},
                    "item": {
                        "@type": "Article",
                        "name": "{{ $post->title }}",
                        "url": "{{ route('post.show', $post->id) }}",
                        "description": "{{ Str::limit($post->excerpt ?? $post->description, 150) }}"
                    }
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]
        }
    }
    </script>
    @endif

    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "{{ $categoryName }} Articles",
  "url": "{{ url()->current() }}"
}
</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
    <style>
        @font-face {
            font-display: swap !important;
        }

        .category-gradient {
            background: linear-gradient(135deg, var(--color-unik-primary) 0%, var(--color-unik-secondary) 100%);
        }

        .sticky-sidebar {
            position: sticky;
            top: 100px;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-bar {
            height: 4px;
            background: linear-gradient(90deg, var(--color-unik-primary), var(--color-unik-secondary));
            width: 0%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body class="bg-unik-light text-unik-dark">
    <!-- Reading Progress Bar -->
    <div class="progress-bar" id="readingProgress"></div>

    @include('common.header')
    @php
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    @endphp
    <main class="container-unik py-8 md:py-12">
        <!-- Category Hero Section -->
        <section class="mb-12 fade-in">
            <div class="category-gradient rounded-unik-xl text-white p-8 md:p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-32 translate-x-32">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-24 -translate-x-24">
                    </div>
                </div>

                <div class="relative z-10 text-center">
                    <div class="inline-flex items-center gap-3 mb-6">
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-folder-open mr-2"></i> Category
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-newspaper mr-2"></i> {{ $posts->total() ?? 0 }}+ Articles
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">
                        {{ $categoryName ?? 'Category' }}
                    </h1>

                    <p class="text-xl opacity-90 max-w-3xl mx-auto mb-8">
                        Your comprehensive resource for {{ strtolower($categoryName ?? '') }} insights, tutorials,
                        and industry updates
                    </p>

                    <div class="flex flex-wrap justify-center gap-4 mb-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $posts->total() ?? 0 }}</div>
                            <div class="text-sm opacity-80">Total Articles</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">
                                {{ $posts->where('created_at', '>=', now()->subDays(30))->count() ?? 0 }}
                            </div>
                            <div class="text-sm opacity-80">Last 30 Days</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">
                                @php
                                    $mostViewed = optional($posts->sortByDesc('views')->first());
                                @endphp
                                {{ $mostViewed->views ?? 0 }}
                            </div>
                            <div class="text-sm opacity-80">Most Viewed</div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Featured Article (If available) -->
                @php
                    $featuredPost = optional($posts->where('is_featured', true)->first());
                @endphp
                @if ($featuredPost && $featuredPost->id)
                    @php
                        $imagepath = json_decode($featuredPost->file_path, true);
                        $img = !empty($imagepath) ? $imagepath['original']['webp'] : 'uploads/no_image.jpg';
                    @endphp
                    <section class="bg-white rounded-unik-xl shadow-unik-lg overflow-hidden fade-in">
                        <div class="md:flex">
                            <div class="md:w-1/2 relative">
                                <img src="{{ asset('storage/' . ($img ?? 'assets/featured.jpg')) }}"
                                    alt="{{ $featuredPost->title ?? 'Featured Post' }}"
                                    class="w-full h-64 md:h-full object-cover" loading="lazy">
                                <div class="absolute top-4 left-4">
                                    <span class="bg-unik-primary text-white px-3 py-1 rounded-full text-xs font-bold">
                                        <i class="fas fa-star mr-1"></i> Featured
                                    </span>
                                </div>
                            </div>
                            <div class="md:w-1/2 p-6 md:p-8">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="px-3 py-1 bg-unik-primary/10 text-unik-primary rounded-full text-xs font-medium">
                                        {{ $categoryName ?? '' }}
                                    </span>
                                    <span class="text-unik-muted text-sm">
                                        {{ $featuredPost->read_time ?? '5' }} min read
                                    </span>
                                </div>
                                <h2 class="text-2xl font-bold text-unik-dark mb-4">
                                    <a href="{{ route('post.show', $featuredPost->id) }}"
                                        class="hover:text-unik-primary transition-colors">
                                        {{ $featuredPost->title ?? '' }}
                                    </a>
                                </h2>

                                <p class="text-unik-muted mb-6 leading-relaxed">
                                    {{ Str::limit(strip_tags($parsedown->text($featuredPost->description ?? '')), 200) }}

                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-unik-primary/10 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-unik-primary"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-sm">
                                                {{ $featuredPost->author_name ?? 'Admin' }}</div>
                                            <div class="text-xs text-unik-muted">
                                                {{ optional($featuredPost->created_at)->format('F j, Y') ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('post.show', $featuredPost->id) }}"
                                        class="bg-unik-primary text-white px-6 py-3 rounded-lg hover:bg-unik-primary/90 transition-colors font-medium">
                                        Read Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                <!-- Ad Banner -->
                <div
                    class="bg-gradient-to-r from-unik-light to-white rounded-unik-lg p-6 border border-unik-border text-center">
                    <div class="inline-flex items-center gap-2 mb-3">
                        <i class="fas fa-ad text-unik-muted"></i>
                        <span class="text-sm text-unik-muted font-medium">Sponsored</span>
                    </div>
                    <p class="text-unik-dark mb-4">Elevate your {{ strtolower($categoryName ?? '') }} skills with our
                        premium
                        resources</p>
                    <a href="#"
                        class="inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary">
                        Explore Premium Content <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Category Articles Grid -->
                <section>
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-unik-dark">
                            Latest {{ $categoryName ?? '' }} Articles
                            <span class="text-lg text-unik-muted font-normal ml-2">
                                ({{ $posts->total() ?? 0 }} results)
                            </span>
                        </h2>
                        @if ($posts->isNotEmpty())
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-unik-muted">Sort by:</span>
                                <select
                                    class="bg-white border border-unik-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-unik-primary"
                                    onchange="window.location.href = this.value">
                                    <option value="{{ url()->current() }}?sort=latest">Latest</option>
                                    <option value="{{ url()->current() }}?sort=popular">Most Popular</option>
                                    <option value="{{ url()->current() }}?sort=trending">Trending</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    @if ($posts->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in">
                            @foreach ($posts as $post)
                                @php
                                    $imagepath = $post->file_path ? json_decode($post->file_path, true) : null;
                                    $img = !empty($imagepath)
                                        ? $imagepath['original']['webp'] ??
                                            ($imagepath['original']['jpg'] ?? 'uploads/no_image.jpg')
                                        : 'uploads/no_image.jpg';
                                @endphp
                                <article
                                    class="bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 group">
                                    <div class="relative overflow-hidden rounded-t-unik-lg">
                                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $post->title ?? '' }}"
                                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span
                                                class="px-3 py-1 bg-unik-primary/10 text-unik-primary rounded-full text-xs font-medium">
                                                {{ $categoryName ?? '' }}
                                            </span>
                                            <span class="text-unik-muted text-xs">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($post->created_date ?? now())->diffForHumans() }}
                                            </span>
                                        </div>

                                        <h3
                                            class="text-xl font-bold text-unik-dark mb-3 group-hover:text-unik-primary transition-colors">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                {{ $post->title ?? '' }}
                                            </a>
                                        </h3>

                                        <p class="text-unik-muted mb-4 text-sm leading-relaxed">
                                            {{ Str::limit(strip_tags($parsedown->text($post->description ?? '')), 200) }}
                                        </p>

                                        <div
                                            class="flex items-center justify-between pt-4 border-t border-unik-border">
                                            <div class="flex items-center gap-4 text-xs text-unik-muted">
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-eye"></i>
                                                    {{ $post->views ?? rand(100, 5000) }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-comment"></i>
                                                    {{ $post->comments_count ?? rand(0, 50) }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-bookmark"></i>
                                                    {{ round(str_word_count($post->description ?? '') / 200) }} min
                                                    read
                                                </span>
                                            </div>
                                            <a href="{{ route('post.show', $post->id) }}"
                                                class="text-unik-primary font-medium hover:text-unik-secondary text-sm">
                                                Read Full <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-unik-lg">
                            <div class="w-20 h-20 mx-auto mb-6 text-unik-muted">
                                <i class="fas fa-newspaper text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-unik-dark mb-3">No Articles Yet</h3>
                            <p class="text-unik-muted max-w-md mx-auto mb-6">
                                We're currently working on new {{ strtolower($categoryName ?? '') }} content.
                                Check back soon or explore other categories.
                            </p>
                            <a href="/categories"
                                class="inline-flex items-center bg-unik-primary text-white px-6 py-3 rounded-lg hover:bg-unik-primary/90 transition-colors">
                                <i class="fas fa-compass mr-2"></i> Browse All Categories
                            </a>
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if ($posts->hasPages())
                        <div class="mt-12">
                            {{ $posts->onEachSide(1)->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </section>

                <!-- Newsletter Section -->
                <section
                    class="bg-gradient-to-r from-unik-primary to-unik-secondary rounded-unik-xl p-8 text-white text-center">
                    <div class="max-w-lg mx-auto">
                        <i class="fas fa-envelope-open-text text-4xl mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3">Stay Updated on {{ $categoryName ?? '' }}</h3>
                        <p class="opacity-90 mb-6">
                            Get the latest {{ strtolower($categoryName ?? '') }} articles, tips, and insights
                            delivered directly to your inbox
                        </p>
                        <form class="flex flex-col sm:flex-row gap-3">
                            <input type="email" placeholder="Your email address"
                                class="flex-grow px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                            <button type="submit"
                                class="px-6 py-3 bg-white text-unik-primary font-bold rounded-lg hover:bg-gray-100 transition-colors">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-sm opacity-75 mt-4">No spam. Unsubscribe at any time.</p>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-6 sticky-sidebar">
                <!-- Category Stats -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-unik-primary"></i> Category Stats
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-unik-muted">Total Articles</span>
                            <span class="font-bold text-unik-primary">{{ $posts->total() ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-unik-muted">This Month</span>
                            <span class="font-bold text-unik-secondary">
                                {{ $posts->where('created_at', '>=', now()->subDays(30))->count() ?? 0 }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-unik-muted">Avg. Reading Time</span>
                            <span class="font-bold text-unik-accent">5 min</span>
                        </div>
                        <div class="pt-4 border-t border-unik-border">
                            <div class="text-sm text-unik-muted mb-2">Category Growth</div>
                            <div class="w-full bg-unik-light rounded-full h-2">
                                <div class="bg-unik-primary rounded-full h-2" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ad Box -->
                <div
                    class="bg-gradient-to-br from-unik-secondary to-unik-primary rounded-unik-lg p-6 text-center text-white">
                    <div class="mb-4">
                        <i class="fas fa-gem text-3xl opacity-80"></i>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Premium {{ $categoryName ?? '' }} Toolkit</h4>
                    <p class="text-sm opacity-90 mb-4">
                        Access exclusive resources, templates, and tools for mastering
                        {{ strtolower($categoryName ?? '') }}
                    </p>
                    <a href="#"
                        class="inline-block bg-[#3C41B7] text-unik-primary px-6 py-3 rounded-lg font-bold hover:bg-[#5053b3] transition-colors">
                        Upgrade Now
                    </a>
                </div>

                <!-- Related Categories -->
                @if (isset($categorySlug))
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                        <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-unik-primary"></i> Related Categories
                        </h3>
                        <div class="space-y-2">
                            @php
                                $relatedCategories = [
                                    'technology' => ['productivity', 'digital-trends', 'tutorials'],
                                    'travel' => ['lifestyle', 'stories-experiences'],
                                    'news' => ['news-updates', 'digital-trends'],
                                    'lifestyle' => ['travel', 'creativity-inspiration'],
                                    // Add more mappings as needed
                                ][$categorySlug] ?? ['technology', 'travel', 'news'];
                            @endphp
                            @foreach ($relatedCategories as $related)
                                <a href="{{ route('category.show', $related) }}"
                                    class="flex items-center justify-between p-3 rounded-lg hover:bg-unik-light transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-unik-primary/10 flex items-center justify-center group-hover:bg-unik-primary/20 transition-colors">
                                            <i class="fas fa-folder text-unik-primary text-sm"></i>
                                        </div>
                                        <span
                                            class="font-medium text-unik-dark">{{ ucwords(str_replace('-', ' ', $related)) }}</span>
                                    </div>
                                    <i
                                        class="fas fa-chevron-right text-unik-muted group-hover:text-unik-primary transition-colors"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Trending Articles -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-fire text-unik-accent"></i> Trending in {{ $categoryName ?? '' }}
                    </h3>
                    <div class="space-y-4">
                        @forelse($posts->take(3) as $trending)
                            <a href="{{ route('post.show', $trending->id) }}"
                                class="flex items-start gap-3 p-2 rounded-lg hover:bg-unik-light transition-colors group">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-lg bg-unik-primary/10 flex items-center justify-center group-hover:bg-unik-primary/20 transition-colors">
                                    <span class="font-bold text-unik-primary">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <h4
                                        class="font-medium text-unik-dark group-hover:text-unik-primary transition-colors text-sm">
                                        {{ Str::limit($trending->title ?? '', 50) }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-unik-muted">
                                            <i class="far fa-eye"></i> {{ $trending->views ?? '1.2k' }}
                                        </span>
                                        <span class="text-xs text-unik-muted">•</span>
                                        <span
                                            class="text-xs text-unik-muted">{{ optional($trending->created_at)->diffForHumans() ?? '' }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-newspaper text-2xl text-unik-muted mb-2"></i>
                                <p class="text-unik-muted text-sm">No trending articles available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Resources Download -->
                <div class="bg-white rounded-unik-lg p-6 border border-unik-border">
                    <h3 class="text-lg font-semibold text-unik-dark mb-4">Free Resources</h3>
                    <p class="text-sm text-unik-muted mb-4">
                        Download our free {{ strtolower($categoryName ?? '') }} checklist and templates
                    </p>
                    <a href="#"
                        class="inline-flex items-center justify-center w-full bg-unik-primary text-white py-3 rounded-lg font-medium hover:bg-unik-primary/90 transition-colors">
                        <i class="fas fa-download mr-2"></i> Download Now
                    </a>
                </div>
            </aside>
        </div>

        <!-- Author Spotlight -->
        @php
            $topAuthor = optional(
                optional(
                    $posts
                        ->groupBy('author_id')
                        ->sortByDesc(function ($posts) {
                            return $posts->sum('views');
                        })
                        ->first(),
                )->first(),
            );
        @endphp
        @if ($topAuthor && $topAuthor->id)
            <section class="mt-12 bg-white rounded-unik-xl p-8 border border-unik-border">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-unik-dark mb-2">Featured Author</h2>
                    <p class="text-unik-muted">Meet our top contributor in {{ $categoryName ?? '' }}</p>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="md:w-1/4">
                        <div
                            class="w-48 h-48 mx-auto rounded-full bg-gradient-to-r from-unik-primary to-unik-secondary p-1">
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-user-edit text-6xl text-unik-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-3/4">
                        <h3 class="text-2xl font-bold text-unik-dark mb-3">
                            {{ $topAuthor->author_name ?? 'Expert Author' }}
                        </h3>
                        <p class="text-unik-muted mb-6 leading-relaxed">
                            With over {{ $posts->where('author_id', $topAuthor->author_id)->count() ?? 0 }} articles in
                            {{ $categoryName ?? '' }}, our featured author brings deep expertise and practical
                            insights.
                            Their work has been read
                            {{ $posts->where('author_id', $topAuthor->author_id)->sum('views') ?? '0' }}
                            times.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="#"
                                class="px-6 py-2 bg-unik-primary text-white rounded-lg hover:bg-unik-primary/90 transition-colors">
                                View All Articles
                            </a>
                            <a href="#"
                                class="px-6 py-2 border border-unik-primary text-unik-primary rounded-lg hover:bg-unik-primary/5 transition-colors">
                                Follow Author
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('common.footer')

    <script>
        // Reading Progress Bar
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progressBar = document.getElementById('readingProgress');
            if (progressBar) {
                progressBar.style.width = scrolled + "%";
            }
        });

        // Smooth scroll to top
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>


