@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unik Muse Blog - Insights, Stories, Trends and Expert Opinions</title>
    <meta name="description"
        content="Discover expert insights, technology updates, travel guides, news breakdowns, and inspirational stories. Explore curated articles across multiple categories including Tech, Travel, Lifestyle, Creativity, and Productivity.">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="Unik Muse Blog - Insights, Stories, Trends and Expert Opinions">
    <meta property="og:description"
        content="Discover expert insights, technology updates, travel guides, news breakdowns, and inspirational stories.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/snow.webp') }}">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Unik Muse Blog",
        "description": "Your destination for meaningful content across technology, travel, digital culture, creativity, and productivity.",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/blog') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/blog.js')
</head>

<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')
    @php
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
    @endphp
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent text-white py-16 md:py-24">
        <div class="container-unik text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 font-serif">
                Explore Ideas That Inspire and Inform
            </h1>
            <p class="text-lg md:text-xl max-w-3xl mx-auto opacity-90">
                At Unik Muse, we curate stories, guides, tutorials, and insights across technology, travel, culture,
                creativity, and modern digital life. Stay updated and discover new perspectives every day.
            </p>
        </div>
    </section>

    <div class="container-unik py-8 md:py-12">
        <!-- Featured Posts -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-3 font-serif">
                    Featured Articles
                </h2>
                <p class="text-unik-muted text-lg max-w-2xl mx-auto">
                    Handpicked stories that are currently trending, most loved, or carry meaningful insights.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach ($featuredPosts as $post)
                        @php
                            $path = $post['thumbnail']['original']['webp'] ?? null;
                            $alt = $post['title'] ?? 'Blog Post Thumbnail';
                        @endphp
                    <article
                        class="bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1"
                        itemscope itemtype="https://schema.org/BlogPosting">
                        <div class="overflow-hidden rounded-t-unik-lg">
                            <img src="{{ asset('storage/' . $path) }}"
                                alt="{{ $alt }}"
                                class="w-full h-48 md:h-56 object-cover hover:scale-105 transition-transform duration-300"
                                itemprop="image">
                        </div>
                        <div class="p-6">
                            <span
                                class="inline-block bg-unik-primary text-white px-3 py-1 rounded-full text-sm font-medium mb-3">
                                {{ $post['category'] }}
                            </span>
                            <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-3 line-clamp-2"
                                itemprop="headline">
                                {{ $post['title'] }}
                            </h3>
                            <p class="text-unik-muted mb-4 text-sm md:text-base line-clamp-3" itemprop="description">
                                 {{ Str::limit(strip_tags($parsedown->text($post['excerpt'] ?? '')), 120) }}
                            </p>
                            <a href="{{ route('post.show', $post['id']) }}"
                                class="block px-5 py-2 bg-unik-primary items-center text-white font-medium hover:color-unik-secondary transition-colors text-sm"
                                itemprop="mainEntityOfPage">
                                Read More <i class="fas fa-arrow-right ml-2 text-white"></i>
                            </a>
                        </div>
                        <meta itemprop="datePublished"
                            content="{{ Carbon::parse($post['published_at'])->toIso8601String() }}">
                        <meta itemprop="dateModified"
                            content="{{ Carbon::parse($post['updated_at'])->toIso8601String() }}">
                        <meta itemprop="author" content="Unik Muse">
                        <meta itemprop="publisher" content="Unik Muse">
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Category Filters -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-3 font-serif">
                    Browse by Category
                </h2>
                <p class="text-unik-muted text-lg max-w-2xl mx-auto">
                    Explore posts based on your interests
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-3 md:gap-4">
                <button
                    class="px-5 py-2.5 rounded-full border border-unik-border hover:border-unik-primary hover:text-unik-primary transition-colors {{ !$category ? 'bg-unik-primary text-white border-unik-primary hover:text-white hover:bg-unik-primary/90' : 'bg-white text-unik-dark' }}"
                    onclick="window.location='{{ route('blog.index') }}'">
                    All Categories
                </button>
                @foreach ($categoriesList as $catName => $count)
                    @foreach ($defaultCategories as $name => $displayName)
                        @if ($displayName == $catName)
                            @php $Name = $name; @endphp
                            <button
                                class="px-5 py-2.5 rounded-full border border-unik-border hover:border-unik-primary hover:text-unik-primary transition-colors {{ $category == $Name ? 'bg-unik-primary text-white border-unik-primary hover:text-white hover:bg-unik-primary/90' : 'bg-white text-unik-dark' }}"
                                onclick="window.location='{{ route('blog.index', ['category' => $Name]) }}'">
                                {{ $catName }}
                            </button>
                            @break
                        @endif
                    @endforeach
                @endforeach
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <main class="lg:col-span-2">
                <section>
                    <div class="text-center mb-12">
                        <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-3 font-serif">
                            Latest Posts
                        </h2>
                        <p class="text-unik-muted text-lg">
                            Browse the newest articles across all categories
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        @foreach ($posts as $post)
                            <article
                                class="bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1"
                                itemscope itemtype="https://schema.org/BlogPosting">
                                <div class="overflow-hidden rounded-t-unik-lg">
                                    @php
                                        $path = $post['thumbnail']['original']['webp'] ?? null;
                                        $alt = $post['title'] ?? 'Blog Post Thumbnail';
                                    @endphp
                                    <img src="{{ asset('storage/' . $path) }}"
                                        alt="{{ $alt }}"
                                        class="w-full h-48 md:h-56 object-cover hover:scale-105 transition-transform duration-300"
                                        itemprop="image">
                                </div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <span
                                            class="bg-unik-primary/10 text-unik-primary px-3 py-1 rounded-full text-xs font-medium">
                                            {{ $post['category'] }}
                                        </span>
                                        <span class="text-unik-muted text-sm">
                                            {{ $post['estimated_reading_time'] }} min read
                                        </span>
                                    </div>
                                    <h3 class="text-lg md:text-xl font-semibold text-unik-dark mb-3 line-clamp-2"
                                        itemprop="headline">
                                        {{ $post['title'] }}
                                    </h3>
                                    <p class="text-unik-muted mb-6 text-sm md:text-base line-clamp-3"
                                        itemprop="description">
                                        {{ Str::limit(strip_tags($parsedown->text($post['excerpt'] ?? '')), 150) }}
                                    </p>
                                    <div class="flex justify-between items-center pt-4 border-t border-unik-border">
                                        <span class="text-unik-muted text-sm" itemprop="datePublished">
                                            {{ Carbon::parse($post['published_at'])->format('M d, Y') }}
                                        </span>
                                        <a href="{{ route('post.show', $post['id']) }}"
                                            class="inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary transition-colors text-sm">
                                            Read More <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                                <meta itemprop="dateModified"
                                    content="{{ Carbon::parse($post['updated_at'])->toIso8601String() }}">
                                <meta itemprop="author" content="Unik Muse">
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center items-center gap-2 mt-12">

                        @if ($posts->hasPages())
                            <nav class="flex items-center justify-center gap-2" role="navigation"
                                aria-label="Pagination Navigation">
                                {{-- Previous Page Link --}}
                                @if ($posts->onFirstPage())
                                    <span aria-disabled="true" aria-label="Previous">
                                        <span
                                            class="px-4 py-2 bg-unik-light rounded-unik-md border border-unik-border text-unik-muted cursor-not-allowed"
                                            aria-hidden="true">
                                            <i class="fas fa-chevron-left mr-1"></i> Previous
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $posts->previousPageUrl() }}" rel="prev"
                                        aria-label="Previous Page"
                                        class="px-4 py-2 rounded-unik-md border border-unik-border bg-white text-unik-primary hover:bg-unik-primary hover:text-white transition-colors duration-300">
                                        <i class="fas fa-chevron-left mr-1 text-unik-muted"></i> <span
                                            class="text-unik-muted">Previous</span>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                <div class="flex items-center gap-1">
                                    @php
                                        $current = $posts->currentPage();
                                        $last = $posts->lastPage();
                                        $start = max($current - 2, 1);
                                        $end = min($current + 2, $last);
                                    @endphp

                                    {{-- First Page --}}
                                    @if ($start > 1)
                                        <a href="{{ $posts->url(1) }}"
                                            class="px-3 py-1.5 rounded-unik-md border border-unik-border text-unik-muted bg-white text-unik-primary hover:bg-unik-primary hover:text-white transition-colors duration-300">
                                            1
                                        </a>
                                        @if ($start > 2)
                                            <span class="px-2 text-unik-muted">...</span>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $current)
                                            <span aria-current="page">
                                                <span
                                                    class="px-3 py-1.5 rounded-unik-md bg-unik-primary text-white border border-unik-primary">
                                                    {{ $page }}
                                                </span>
                                            </span>
                                        @else
                                            <a href="{{ $posts->url($page) }}"
                                                class="px-3 py-1.5 rounded-unik-md border border-unik-border bg-white text-unik-muted hover:bg-unik-primary hover:text-white transition-colors duration-300">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if ($end < $last)
                                        @if ($end < $last - 1)
                                            <span class="px-2 text-unik-muted">...</span>
                                        @endif
                                        <a href="{{ $posts->url($last) }}"
                                            class="px-3 py-1.5 rounded-unik-md border border-unik-border bg-white text-unik-muted hover:bg-unik-primary hover:text-white transition-colors duration-300">
                                            {{ $last }}
                                        </a>
                                    @endif
                                </div>

                                {{-- Next Page Link --}}
                                @if ($posts->hasMorePages())
                                    <a href="{{ $posts->nextPageUrl() }}" rel="next" aria-label="Next Page"
                                        class="px-4 py-2 rounded-unik-md border border-unik-border bg-white text-unik-primary hover:bg-unik-primary hover:text-white transition-colors duration-300">
                                        <span class="text-unik-muted">Next</span> <i
                                            class="fas fa-chevron-right ml-1 text-unik-muted"></i>
                                    </a>
                                @else
                                    <span aria-disabled="true" aria-label="Next">
                                        <span
                                            class="px-4 py-2 rounded-unik-md border border-unik-border bg-unik-light text-unik-muted cursor-not-allowed"
                                            aria-hidden="true">
                                            Next <i class="fas fa-chevron-right ml-1"></i>
                                        </span>
                                    </span>
                                @endif
                            </nav>
                        @endif
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-8">
                <!-- Categories -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h3 class="text-xl font-semibold text-unik-dark mb-4 font-serif">Categories</h3>
                    <ul class="space-y-2">
                        @foreach ($categoriesList as $catName => $count)
                            <li>
                                @foreach ($defaultCategories as $name => $displayName)
                                    @if ($displayName == $catName)
                                        @php $catName = $name; @endphp
                                        <a href="{{ route('category.show', $catName) }}"
                                            class="flex px-5 py-2 bg-unik-primary text-white justify-between items-center p-3 rounded-unik-md hover:bg-[#5f6397] text-unik-primary transition-colors group">
                                            <span class="font-medium">{{ $catName }}</span>
                                            <span
                                                class="bg-unik-light text-unik-dark text-xs px-2 py-1 rounded-full">{{ $count }}</span>
                                        </a>
                                        @break
                                    @endif
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h3 class="text-xl font-semibold text-unik-dark mb-4 font-serif">Search Articles</h3>
                    <div class="relative">
                        <i
                            class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-unik-muted"></i>
                        <input type="text" placeholder="Search for articles..."
                            class="w-full pl-12 pr-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent transition-colors">
                    </div>
                </div>

                <!-- Popular Posts -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h3 class="text-xl font-semibold text-unik-dark mb-4 font-serif">Most Popular</h3>
                    <div class="space-y-4">
                        @foreach ($popularPosts as $popular)
                            <a href="{{ route('post.show', $popular['id']) }}" class="block">
                                <div
                                @php
                                    $path = $popular['thumbnail']['original']['webp'] ?? null;
                                    $alt = $popular['title'] ?? 'Blog Post Thumbnail';
                                @endphp
                                    class="flex items-start gap-3 p-2 rounded-unik-md hover:bg-unik-primary/5 transition-colors group">

                                    <img src="{{ asset('storage/' . $path) }}"
                                        alt="{{ $alt }}"
                                        class="w-16 h-16 object-cover rounded-unik-md flex-shrink-0">

                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="font-medium text-unik-dark text-sm mb-1 line-clamp-2 group-hover:text-unik-primary transition-colors">
                                            {{ Str::limit($popular['title'], 50) }}
                                        </h4>

                                        <div class="text-unik-muted text-xs">
                                            {{ $popular['category'] }} • {{ $popular['estimated_reading_time'] }} min
                                            read
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter -->
                <div
                    class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent rounded-unik-lg p-6 text-white">
                    <h3 class="text-xl font-semibold mb-3 font-serif">Subscribe to Our Newsletter</h3>
                    <p class="text-white/90 mb-4 text-sm">Get the latest insights delivered to your inbox</p>
                    <form id="newsletter-form" class="space-y-3">
                        <input type="email"
                            class="w-full px-4 py-3 border border-1 rounded-unik-md border-none focus:outline-none focus:ring-2 focus:ring-white/30 text-unik-dark"
                            placeholder="Your email address" required>
                        <button type="submit"
                            class="w-full bg-white mt-1 text-unik-primary font-semibold py-3 rounded-unik-md hover:bg-white/90 transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>

                <!-- AdSense Placeholder -->
                <div class="bg-unik-light border-2 border-dashed border-unik-border rounded-unik-lg p-6 text-center">
                    <p class="text-sm text-unik-muted">AdSense Advertisement</p>
                </div>
            </aside>
        </div>
    </div>

    @include('common.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[type="email"]').value;
                    alert('Thank you for subscribing with: ' + email);
                    this.reset();
                });
            }

            const searchInput = document.querySelector('.search-box input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = '{{ route('blog.index') }}?search=' +
                                encodeURIComponent(query);
                        }
                    }
                });
            }

            const blogCards = document.querySelectorAll('.blog-card');
            blogCards.forEach(card => {
                const title = card.querySelector('.blog-title')?.textContent;
                const description = card.querySelector('.blog-excerpt')?.textContent;
                const datePublished = card.querySelector('meta[itemprop="datePublished"]')?.getAttribute(
                    'content');
                const dateModified = card.querySelector('meta[itemprop="dateModified"]')?.getAttribute(
                    'content');

                if (title && description) {
                    const schema = {
                        "@context": "https://schema.org",
                        "@type": "BlogPosting",
                        "headline": title.trim(),
                        "description": description.trim(),
                        "datePublished": datePublished,
                        "dateModified": dateModified,
                        "author": {
                            "@type": "Organization",
                            "name": "Unik Muse"
                        },
                        "publisher": {
                            "@type": "Organization",
                            "name": "Unik Muse",
                            "logo": {
                                "@type": "ImageObject",
                                "url": "{{ asset('assets/unikmusewhite.webp') }}"
                            }
                        }
                    };

                    const script = document.createElement('script');
                    script.type = 'application/ld+json';
                    script.textContent = JSON.stringify(schema);
                    document.head.appendChild(script);
                }
            });
        });
    </script>
</body>

</html>

