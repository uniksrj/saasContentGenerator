@php
    $images = json_decode($post->file_path ?? '[]', true);
    $featuredImage = !empty($images) ? $images['medium']['webp'] : 'uploads/no_image.jpg';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Unik Muse</title>
    <meta name="description"
        content="{{ Str::limit(
            strip_tags(
                str_replace(['## ', '### ', '#### ', '##', '###', '####'], '', $post->excerpt ?? ($post->description ?? '')),
            ),
            120,
        ) }}">
    <link rel="canonical" href="{{ route('post.show', ['slugOrId' => $post->slug ?: $post->id]) }}">

    <!-- Open Graph for Social Sharing -->
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description"
        content="{{ Str::limit(
            strip_tags(
                str_replace(['## ', '### ', '#### ', '##', '###', '####'], '', $post->excerpt ?? ($post->description ?? '')),
            ),
            160,
        ) }}">
    <meta property="og:image" content="{{ asset('storage/' . $featuredImage) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "image": "{{ asset('storage/' . $featuredImage) }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->name ?? 'Admin' }}"
        },
        "datePublished": "{{ $post->created_date }}",
        "description": "{{ Str::limit(strip_tags($post->description), 160) }}"
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/readmore.js')
</head>

<body class="bg-unik-light text-unik-dark font-sans">

    

    @include('common.header')
    <!-- Reading Progress Bar -->
    <div class="fixed top-0 left-0 w-full h-1 bg-unik-primary/20 z-50">
        <div class="h-full bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent" id="readingProgress"
            style="width: 0%; transition: width 0.3s ease;">
        </div>
    </div>
    <!-- Article Header -->
    <header
        class="relative bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent text-white py-12 md:py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="container-unik relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 font-serif">
                    {{ $post->title }}
                </h1>
                <div class="flex flex-wrap justify-center gap-4 md:gap-6 text-sm md:text-base">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user"></i>
                        <span>By {{ $post->name ?? 'Admin' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($post->created_date)->format('F j, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-clock"></i>
                        <span>{{ $readingTime ?? 1 }} min read</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-eye"></i>
                        <span>{{ $viewStats['total'] ?? '0' }} views</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container-unik py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Article Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Inline Ad - Top -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <div class="text-xs uppercase text-unik-muted mb-4">Advertisement</div>
                    <div class="text-center py-4">
                        <i class="fas fa-ad fa-3x mb-4 text-unik-primary"></i>
                        <p class="text-unik-muted mb-4">This space is available for advertising</p>
                        <a href="#"
                            class="inline-block bg-unik-primary text-white px-6 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Article Content -->
                <article class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border">
                    <!-- Featured Image -->
                    <img src="{{ asset('storage/' . $featuredImage) }}" alt="{{ $post->title }}"
                        class="w-full h-64 md:h-96 object-cover rounded-unik-lg mb-8">

                    <!-- Table of Contents -->
                    <div class="bg-gradient-to-r rounded-unik-lg p-6 mb-8 border-l-4 border-l-unik-primary">
                        <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                            <i class="fas fa-list"></i> Table of Contents
                            @if (!empty($tocData))
                                <span class="ml-auto text-sm text-unik-muted font-normal" id="tocCount">
                                    {{ count($tocData) }} sections
                                </span>
                            @endif
                        </h3>
                        <ul class="md:space-y-2 lg:space-y-2" id="tocList">
                            @if (!empty($tocData))
                                @foreach ($tocData as $item)
                                    <li
                                        class="{{ $item['level'] == 3 ? 'pl-8' : ($item['level'] == 4 ? 'pl-12' : 'pl-4') }}">
                                        <a href="#{{ $item['slug'] ?? Str::slug($item['title']) }}"
                                            class="anchor-link flex items-center gap-3 p-3 rounded-lg border border-unik-border hover:border-unik-primary hover:bg-unik-primary/5 transition-all duration-200 hover:translate-x-1 text-unik-dark hover:text-unik-primary no-underline group">
                                            <span
                                                class="w-6 h-6 flex items-center justify-center rounded-full bg-unik-primary/10 text-unik-primary text-sm font-medium group-hover:bg-unik-primary group-hover:text-white transition-colors">
                                                {{ $loop->iteration }}
                                            </span>
                                            <span
                                                class="flex-1 text-sm group-hover:font-medium">{{ $item['title'] }}</span>
                                            <i
                                                class="fas fa-arrow-right text-xs text-unik-muted group-hover:text-unik-primary transition-colors"></i>
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-unik-muted text-sm italic">No table of contents available for this
                                    article.</li>
                            @endif
                        </ul>
                    </div>
                    @php
                        if (is_array($post->table_of_contents)) {
                            $tocData = $post->table_of_contents;
                        } elseif (is_string($post->table_of_contents) && !empty($post->table_of_contents)) {
                            $tocData = json_decode($post->table_of_contents, true) ?? [];
                        } else {
                            $tocData = [];
                        }
                    @endphp

                    <div class="mb-12">
                        <article class="prose prose-lg max-w-none bg-white md:p-8 content-body">
                            {!! (new Parsedown())->text($post->description ?? '') !!}
                            {{-- {{ $post->description }} --}}
                        </article>
                    </div>

                    <!-- Share Section -->
                    <div
                        class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent rounded-unik-lg p-6 md:p-8 text-white mt-8">
                        <h3 class="text-xl font-semibold mb-4 text-center">Share This Article</h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                                target="_blank">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                                target="_blank">
                                <i class="fab fa-twitter text-white"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                                target="_blank">
                                <i class="fab fa-linkedin-in text-white"></i>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ asset('storage/' . $post->image) }}&description={{ urlencode($post->title) }}"
                                class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                                target="_blank">
                                <i class="fab fa-pinterest-p text-white"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                                class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                                target="_blank">
                                <i class="fab fa-whatsapp text-white"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Author Section -->
                    <div class="bg-unik-light/30 rounded-unik-lg p-6 mt-8 border border-unik-border">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="https://ui-avatars.com/api/?name=Suraj+Magar&background=667eea&color=fff&size=100"
                                alt="Suraj Magar" class="w-24 h-24 rounded-full border-4 border-white shadow-unik-sm">
                            <div class="text-center md:text-left">
                                <h4 class="text-xl font-semibold text-unik-dark mb-2">About the Author</h4>
                                <p class="text-unik-muted">
                                    I'm Suraj Magar, a tech enthusiast and content creator who writes about the latest
                                    technology,
                                    gadgets, smartphones, and trending topics with simple and clear explanations.
                                </p>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Inline Ad - Middle -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <div class="text-xs uppercase text-unik-muted mb-4">Sponsored Content</div>
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn fa-3x mb-4 text-unik-accent"></i>
                        <p class="text-unik-muted mb-4">Promote your business here</p>
                        <a href="#"
                            class="inline-block bg-unik-primary text-white px-6 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Related Posts -->
                <section class="mt-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-8 font-serif">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($relatedPosts as $related)
                            @php
                                $images = json_decode($related->file_path ?? '[]', true);
                                $featuredImage = !empty($images) ? $images['thumb']['webp'] : 'uploads/no_image.jpg';
                            @endphp
                            <article
                                class="bg-white rounded-unik-lg shadow-unik-sm overflow-hidden border border-unik-border hover:shadow-unik-md transition-shadow">
                                <img src="{{ asset('storage/' . $featuredImage) }}" alt="{{ $related->title }}"
                                    class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h4 class="text-lg font-semibold text-unik-dark mb-3 line-clamp-2">
                                        {{ Str::limit($related->title, 60) }}
                                    </h4>
                                    <p class="text-unik-muted text-sm mb-4 line-clamp-3">
                                        {{ Str::limit($related->description, 100) }}
                                    </p>
                                    <a href="{{ url('/posts/' . $related->id) }}"
                                        class="inline-block bg-unik-primary text-white px-4 py-2 rounded-unik-md text-sm font-medium hover:bg-unik-primary/90 transition-colors">
                                        Related Topic
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <!-- Comments Section -->
                <section class="bg-white rounded-unik-lg shadow-unik-md p-6 md:p-8 border border-unik-border mt-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-unik-primary mb-8 font-serif">Comments
                        ({{ $comments->count() }})</h2>

                    <!-- Comment Form -->
                    <div class="bg-unik-light/30 rounded-unik-lg p-6 mb-8">
                        <h3 class="text-xl font-semibold text-unik-dark mb-4">Leave a Comment</h3>
                        <form id="commentForm" class="space-y-4">
                            <div>
                                <input type="text"
                                    class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent"
                                    placeholder="Your Name" required>
                            </div>
                            <div>
                                <input type="email"
                                    class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent"
                                    placeholder="Your Email" required>
                            </div>
                            <div>
                                <textarea
                                    class="w-full px-4 py-3 border border-unik-border rounded-unik-md focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent"
                                    rows="4" placeholder="Your Comment" required></textarea>
                            </div>
                            <button type="submit"
                                class="bg-unik-primary text-white px-6 py-3 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                                Post Comment
                            </button>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-6">
                        @foreach ($comments as $comment)
                            <div class="pb-6 border-b border-unik-border last:border-b-0 last:pb-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=667eea&color=fff&size=40"
                                        alt="{{ $comment->name }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <div class="font-semibold text-unik-dark">{{ $comment->name }}</div>
                                        <div class="text-sm text-unik-muted">
                                            {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <p class="text-unik-dark">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- Sidebar with Ads -->
            <aside class="space-y-6">
                <!-- Sidebar Ad 1 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <div class="text-xs uppercase text-unik-muted mb-4">Advertisement</div>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x mb-4 text-green-500"></i>
                        <p class="text-unik-muted mb-4">Boost Your Online Presence</p>
                        <a href="#"
                            class="inline-block bg-unik-primary text-white px-6 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                            Get Premium Ad
                        </a>
                    </div>
                </div>

                <!-- Newsletter Signup -->
                <div
                    class="bg-gradient-to-r from-unik-primary via-unik-secondary to-unik-accent rounded-unik-lg p-6 text-white">
                    <div class="text-center">
                        <i class="fas fa-envelope fa-3x mb-4"></i>
                        <h4 class="text-xl font-semibold mb-3">Subscribe to Newsletter</h4>
                        <p class="text-white/90 mb-4">
                            Get the latest articles delivered to your inbox
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your Email"
                                class="w-full px-4 py-3 rounded-unik-md border-none focus:outline-none focus:ring-2 focus:ring-white/30 text-unik-dark">
                            <button type="submit"
                                class="w-full bg-white text-unik-primary font-semibold py-3 rounded-unik-md hover:bg-white/90 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Ad 2 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <div class="text-xs uppercase text-unik-muted mb-4">Sponsored</div>
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-3x mb-4 text-red-500"></i>
                        <p class="text-unik-muted mb-4">Discover Amazing Products</p>
                        <a href="#"
                            class="inline-block bg-unik-primary text-white px-6 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                            Shop Now
                        </a>
                    </div>
                </div>

                <!-- Popular Posts -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <h4 class="text-lg font-semibold text-unik-dark mb-4">Popular Posts</h4>
                    <div class="space-y-3">
                        @foreach ($popularPosts as $popular)
                            @php
                                $images = json_decode($popular->file_path ?? '[]', true);
                                $featuredImage = !empty($images) ? $images['thumb']['webp'] : 'uploads/no_image.jpg';
                            @endphp
                            <a href="{{ url('/posts/' . $popular->id) }}"
                                class="flex items-center gap-3 p-3 rounded-unik-md hover:bg-unik-light transition-colors group">
                                <img src="{{ asset('storage/' . $featuredImage) }}" alt="{{ $popular->title }}"
                                    class="w-12 h-12 object-cover rounded-unik-md">
                                <span
                                    class="font-medium text-unik-dark group-hover:text-unik-primary transition-colors line-clamp-2">
                                    {{ Str::limit($popular->title, 40) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar Ad 3 -->
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                    <div class="text-xs uppercase text-unik-muted mb-4">Advertisement</div>
                    <div class="text-center py-4">
                        <i class="fas fa-rocket fa-3x mb-4 text-yellow-500"></i>
                        <p class="text-unik-muted mb-4">Grow Your Business</p>
                        <a href="#"
                            class="inline-block bg-unik-primary text-white px-6 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors">
                            Start Free Trial
                        </a>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Inline Ad - Bottom -->
        <div class="bg-white rounded-unik-lg shadow-unik-md p-6 border border-unik-border mt-12">
            <div class="text-xs uppercase text-unik-muted mb-4">Advertisement</div>
            <div class="text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    {{-- <i class="fas fa-star fa-3x text-yellow-500"></i> --}}
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-unik-dark mb-2">Premium Advertising Space</h4>
                        <p class="text-unik-muted">Reach thousands of readers with your message</p>
                    </div>
                    <a href="#"
                        class="bg-unik-primary text-white px-6 py-3 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors whitespace-nowrap">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('common.footer')

    <script>
        window.addEventListener('scroll', function() {
            const article = document.querySelector('.article-content');
            if (!article) return;

            const totalHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollPosition = window.scrollY;

            const progress = (scrollPosition / (totalHeight - windowHeight)) * 100;
            document.getElementById('readingProgress').style.width = Math.min(progress, 100) + '%';
        });

        function calculateReadingTime(text) {
            const wordsPerMinute = 200;
            const words = text.split(/\s+/).length;
            return Math.ceil(words / wordsPerMinute);
        }
        window.tocData = @json($tocData ?? []);
        window.postData = {
            title: "{{ $post->title }}",
            readingTime: {{ $readingTime ?? 1 }},
            viewCount: {{ $viewStats['total'] ?? 0 }}
        };
    </script>
</body>

</html>
