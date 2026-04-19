<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | Unik Muse</title>
    <meta name="description"
        content="Read our Terms & Conditions to understand the rules and guidelines for using Unik Muse. Learn about user responsibilities, content policies, and more.">
    <meta name="keywords" content="terms and conditions, terms of use, user agreement, website terms, legal agreement">
    <meta property="og:title" content="Terms & Conditions - Unik Muse">
    <meta property="og:description"
        content="Official Terms & Conditions for using Unik Muse. Understand your rights and responsibilities as a user.">
    <meta property="og:image" content="{{ asset('assets/snow.webp') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Terms & Conditions",
        "description": "Terms & Conditions for Unik Muse Blog",
        "url": "{{ url()->current() }}",
        "publisher": {
            "@type": "Organization",
            "name": "Unik Muse",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('assets/unikmusewhite.webp') }}"
            }
        },
        "datePublished": "{{ date('Y-m-d') }}",
        "dateModified": "{{ date('Y-m-d') }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        },{
            "@type": "ListItem",
            "position": 2,
            "name": "Terms & Conditions"
        }]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .terms-gradient {
            background: linear-gradient(135deg, var(--color-unik-secondary) 0%, var(--color-unik-accent) 100%);
        }

        .sticky-toc {
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
            background: linear-gradient(90deg, var(--color-unik-secondary), var(--color-unik-accent));
            width: 0%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            transition: width 0.3s ease;
        }

        .terms-section {
            border-left: 4px solid var(--color-unik-secondary);
            padding-left: 1.5rem;
            margin-left: 0.5rem;
        }

        .legal-note {
            border: 2px solid var(--color-status-warning);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.1));
        }
    </style>
</head>

<body class="bg-unik-light text-unik-dark">
    <!-- Reading Progress Bar -->
    <div class="progress-bar" id="readingProgress"></div>

    @include('common.header')

    <main class="container-unik py-8 md:py-12">
        <!-- Terms & Conditions Hero Section -->
        <section class="mb-12 fade-in">
            <div class="terms-gradient rounded-unik-xl text-white p-8 md:p-12 relative overflow-hidden">
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
                            <i class="fas fa-balance-scale mr-2"></i> Legal Agreement
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-file-contract mr-2"></i> Binding Terms
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">
                        Terms & Conditions
                    </h1>

                    <p class="text-xl opacity-90 max-w-3xl mx-auto mb-8">
                        Please read these terms carefully before using our website
                    </p>

                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ date('Y') }}</div>
                            <div class="text-sm opacity-80">Last Updated</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">12</div>
                            <div class="text-sm opacity-80">Sections</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">100%</div>
                            <div class="text-sm opacity-80">Legal Compliance</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Important Notice -->
        <div class="legal-note rounded-unik-lg p-6 mb-8 fade-in">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-status-warning/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl text-status-warning"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-unik-dark mb-2">Important Legal Notice</h3>
                    <p class="text-unik-muted mb-3">
                        By accessing or using this website, you agree to be bound by these Terms & Conditions. 
                        If you do not agree with any part of these Terms, you must discontinue use of the website immediately.
                    </p>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-status-warning"></i>
                            Effective: {{ date('F d, Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-globe-americas text-status-warning"></i>
                            Applicable Worldwide
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Table of Contents -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border sticky-toc">
                    <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-list-ol text-unik-secondary"></i> Quick Navigation
                    </h3>
                    <nav class="space-y-2">
                        @php
                            $sections = [
                                ['id' => 'acceptance', 'title' => 'Acceptance of Terms', 'icon' => 'fa-check-circle'],
                                ['id' => 'use-of-website', 'title' => 'Use of Website', 'icon' => 'fa-compass'],
                                ['id' => 'user-content', 'title' => 'User-Generated Content', 'icon' => 'fa-edit'],
                                ['id' => 'intellectual-property', 'title' => 'Intellectual Property', 'icon' => 'fa-copyright'],
                                ['id' => 'third-party-links', 'title' => 'Third-Party Links', 'icon' => 'fa-link'],
                                ['id' => 'disclaimer', 'title' => 'Disclaimer', 'icon' => 'fa-exclamation-triangle'],
                                ['id' => 'liability', 'title' => 'Limitation of Liability', 'icon' => 'fa-balance-scale'],
                                ['id' => 'termination', 'title' => 'Termination', 'icon' => 'fa-ban'],
                                ['id' => 'privacy', 'title' => 'Privacy Policy', 'icon' => 'fa-shield-alt'],
                                ['id' => 'changes', 'title' => 'Changes to Terms', 'icon' => 'fa-sync-alt'],
                                ['id' => 'governing-law', 'title' => 'Governing Law', 'icon' => 'fa-gavel'],
                                ['id' => 'contact', 'title' => 'Contact Information', 'icon' => 'fa-envelope'],
                            ];
                        @endphp
                        @foreach ($sections as $section)
                            <a href="#{{ $section['id'] }}" 
                               class="toc-item flex items-center p-3 rounded-lg hover:bg-unik-light transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-unik-secondary/10 flex items-center justify-center mr-3 group-hover:bg-unik-secondary/20">
                                    <i class="fas {{ $section['icon'] }} text-unik-secondary text-sm"></i>
                                </div>
                                <span class="font-medium text-unik-dark group-hover:text-unik-secondary transition-colors">
                                    {{ $section['title'] }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                    
                    <!-- Download Button -->
                    <div class="mt-8 pt-6 border-t border-unik-border">
                        <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 bg-unik-light text-unik-dark py-3 rounded-lg hover:bg-unik-border transition-colors">
                            <i class="fas fa-print"></i>
                            Print This Page
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Acceptance of Terms -->
                <section id="acceptance" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-check-circle text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">1. Acceptance of Terms</h2>
                                <p class="text-unik-muted">Last updated: {{ date('F d, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="text-lg leading-relaxed mb-6">
                                Welcome to <span class="font-bold text-unik-secondary">Unik Muse</span> ("we", "our", "us").
                                These Terms & Conditions govern your access to and use of {{ config('app.url', 'unikmuse.com') }}.
                            </p>
                            
                            <div class="bg-unik-light rounded-lg p-6 mb-6">
                                <h4 class="font-semibold text-unik-dark mb-4">By accessing or using this website, you confirm that you:</h4>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-status-success/20 flex items-center justify-center">
                                            <i class="fas fa-check text-status-success text-sm"></i>
                                        </div>
                                        <span>Are at least 13 years of age</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-status-success/20 flex items-center justify-center">
                                            <i class="fas fa-check text-status-success text-sm"></i>
                                        </div>
                                        <span>Have read and understood these Terms</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-status-success/20 flex items-center justify-center">
                                            <i class="fas fa-check text-status-success text-sm"></i>
                                        </div>
                                        <span>Agree to be bound by these Terms</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-status-success/20 flex items-center justify-center">
                                            <i class="fas fa-check text-status-success text-sm"></i>
                                        </div>
                                        <span>Will comply with applicable laws</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="mb-0 font-medium">
                                            If you do not agree with any part of these Terms, you must discontinue use of the website immediately.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Use of Website -->
                <section id="use-of-website" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-compass text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">2. Use of the Website</h2>
                                <p class="text-unik-muted">Permitted and prohibited uses</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="mb-6">You agree to use this website only for lawful purposes and in a manner that does not:</p>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-unik-light rounded-lg p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-status-error/20 flex items-center justify-center">
                                            <i class="fas fa-ban text-status-error"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark">Prohibited Actions</h4>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach([
                                            'Violate any applicable law or regulation',
                                            'Infringe upon intellectual property rights',
                                            'Harm, disrupt, or interfere with the website',
                                            'Attempt unauthorized access to systems',
                                            'Distribute malware or harmful code',
                                            'Engage in spamming or phishing'
                                        ] as $item)
                                            <li class="flex items-start">
                                                <i class="fas fa-times text-status-error mr-2 mt-1"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <div class="bg-gradient-to-br from-status-success/5 to-unik-light rounded-lg p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-status-success/20 flex items-center justify-center">
                                            <i class="fas fa-check-circle text-status-success"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark">Expected Conduct</h4>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach([
                                            'Respect other users and their rights',
                                            'Provide accurate information',
                                            'Report violations or issues',
                                            'Use appropriate language',
                                            'Follow community guidelines',
                                            'Respect intellectual property'
                                        ] as $item)
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-status-success mr-2 mt-1"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- User-Generated Content -->
                <section id="user-content" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-edit text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">3. User-Generated Content</h2>
                                <p class="text-unik-muted">Rules for content submission</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="mb-6">Users may be allowed to submit content such as:</p>
                            
                            <div class="grid md:grid-cols-3 gap-6 mb-8">
                                @foreach([
                                    ['icon' => 'fa-file-alt', 'title' => 'Blog Posts', 'desc' => 'Articles & stories'],
                                    ['icon' => 'fa-comments', 'title' => 'Comments', 'desc' => 'Discussions & feedback'],
                                    ['icon' => 'fa-envelope', 'title' => 'Messages', 'desc' => 'Contact & inquiries']
                                ] as $content)
                                    <div class="text-center p-6 bg-unik-light rounded-lg border border-unik-border">
                                        <div class="w-16 h-16 rounded-full bg-unik-secondary/10 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas {{ $content['icon'] }} text-2xl text-unik-secondary"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark mb-2">{{ $content['title'] }}</h4>
                                        <p class="text-sm text-unik-muted">{{ $content['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6 mb-6">
                                <h4 class="font-semibold text-unik-dark mb-4">By submitting content, you:</h4>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-unik-secondary/20 flex items-center justify-center mt-1">
                                            <i class="fas fa-check text-unik-secondary text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Confirm ownership or rights</p>
                                            <p class="text-sm text-unik-muted">You own or have permission to share the content</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-unik-secondary/20 flex items-center justify-center mt-1">
                                            <i class="fas fa-check text-unik-secondary text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Grant license to display</p>
                                            <p class="text-sm text-unik-muted">Non-exclusive, worldwide license for us to display your content</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-unik-secondary/20 flex items-center justify-center mt-1">
                                            <i class="fas fa-check text-unik-secondary text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Accept responsibility</p>
                                            <p class="text-sm text-unik-muted">You are fully responsible for the content you submit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-red-50 border-l-4 border-status-error pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-times-circle text-status-error text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Content Removal Rights</h4>
                                        <p class="text-unik-muted mb-0">
                                            We reserve the right to remove any content that is offensive, abusive, defamatory, 
                                            spam, misleading, or in violation of these Terms.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Intellectual Property -->
                <section id="intellectual-property" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-copyright text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">4. Intellectual Property</h2>
                                <p class="text-unik-muted">Ownership and usage rights</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="mb-6">All content on this website, including but not limited to:</p>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-4">
                                    @foreach(['Text & articles', 'Logos & branding', 'Graphics & images', 'Design elements'] as $item)
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-unik-secondary/10 flex items-center justify-center">
                                                <i class="fas fa-file-alt text-unik-secondary"></i>
                                            </div>
                                            <span class="font-medium">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="space-y-4">
                                    @foreach(['Code & layout', 'Database structure', 'User interface', 'Trade secrets'] as $item)
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-unik-secondary/10 flex items-center justify-center">
                                                <i class="fas fa-code text-unik-secondary"></i>
                                            </div>
                                            <span class="font-medium">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-unik-light to-white rounded-lg p-6 border border-unik-secondary/20 mb-6">
                                <h4 class="font-semibold text-unik-dark mb-4">Protection & Usage</h4>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-unik-muted mb-2">This content is:</p>
                                        <ul class="space-y-2">
                                            @foreach([
                                                'Property of Unik Muse',
                                                'Protected by copyright laws',
                                                'Protected by IP laws'
                                            ] as $protection)
                                                <li class="flex items-center gap-2">
                                                    <i class="fas fa-shield-alt text-unik-secondary text-sm"></i>
                                                    <span>{{ $protection }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm text-unik-muted mb-2">You may not:</p>
                                        <ul class="space-y-2">
                                            @foreach([
                                                'Reproduce content',
                                                'Distribute content',
                                                'Modify content'
                                            ] as $restriction)
                                                <li class="flex items-center gap-2">
                                                    <i class="fas fa-ban text-status-error text-sm"></i>
                                                    <span>{{ $protection }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info bg-blue-50 border-l-4 border-status-info pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-status-info text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-unik-muted mb-0">
                                            <strong>Exception:</strong> You may use content as permitted by applicable fair use laws 
                                            or with prior written permission from Unik Muse.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Third-Party Links -->
                <section id="third-party-links" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-link text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">5. Third-Party Links</h2>
                                <p class="text-unik-muted">External websites and resources</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-external-link-alt text-6xl text-unik-secondary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">
                                        This website may contain links to third-party websites for your convenience and reference.
                                    </p>
                                    <div class="bg-unik-light rounded-lg p-6">
                                        <p class="font-medium mb-2">Important Disclaimer:</p>
                                        <p class="text-sm text-unik-muted mb-0">
                                            We do not control or endorse the content, privacy policies, or practices of any third-party sites 
                                            and assume no responsibility for them. Accessing third-party links is at your own risk.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Disclaimer -->
                <section id="disclaimer" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">6. Disclaimer</h2>
                                <p class="text-unik-muted">Limitations of content and service</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="mb-6">The content on this website is provided "as is" and "as available" without warranties of any kind.</p>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-unik-light rounded-lg p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-status-warning/20 flex items-center justify-center">
                                            <i class="fas fa-times-circle text-status-warning"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark">No Guarantees</h4>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach([
                                            'Accuracy or reliability of content',
                                            'Availability or uninterrupted operation',
                                            'Fitness for any particular purpose',
                                            'Freedom from errors or bugs',
                                            'Timeliness of information'
                                        ] as $item)
                                            <li class="flex items-start">
                                                <i class="fas fa-minus text-status-warning mr-2 mt-1"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <div class="bg-unik-light rounded-lg p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-unik-secondary/20 flex items-center justify-center">
                                            <i class="fas fa-info-circle text-unik-secondary"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark">Content Nature</h4>
                                    </div>
                                    <p class="text-sm text-unik-muted mb-4">
                                        Content is for informational purposes only and does not constitute professional advice.
                                    </p>
                                    <div class="space-y-2">
                                        @foreach([
                                            'Not financial advice',
                                            'Not legal advice',
                                            'Not medical advice',
                                            'Educational content only'
                                        ] as $disclaimer)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-asterisk text-unik-secondary text-xs"></i>
                                                <span class="text-sm">{{ $disclaimer }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Limitation of Liability -->
                <section id="liability" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-balance-scale text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">7. Limitation of Liability</h2>
                                <p class="text-unik-muted">Legal responsibilities and limits</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="bg-red-50 border border-red-100 rounded-lg p-6 mb-6">
                                <div class="flex items-start gap-4">
                                    <i class="fas fa-gavel text-3xl text-status-error mt-1"></i>
                                    <div>
                                        <h4 class="font-bold text-unik-dark mb-3">Important Legal Notice</h4>
                                        <p class="mb-4">
                                            To the fullest extent permitted by law, <span class="font-semibold">Unik Muse</span> shall not be liable for:
                                        </p>
                                        <div class="space-y-3">
                                            @foreach([
                                                'Any direct or indirect damages',
                                                'Loss of data, profits, or business',
                                                'Issues arising from use or inability to use the website',
                                                'Damages from third-party content',
                                                'Technical issues or downtime'
                                            ] as $liability)
                                                <div class="flex items-start gap-2">
                                                    <i class="fas fa-times text-status-error mt-1"></i>
                                                    <span>{{ $liability }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="mb-0 font-medium">
                                            Your use of the website is at your sole risk. You are responsible for any actions taken based on content.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Termination -->
                <section id="termination" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-ban text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">8. Termination</h2>
                                <p class="text-unik-muted">Account and access termination</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <p class="mb-6">We reserve the right to:</p>
                            
                            <div class="grid md:grid-cols-3 gap-6 mb-8">
                                @foreach([
                                    ['icon' => 'fa-user-slash', 'title' => 'Restrict Access', 'desc' => 'Limit website access'],
                                    ['icon' => 'fa-trash-alt', 'title' => 'Remove Content', 'desc' => 'Delete violating content'],
                                    ['icon' => 'fa-pause-circle', 'title' => 'Suspend Users', 'desc' => 'Temporary account suspension']
                                ] as $action)
                                    <div class="text-center p-6 bg-unik-light rounded-lg border border-unik-border">
                                        <div class="w-16 h-16 rounded-full bg-status-error/10 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas {{ $action['icon'] }} text-2xl text-status-error"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark mb-2">{{ $action['title'] }}</h4>
                                        <p class="text-sm text-unik-muted">{{ $action['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-unik-secondary text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Termination Conditions</h4>
                                        <p class="text-unik-muted mb-0">
                                            These actions may be taken at our sole discretion, without prior notice, 
                                            if these Terms are violated. We may also permanently ban users for serious violations.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Privacy Policy -->
                <section id="privacy" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">9. Privacy</h2>
                                <p class="text-unik-muted">Data protection and privacy</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="flex items-center gap-6 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-user-lock text-5xl text-unik-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">
                                        Your use of this website is also governed by our Privacy Policy, which explains 
                                        how we collect, use, protect, and disclose personal information.
                                    </p>
                                    <a href="{{ route('privacy.policy') }}" 
                                       class="inline-flex items-center bg-unik-primary text-white px-6 py-3 rounded-lg hover:bg-unik-primary/90 transition-colors">
                                        <i class="fas fa-file-contract mr-2"></i> View Privacy Policy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Changes to Terms -->
                <section id="changes" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-sync-alt text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">10. Changes to Terms</h2>
                                <p class="text-unik-muted">Updates and modifications</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="flex items-start gap-6">
                                <div class="text-center">
                                    <i class="fas fa-history text-5xl text-unik-secondary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">We may update these Terms & Conditions at any time.</p>
                                    <div class="bg-unik-light rounded-lg p-6">
                                        <div class="space-y-4">
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-bell text-unik-secondary mt-1"></i>
                                                <div>
                                                    <p class="font-medium mb-1">Effective Date</p>
                                                    <p class="text-sm text-unik-muted">Changes will be effective immediately upon posting on this page</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-calendar-check text-unik-secondary mt-1"></i>
                                                <div>
                                                    <p class="font-medium mb-1">Continued Use</p>
                                                    <p class="text-sm text-unik-muted">Continued use of the website constitutes acceptance of updated Terms</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <i class="fas fa-eye text-unik-secondary mt-1"></i>
                                                <div>
                                                    <p class="font-medium mb-1">Your Responsibility</p>
                                                    <p class="text-sm text-unik-muted">You should review these Terms periodically for changes</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Governing Law -->
                <section id="governing-law" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-gavel text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">11. Governing Law</h2>
                                <p class="text-unik-muted">Legal jurisdiction</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-center gap-6">
                                    <div class="text-center">
                                        <i class="fas fa-balance-scale-left text-5xl text-unik-secondary"></i>
                                    </div>
                                    <div>
                                        <p class="mb-4">
                                            These Terms shall be governed and interpreted in accordance with the laws of 
                                            <span class="font-semibold">[Your Country/State]</span>, without regard to conflict of law principles.
                                        </p>
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div class="bg-white rounded-lg p-4">
                                                <p class="text-sm font-medium text-unik-muted mb-1">Applicable Law</p>
                                                <p class="font-semibold">Local Jurisdiction</p>
                                            </div>
                                            <div class="bg-white rounded-lg p-4">
                                                <p class="text-sm font-medium text-unik-muted mb-1">Dispute Resolution</p>
                                                <p class="font-semibold">Court Jurisdiction</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact Information -->
                <section id="contact" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center">
                                <i class="fas fa-envelope text-xl text-unik-secondary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">12. Contact Information</h2>
                                <p class="text-unik-muted">How to reach us</p>
                            </div>
                        </div>
                        
                        <div class="terms-section">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Get in Touch</h3>
                                    
                                    <div class="space-y-6">
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-envelope text-unik-secondary"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Email</p>
                                                <a href="mailto:{{ config('app.email', 'legal@unikmuse.com') }}" 
                                                   class="font-semibold text-unik-secondary hover:text-unik-primary transition-colors">
                                                    {{ config('app.email', 'legal@unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-secondary/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-globe text-unik-secondary"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Website</p>
                                                <a href="{{ config('app.url', url('/')) }}" 
                                                   class="font-semibold text-unik-secondary hover:text-unik-primary transition-colors">
                                                    {{ config('app.url', 'unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Response Information</h3>
                                    <p class="mb-4">We strive to respond to all legal inquiries within 5-7 business days.</p>
                                    
                                    <div class="bg-unik-light rounded-lg p-4">
                                        <p class="text-sm text-unik-muted mb-0">
                                            <i class="fas fa-lightbulb text-unik-secondary mr-2"></i>
                                            For legal notices, please include "Legal Notice" in the subject line.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Terms Footer -->
                <footer class="mt-12 pt-8 border-t border-unik-border">
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-unik-light rounded-lg p-6">
                            <h4 class="font-semibold text-unik-dark mb-4">Document Information</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-unik-muted">Version</span>
                                    <span class="font-medium">1.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-unik-muted">Last Updated</span>
                                    <span class="font-medium">{{ date('F d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-unik-muted">Effective Date</span>
                                    <span class="font-medium">{{ date('F d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- <div class="bg-unik-light rounded-lg p-6">
                            <h4 class="font-semibold text-unik-dark mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('privacy.policy') }}" class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-unik-border transition-colors">
                                    <span>Privacy Policy</span>
                                    <i class="fas fa-chevron-right text-unik-muted"></i>
                                </a>
                                <button onclick="window.print()" class="w-full flex items-center justify-between p-3 bg-white rounded-lg hover:bg-unik-border transition-colors">
                                    <span>Print This Page</span>
                                    <i class="fas fa-print text-unik-muted"></i>
                                </button>
                            </div>
                        </div> --}}
                    </div>
                    
                    <div class="text-center">
                        <p class="text-unik-muted mb-6">
                            <small>
                                This Terms & Conditions agreement is effective as of {{ date('F d, Y') }}.
                            </small>
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-outline inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Home
                        </a>
                    </div>
                </footer>
            </div>
        </div>
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

        // Table of Contents active link highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const tocItems = document.querySelectorAll('.toc-item');
            const sections = document.querySelectorAll('section[id]');
            
            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -70% 0px',
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        tocItems.forEach(item => {
                            item.classList.remove('bg-unik-secondary/5', 'border', 'border-unik-secondary/20');
                            if (item.getAttribute('href') === `#${id}`) {
                                item.classList.add('bg-unik-secondary/5', 'border', 'border-unik-secondary/20');
                            }
                        });
                    }
                });
            }, observerOptions);
            
            sections.forEach(section => observer.observe(section));
            
            // Smooth scroll for TOC links
            tocItems.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = link.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const offset = 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - offset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

