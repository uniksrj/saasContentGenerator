<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
@php
    $rawSearch = request('q');
    $decodedSearch = '';

    if (!empty($rawSearch)) {
        $decoded = base64_decode((string) $rawSearch, true);
        $decodedSearch = $decoded !== false ? trim($decoded) : trim((string) $rawSearch);
    }

    $mainLinks = [
        ['label' => 'Home', 'url' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Blog', 'url' => route('blog.index'), 'active' => request()->routeIs('blog.*') || request()->routeIs('post.show')],
        ['label' => 'Categories', 'url' => route('categories.index'), 'active' => request()->routeIs('categories.index') || request()->routeIs('category.show')],
        ['label' => 'About', 'url' => route('about'), 'active' => request()->routeIs('about')],
        ['label' => 'Contact', 'url' => route('contact'), 'active' => request()->routeIs('contact')],
    ];

    $categoryLinks = [
        ['label' => 'Technology', 'slug' => 'technology'],
        ['label' => 'Travel', 'slug' => 'travel'],
        ['label' => 'Lifestyle', 'slug' => 'life-style'],
        ['label' => 'Digital Trends', 'slug' => 'digital-trends'],
        ['label' => 'Productivity', 'slug' => 'productivity'],
        ['label' => 'News & Updates', 'slug' => 'news-updates'],
        ['label' => 'Stories & Experiences', 'slug' => 'stories-experiences'],
        ['label' => 'Creativity & Inspiration', 'slug' => 'creativity-inspiration'],
    ];
@endphp

<style>
    .site-header-wrap a {
        text-decoration: none !important;
    }

    .site-header-wrap .header-link {
        color: rgba(241, 245, 249, 0.9) !important;
    }

    .site-header-wrap .header-link:hover,
    .site-header-wrap .header-link:focus-visible {
        color: #ff0000 !important;
    }

    .site-header-wrap .header-link-active {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.12);
    }

    .site-header-wrap .dropdown-panel a {
        color: #0f172a !important;
    }

    .site-header-wrap .dropdown-panel a:hover {
        color: #0f172a !important;
        background-color: #e2e8f0;
    }

    .site-header-wrap .desktop-category-item .dropdown-panel {
        opacity: 0;
        visibility: hidden;
        transform: translateY(6px);
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
    }

    .site-header-wrap .desktop-category-item::after {
        content: "";
        position: absolute;
        left: 0;
        top: 100%;
        width: 100%;
        height: 10px;
    }

    .site-header-wrap .desktop-category-item:hover .dropdown-panel,
    .site-header-wrap .desktop-category-item:focus-within .dropdown-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    .site-header-wrap .brand-title,
    .site-header-wrap .brand-subtitle,
    .site-header-wrap .search-icon,
    .site-header-wrap .menu-label {
        color: #ffffff !important;
    }

    .site-header-wrap .menu-overlay {
        background: rgba(2, 6, 23, 0.6);
    }
</style>

<header class="site-header-wrap sticky top-0 z-50 border-b border-slate-800/60 bg-slate-950/95 shadow-lg backdrop-blur">
    <div class="container-unik py-3">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3" aria-label="Unik Muse home">
                <img src="{{ asset('assets/unikmusewhite.webp') }}" alt="Unik Muse" class="h-9 w-auto sm:h-10">
                <div class="hidden sm:block">
                    <p class="brand-title mb-0 text-sm font-semibold tracking-wide">Unik Muse</p>
                    <p class="brand-subtitle mb-0 text-[11px] uppercase tracking-[0.16em] text-slate-300">Editorial Blog</p>
                </div>
            </a>

            <form action="{{ route('home') }}" method="GET" role="search" class="hidden flex-1 items-center justify-end px-2 lg:flex">
                <label for="header-search" class="sr-only">Search articles</label>
                <div class="relative w-full max-w-md">
                    <input
                        id="header-search"
                        name="q"
                        type="text"
                        value="{{ $decodedSearch }}"
                        placeholder="Search articles, topics, categories..."
                        class="w-full rounded-full border border-slate-700 bg-slate-900/70 px-4 py-2.5 pr-11 text-sm text-slate-100 placeholder:text-slate-400 focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/40">
                    <button type="submit" class="search-icon absolute right-3 top-1/2 -translate-y-1/2 text-sm" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <button
                type="button"
                id="header-menu-toggle"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm font-medium text-slate-100 transition hover:bg-slate-800 md:hidden"
                aria-expanded="false"
                aria-controls="mobile-nav-panel">
                <i class="fas fa-bars"></i>
                <span class="menu-label">Menu</span>
            </button>
        </div>

        <nav class="mt-3 hidden items-center justify-between gap-3 md:flex" aria-label="Main Navigation">
            <ul class="flex flex-wrap items-center gap-1 lg:gap-2">
                @foreach ($mainLinks as $link)
                    @if ($link['label'] === 'Categories')
                        <li class="group relative desktop-category-item">
                            <a href="{{ $link['url'] }}"
                               class="header-link inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition {{ $link['active'] ? 'header-link-active' : '' }}"
                               aria-current="{{ $link['active'] ? 'page' : 'false' }}">
                                {{ $link['label'] }} <i class="fas fa-chevron-down text-[10px]"></i>
                            </a>
                            <div class="dropdown-panel absolute left-0 top-full z-20 w-72 rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                                @foreach ($categoryLinks as $category)
                                    <a href="{{ route('category.show', $category['slug']) }}" class="block rounded-lg px-3 py-2 text-sm font-medium transition">
                                        {{ $category['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="header-link inline-flex rounded-lg px-4 py-2 text-sm font-medium transition {{ $link['active'] ? 'header-link-active' : '' }}"
                               aria-current="{{ $link['active'] ? 'page' : 'false' }}">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 rounded-full bg-cyan-400 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-cyan-300">
                Explore Posts <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </nav>

        <div id="mobile-nav-panel" class="hidden md:hidden" aria-hidden="true">
            <div id="mobile-nav-overlay" class="menu-overlay fixed inset-0 z-40"></div>
            <div class="fixed inset-y-0 right-0 z-50 w-[86%] max-w-sm overflow-y-auto border-l border-slate-700 bg-slate-950 p-5 shadow-2xl size-dvh">
                <div class="mb-5 flex items-center justify-between">
                    <p class="mb-0 text-sm font-semibold uppercase tracking-[0.16em] text-slate-300">Navigation</p>
                    <button type="button" id="mobile-nav-close" class="rounded-md border border-slate-700 px-2 py-1 text-slate-200">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('home') }}" method="GET" role="search" class="mb-5">
                    <label for="header-search-mobile" class="sr-only">Search articles</label>
                    <div class="relative">
                        <input
                            id="header-search-mobile"
                            name="q"
                            type="text"
                            value="{{ $decodedSearch }}"
                            placeholder="Search articles..."
                            class="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-2.5 pr-10 text-sm text-slate-100 placeholder:text-slate-400 focus:border-cyan-400 focus:outline-none">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="space-y-1">
                    @foreach ($mainLinks as $link)
                        @if ($link['label'] === 'Categories')
                            <li>
                                <button type="button" id="mobile-categories-toggle" class="header-link flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium {{ $link['active'] ? 'header-link-active' : '' }}">
                                    Categories
                                    <i id="mobile-categories-icon" class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div id="mobile-categories-list" class="mt-1 hidden rounded-lg border border-slate-800 bg-slate-900 p-2">
                                    @foreach ($categoryLinks as $category)
                                        <a href="{{ route('category.show', $category['slug']) }}" class="header-link block rounded-md px-3 py-2 text-sm">
                                            {{ $category['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @else
                            <li>
                                <a href="{{ $link['url'] }}" class="header-link block rounded-lg px-3 py-2.5 text-sm font-medium {{ $link['active'] ? 'header-link-active' : '' }}" aria-current="{{ $link['active'] ? 'page' : 'false' }}">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const panel = document.getElementById('mobile-nav-panel');
            const toggleBtn = document.getElementById('header-menu-toggle');
            const closeBtn = document.getElementById('mobile-nav-close');
            const overlay = document.getElementById('mobile-nav-overlay');
            const categoriesToggle = document.getElementById('mobile-categories-toggle');
            const categoriesList = document.getElementById('mobile-categories-list');
            const categoriesIcon = document.getElementById('mobile-categories-icon');

            const openMenu = () => {
                if (!panel || !toggleBtn) return;
                panel.classList.remove('hidden');
                panel.setAttribute('aria-hidden', 'false');
                toggleBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            };

            const closeMenu = () => {
                if (!panel || !toggleBtn) return;
                panel.classList.add('hidden');
                panel.setAttribute('aria-hidden', 'true');
                toggleBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = 'auto';
            };

            if (toggleBtn) {
                toggleBtn.addEventListener('click', openMenu);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeMenu);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMenu);
            }

            if (categoriesToggle && categoriesList && categoriesIcon) {
                categoriesToggle.addEventListener('click', () => {
                    categoriesList.classList.toggle('hidden');
                    categoriesIcon.classList.toggle('fa-chevron-down');
                    categoriesIcon.classList.toggle('fa-chevron-up');
                });
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });
        })();
    </script>
</header>

@if (!empty($breadcrumbs))
    <nav aria-label="Breadcrumb">
        <div class="bg-white border-b border-unik-border">
            <div class="container-unik py-4 flex items-center space-x-2 text-sm">
                @foreach ($breadcrumbs as $crumb)
                    <a href="{{ $crumb['url'] ?? 'javascript:void(0)' }}"
                        class="flex items-center text-unik-primary hover:text-[#424242] transition-colors">
                        <i class="fas {{ $crumb['icon'] ?? 'fa-circle' }} mr-1"></i> {{ $crumb['label'] }}
                    </a>
                    @if (!$loop->last)
                        <i class="fas fa-chevron-right text-unik-muted text-xs mx-2"></i>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif

@if (!empty($breadcrumbs))
    @php
        $breadcrumbItems = collect($breadcrumbs)
            ->values()
            ->map(function ($crumb, $index) {
                $item = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => (string) ($crumb['label'] ?? ''),
                ];

                if (!empty($crumb['url'])) {
                    $item['item'] = (string) $crumb['url'];
                }

                return $item;
            })
            ->all();

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ];
    @endphp
    <script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
