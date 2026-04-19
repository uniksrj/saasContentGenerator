<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Categories - Browse Topics | Unik Muse</title>
    <meta name="description"
        content="Explore all content categories including Technology, Travel, Lifestyle, News, Productivity, and more. Find articles by topic.">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="All Categories - Browse Topics | Unik Muse">
    <meta property="og:description"
        content="Explore all content categories and find articles by topic on Unik Muse.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/snow.webp') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
</head>

<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')    

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent text-white py-12 md:py-16">
        <div class="container-unik text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-serif">
                Explore All Categories
            </h1>
            <p class="text-lg md:text-xl max-w-3xl mx-auto opacity-90">
                Browse through our collection of topics. Find articles, guides, and insights across different
                categories.
            </p>
            <div class="mt-8 flex justify-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 inline-flex items-center gap-4">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ count($categories) }} Categories</span>
                    </span>
                    <span class="h-4 w-px bg-white/30"></span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-newspaper"></i>
                        <span>{{ $totalPosts ?? 0 }} Articles</span>
                    </span>
                </div>
            </div>
        </div>
    </section>
    
    <main class="container-unik py-8 md:py-12">
        <!-- Search & Filter -->
        {{-- <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-auto">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-unik-muted"></i>
                    <input type="text" placeholder="Search categories..."
                        class="bg-white pl-12 pr-4 py-3 w-full md:w-80 border border-unik-border rounded-lg focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent">
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-unik-muted">Sort by:</span>
                    <select
                        class="bg-white border border-unik-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-unik-primary">
                        <option value="popular">Most Popular</option>
                        <option value="posts">Most Posts</option>
                        <option value="name">Alphabetical</option>
                        <option value="recent">Recently Updated</option>
                    </select>
                </div>
            </div>
        </div> --}}

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($categories as $category)
                @php
                    $postCount = $category['post_count'] ?? 0;
                    $icon = match ($category['name']) {
                        'Technology' => 'laptop-code',
                        'Travel' => 'plane',
                        'Lifestyle' => 'heart',
                        'News & Updates' => 'newspaper',
                        'Digital Trends' => 'chart-line',
                        'Productivity' => 'check-double',
                        'Tutorials' => 'graduation-cap',
                        'Stories & Experiences' => 'book-open',
                        'Creativity & Inspiration' => 'lightbulb',
                        default => 'folder',
                    };
                    $color = match ($category['name']) {
                        'Technology' => 'bg-blue-100 text-blue-600',
                        'Travel' => 'bg-green-100 text-green-600',
                        'Lifestyle' => 'bg-purple-100 text-purple-600',
                        'News & Updates' => 'bg-red-100 text-red-600',
                        'Digital Trends' => 'bg-indigo-100 text-indigo-600',
                        'Productivity' => 'bg-amber-100 text-amber-600',
                        'Tutorials' => 'bg-emerald-100 text-emerald-600',
                        'Stories & Experiences' => 'bg-pink-100 text-pink-600',
                        'Creativity & Inspiration' => 'bg-cyan-100 text-cyan-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp

                <a href="{{ route('category.show', $category['slug']) }}"
                    class="category-card block bg-white rounded-xl shadow-md border border-unik-border hover:shadow-lg hover:border-unik-primary transition-all duration-300 hover:-translate-y-1 p-6 group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="{{ $color }} w-14 h-14 rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $icon }} text-xl"></i>
                        </div>
                        <span class="bg-unik-light text-unik-dark text-sm font-medium px-3 py-1 rounded-full">
                            {{ $postCount }} posts
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-unik-dark mb-3 group-hover:text-unik-primary transition-colors">
                        {{ $category['name'] }}
                    </h3>

                    <p class="text-unik-muted text-sm mb-4 line-clamp-2">
                        {{ $category['description'] ?? 'Explore articles and insights about ' . strtolower($category['name']) }}
                    </p>

                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-unik-border">
                        <span class="text-xs text-unik-muted flex items-center gap-1">
                            <i class="far fa-clock"></i>
                            {{ $category['reading_time'] ?? '5' }} min avg read
                        </span>
                        <span class="text-unik-primary font-medium group-hover:translate-x-1 transition-transform">
                            Explore <i class="fas fa-arrow-right ml-1"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Featured Categories Section -->
        <section class="mt-16">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-3 font-serif">
                    Trending Categories with Latest Articles
                </h2>
                <p class="text-unik-muted max-w-2xl mx-auto">
                    Explore categories with recently published content
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach (($trendingCategories ?? array_slice($categories, 0, 2)) as $category)
                    <div class="bg-white rounded-xl shadow-md border border-unik-border p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="{{ $category['icon_bg'] }} {{ $category['icon_color'] }} w-12 h-12 rounded-lg flex items-center justify-center">
                                <i class="fas fa-{{ $category['icon'] }}"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-unik-dark">{{ $category['name'] }}</h3>
                                <p class="text-sm text-unik-muted">{{ $category['post_count'] }} total articles</p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <h4 class="font-semibold text-unik-dark">Latest Articles:</h4>
                            @foreach (($latestPostsByCategory[$category['slug']] ?? collect()) as $post)
                                <a href="{{ route('post.show', $post->id) }}"
                                    class="block p-3 rounded-lg border border-unik-border hover:border-unik-primary hover:bg-unik-primary/5 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-sm font-medium text-unik-dark truncate">{{ $post->title }}</span>
                                        <span
                                            class="text-xs text-unik-muted">{{ $post->created_date->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('category.show', $category['slug']) }}"
                            class="block text-center bg-unik-primary text-white py-3 rounded-lg font-medium hover:bg-unik-primary/90 transition-colors">
                            View All {{ $category['name'] }} Articles
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
        
        <!-- Category Statistics -->
        <div class="mt-12 bg-white rounded-xl shadow-sm border border-unik-border p-6">
            <h3 class="text-xl font-bold text-unik-dark mb-6">Category Statistics</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-unik-primary mb-2">{{ count($categories) }}</div>
                    <div class="text-sm text-unik-muted">Total Categories</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-unik-secondary mb-2">{{ $totalPosts ?? 0 }}</div>
                    <div class="text-sm text-unik-muted">Total Articles</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-unik-accent mb-2">{{ $totalAuthors ?? 1 }}+</div>
                    <div class="text-sm text-unik-muted">Contributing Authors</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-unik-light mb-2">{{ $totalViewsByMonth ?? '10K' }}+</div>
                    <div class="text-sm text-unik-muted">Monthly Views</div>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="mt-12 bg-gradient-to-r from-unik-primary to-unik-secondary rounded-2xl p-8 text-white text-center">
            <div class="max-w-2xl mx-auto">
                <i class="fas fa-envelope-open-text text-5xl mb-6"></i>
                <h3 class="text-2xl font-bold mb-4">Stay Updated Across All Categories</h3>
                <p class="opacity-90 mb-6 ">
                    Get weekly digest of the best articles from all our categories delivered to your inbox
                </p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="Your email address"
                        class="flex-grow px-4 py-3 bg-white rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                    <button type="submit"
                        class="px-6 py-3 bg-white text-unik-primary font-bold rounded-lg hover:bg-gray-100 transition-colors">
                        Subscribe
                    </button>
                </form>
                <p class="text-sm text-white mt-4">No spam. Unsubscribe anytime.</p>
            </div>
        </div>
    </main>

    @include('common.footer')

    <script>
        // Simple category search
        const categorySearchInput = document.querySelector('input[type="text"]');
        if (categorySearchInput) {
            categorySearchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const cards = document.querySelectorAll('.category-card');

                cards.forEach(card => {
                    const title = card.querySelector('h3')?.textContent?.toLowerCase() || '';
                    const description = card.querySelector('p')?.textContent?.toLowerCase() || '';

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Category sorting
        const categorySortSelect = document.querySelector('select');
        if (categorySortSelect) {
            categorySortSelect.addEventListener('change', function(e) {
                const sortBy = e.target.value;
                const container = document.querySelector('.grid');
                const cards = Array.from(document.querySelectorAll('.category-card'));

                if (!container || cards.length === 0) {
                    return;
                }

                cards.sort((a, b) => {
                    if (sortBy === 'popular') {
                        const aCount = parseInt(a.querySelector('.bg-unik-light')?.textContent || '0', 10);
                        const bCount = parseInt(b.querySelector('.bg-unik-light')?.textContent || '0', 10);
                        return bCount - aCount;
                    }
                    if (sortBy === 'name') {
                        const aName = a.querySelector('h3')?.textContent || '';
                        const bName = b.querySelector('h3')?.textContent || '';
                        return aName.localeCompare(bName);
                    }
                    return 0;
                });

                cards.forEach(card => container.appendChild(card));
            });
        }
    </script>
</body>

</html>
