<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | Unik Muse</title>
    <meta name="description"
        content="Read our comprehensive Privacy Policy to understand how we collect, use, and protect your personal information on Unik Muse.">
    <meta name="keywords" content="privacy policy, data protection, GDPR, user data, cookies, personal information">
    <meta property="og:title" content="Privacy Policy - Unik Muse">
    <meta property="og:description"
        content="Learn how we protect your privacy and handle your personal information on Unik Muse.">
    <meta property="og:image" content="{{ asset('assets/snow.webp') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Privacy Policy",
        "description": "Privacy Policy for Unik Muse Blog",
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
            "name": "Privacy Policy"
        }]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .privacy-gradient {
            background: linear-gradient(135deg, var(--color-unik-primary) 0%, var(--color-unik-secondary) 100%);
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
            background: linear-gradient(90deg, var(--color-unik-primary), var(--color-unik-secondary));
            width: 0%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            transition: width 0.3s ease;
        }

        .policy-section {
            border-left: 4px solid var(--color-unik-primary);
            padding-left: 1.5rem;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body class="bg-unik-light text-unik-dark">
    <!-- Reading Progress Bar -->
    <div class="progress-bar" id="readingProgress"></div>

    @include('common.header')

    <main class="container-unik py-8 md:py-12">
        <!-- Privacy Policy Hero Section -->
        <section class="mb-12 fade-in">
            <div class="privacy-gradient rounded-unik-xl text-white p-8 md:p-12 relative overflow-hidden">
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
                            <i class="fas fa-shield-alt mr-2"></i> Legal
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-file-contract mr-2"></i> Policy Document
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">
                        Privacy Policy
                    </h1>

                    <p class="text-xl opacity-90 max-w-3xl mx-auto mb-8">
                        Your privacy matters. Learn how we protect and handle your personal information.
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
                            <div class="text-2xl font-bold">8</div>
                            <div class="text-sm opacity-80">Key Rights</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Table of Contents -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border sticky-toc">
                    <h3 class="text-lg font-semibold text-unik-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-list-ol text-unik-primary"></i> Contents
                    </h3>
                    <nav class="space-y-2">
                        @php
                            $sections = [
                                ['id' => 'introduction', 'title' => 'Introduction & Welcome', 'icon' => 'fa-home'],
                                ['id' => 'information-collected', 'title' => 'Information We Collect', 'icon' => 'fa-database'],
                                ['id' => 'user-content', 'title' => 'User-Generated Content', 'icon' => 'fa-edit'],
                                ['id' => 'use-of-information', 'title' => 'How We Use Information', 'icon' => 'fa-cogs'],
                                ['id' => 'cookies', 'title' => 'Cookies & Tracking', 'icon' => 'fa-cookie-bite'],
                                ['id' => 'third-party', 'title' => 'Third-Party Services', 'icon' => 'fa-share-alt'],
                                ['id' => 'data-security', 'title' => 'Data Security', 'icon' => 'fa-shield-alt'],
                                ['id' => 'data-retention', 'title' => 'Data Retention', 'icon' => 'fa-history'],
                                ['id' => 'your-rights', 'title' => 'Your Rights', 'icon' => 'fa-user-check'],
                                ['id' => 'children', 'title' => 'Children\'s Information', 'icon' => 'fa-child'],
                                ['id' => 'policy-changes', 'title' => 'Policy Changes', 'icon' => 'fa-sync'],
                                ['id' => 'contact', 'title' => 'Contact Information', 'icon' => 'fa-envelope'],
                            ];
                        @endphp
                        @foreach ($sections as $section)
                            <a href="#{{ $section['id'] }}" 
                               class="toc-item flex items-center p-3 rounded-lg hover:bg-unik-light transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-unik-primary/10 flex items-center justify-center mr-3 group-hover:bg-unik-primary/20">
                                    <i class="fas {{ $section['icon'] }} text-unik-primary text-sm"></i>
                                </div>
                                <span class="font-medium text-unik-dark group-hover:text-unik-primary transition-colors">
                                    {{ $section['title'] }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                    
                    <!-- Last Updated -->
                    <div class="mt-8 pt-6 border-t border-unik-border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-unik-dark">Last Updated</p>
                                <p class="text-sm text-unik-muted">{{ date('F d, Y') }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-unik-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Introduction -->
                <section id="introduction" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-home text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Introduction & Welcome</h2>
                                <p class="text-unik-muted">Last updated: {{ date('F d, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="text-lg leading-relaxed mb-6">
                                Welcome to <span class="font-bold text-unik-primary">Unik Muse</span> ("we", "our", "us"). 
                                Your privacy is important to us. This Privacy Policy explains how we collect, use, protect, 
                                and disclose information when you visit or interact with our website.
                            </p>
                            
                            <div class="alert alert-info bg-unik-light border-l-4 border-unik-primary pl-4 py-3 mb-6">
                                <p class="mb-0">
                                    <i class="fas fa-info-circle text-unik-primary mr-2"></i>
                                    By using this website, you agree to the practices described in this Privacy Policy.
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-unik-light rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-eye text-unik-primary"></i>
                                        <h4 class="font-semibold text-unik-dark">Transparency</h4>
                                    </div>
                                    <p class="text-sm text-unik-muted">We believe in clear, straightforward privacy practices.</p>
                                </div>
                                <div class="bg-unik-light rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-lock text-unik-primary"></i>
                                        <h4 class="font-semibold text-unik-dark">Security</h4>
                                    </div>
                                    <p class="text-sm text-unik-muted">Your data is protected with industry-standard measures.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Information We Collect -->
                <section id="information-collected" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-database text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Information We Collect</h2>
                                <p class="text-unik-muted">What data we gather and why</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-6">We may collect the following types of information:</p>
                            
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                <!-- Personal Information -->
                                <div class="bg-gradient-to-br from-unik-light to-white rounded-lg p-6 border border-unik-border">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-unik-primary/20 flex items-center justify-center">
                                            <i class="fas fa-user text-unik-primary"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-unik-dark">Personal Information</h3>
                                    </div>
                                    <p class="text-sm text-unik-muted mb-4">You may voluntarily provide:</p>
                                    <ul class="space-y-3">
                                        @foreach(['Name', 'Email address', 'Contact information', 'Content submitted through forms, comments, or messages'] as $item)
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-status-success mr-2 mt-1"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <!-- Non-Personal Information -->
                                <div class="bg-gradient-to-br from-unik-light to-white rounded-lg p-6 border border-unik-border">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-unik-secondary/20 flex items-center justify-center">
                                            <i class="fas fa-chart-line text-unik-secondary"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-unik-dark">Non-Personal Information</h3>
                                    </div>
                                    <p class="text-sm text-unik-muted mb-4">We automatically collect:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['IP address', 'Browser type', 'Device info', 'Pages visited', 'Time spent', 'Referring URLs'] as $item)
                                            <span class="inline-flex items-center px-3 py-1 bg-unik-light text-unik-dark rounded-full text-xs">
                                                {{ $item }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 p-3 bg-unik-primary/5 rounded-lg">
                                        <p class="text-sm text-unik-muted mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            This data is used for analytics and site optimization purposes only.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- User-Generated Content -->
                <section id="user-content" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-edit text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">User-Generated Content</h2>
                                <p class="text-unik-muted">What you share and how it's handled</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-6">Our blog allows users to:</p>
                            
                            <div class="grid md:grid-cols-3 gap-6 mb-8">
                                @foreach([
                                    ['icon' => 'fa-file-alt', 'title' => 'Publish Content', 'color' => 'unik-primary'],
                                    ['icon' => 'fa-comment-alt', 'title' => 'Post Comments', 'color' => 'unik-secondary'],
                                    ['icon' => 'fa-envelope', 'title' => 'Submit Feedback', 'color' => 'unik-accent']
                                ] as $action)
                                    <div class="text-center p-6 bg-white rounded-lg border border-unik-border hover-lift transition-all">
                                        <div class="w-16 h-16 rounded-full bg-{{ $action['color'] }}/10 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas {{ $action['icon'] }} text-2xl text-{{ $action['color'] }}"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark">{{ $action['title'] }}</h4>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-1">Important Note</h4>
                                        <p class="text-unik-muted mb-0">
                                            Any content you voluntarily publish on the site may be publicly visible.
                                            We are not responsible for personal information you choose to share publicly.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- How We Use Information -->
                <section id="use-of-information" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-cogs text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">How We Use Your Information</h2>
                                <p class="text-unik-muted">Purposes of data processing</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-6">We use collected information for the following purposes:</p>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach([
                                    'To operate and maintain the website',
                                    'To improve content quality and user experience',
                                    'To respond to inquiries or support requests',
                                    'To monitor website usage and performance',
                                    'To prevent spam, fraud, or abuse',
                                    'To comply with legal obligations'
                                ] as $purpose)
                                    <div class="flex items-start p-4 bg-unik-light rounded-lg">
                                        <div class="w-8 h-8 rounded-full bg-unik-primary/20 flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-unik-primary text-sm"></i>
                                        </div>
                                        <span>{{ $purpose }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Cookies & Tracking -->
                <section id="cookies" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-cookie-bite text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Cookies & Tracking Technologies</h2>
                                <p class="text-unik-muted">How we use cookies and similar technologies</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-cookie text-6xl text-unik-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">We use cookies and similar technologies to:</p>
                                    <ul class="space-y-3">
                                        @foreach(['Remember user preferences', 'Analyze website traffic', 'Improve performance and functionality'] as $use)
                                            <li class="flex items-center">
                                                <i class="fas fa-cookie text-unik-primary mr-3"></i>
                                                {{ $use }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-gear text-unik-primary text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Cookie Settings</h4>
                                        <p class="text-unik-muted mb-0">
                                            You can disable cookies through your browser settings. Disabling cookies may affect certain features of the website.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Third-Party Services -->
                <section id="third-party" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-share-alt text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Third-Party Services</h2>
                                <p class="text-unik-muted">External services we work with</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-4">We may use third-party services such as:</p>
                            
                            <div class="flex flex-wrap gap-3 mb-6">
                                @foreach(['Analytics providers', 'Advertising networks', 'Content delivery networks (CDNs)', 'Social media platforms', 'Email services'] as $service)
                                    <span class="inline-flex items-center px-4 py-2 bg-unik-secondary/10 text-unik-secondary rounded-lg">
                                        {{ $service }}
                                    </span>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-warning bg-orange-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-circle text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-unik-muted mb-0">
                                            These third parties may collect information in accordance with their own privacy policies.
                                            We do not control how third-party services handle your data.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Data Security -->
                <section id="data-security" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Data Security</h2>
                                <p class="text-unik-muted">How we protect your information</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-lock text-6xl text-unik-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">We take reasonable technical and organizational measures to protect your information from:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @foreach(['Unauthorized access', 'Alteration', 'Disclosure', 'Destruction'] as $threat)
                                            <span class="inline-flex items-center justify-center px-3 py-2 bg-status-error/10 text-status-error rounded-lg text-sm font-medium">
                                                {{ $threat }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-unik-primary text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-unik-muted mb-0">
                                            However, no method of transmission over the Internet is 100% secure. 
                                            We cannot guarantee absolute security.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Data Retention -->
                <section id="data-retention" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-history text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Data Retention</h2>
                                <p class="text-unik-muted">How long we keep your information</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-6">We retain personal information only for as long as necessary:</p>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-unik-light rounded-lg p-6 border border-unik-border text-center">
                                    <div class="w-16 h-16 rounded-full bg-unik-primary/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-check-circle text-2xl text-status-success"></i>
                                    </div>
                                    <h4 class="font-semibold text-unik-dark mb-2">To fulfill purposes</h4>
                                    <p class="text-sm text-unik-muted">Outlined in this policy</p>
                                </div>
                                <div class="bg-unik-light rounded-lg p-6 border border-unik-border text-center">
                                    <div class="w-16 h-16 rounded-full bg-unik-primary/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-contract text-2xl text-status-success"></i>
                                    </div>
                                    <h4 class="font-semibold text-unik-dark mb-2">Legal compliance</h4>
                                    <p class="text-sm text-unik-muted">And regulatory requirements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Your Rights -->
                <section id="your-rights" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-user-check text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Your Rights</h2>
                                <p class="text-unik-muted">Control over your personal data</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <p class="mb-6">Depending on your location, you may have the right to:</p>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-8">
                                <div class="bg-gradient-to-br from-unik-primary/5 to-unik-secondary/5 rounded-lg p-6 border border-unik-primary/20 text-center hover-lift transition-all">
                                    <div class="w-16 h-16 rounded-full bg-unik-primary/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-eye text-2xl text-unik-primary"></i>
                                    </div>
                                    <h4 class="font-semibold text-unik-dark mb-2">Access your data</h4>
                                    <p class="text-sm text-unik-muted">Request a copy of your information</p>
                                </div>
                                <div class="bg-gradient-to-br from-unik-primary/5 to-unik-secondary/5 rounded-lg p-6 border border-unik-primary/20 text-center hover-lift transition-all">
                                    <div class="w-16 h-16 rounded-full bg-unik-primary/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-pencil-alt text-2xl text-unik-primary"></i>
                                    </div>
                                    <h4 class="font-semibold text-unik-dark mb-2">Request correction</h4>
                                    <p class="text-sm text-unik-muted">Update or remove your information</p>
                                </div>
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-envelope text-unik-primary text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-unik-muted mb-0">
                                            To exercise these rights, please contact us using the details provided in the Contact Information section.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Children's Information -->
                <section id="children" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-child text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Children's Information</h2>
                                <p class="text-unik-muted">Our policy regarding children's data</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-6">
                                <div class="flex flex-col md:flex-row items-start gap-6">
                                    <div class="text-center">
                                        <i class="fas fa-shield-exclamation text-5xl text-status-warning"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-semibold text-unik-dark mb-3">Not Intended for Children</h4>
                                        <p class="mb-3">Our website is not intended for children under the age of 13.</p>
                                        <p class="mb-0">
                                            We do not knowingly collect personal information from children. 
                                            If you believe your child has provided personal information, please contact us immediately.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Policy Changes -->
                <section id="policy-changes" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-sync text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Policy Changes</h2>
                                <p class="text-unik-muted">How we update this policy</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <div class="flex items-start gap-6">
                                <div class="text-center">
                                    <i class="fas fa-clock text-5xl text-unik-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-4">We reserve the right to update or modify this Privacy Policy at any time.</p>
                                    <div class="bg-unik-light rounded-lg p-6">
                                        <p class="text-unik-muted mb-0">
                                            Changes will be posted on this page with an updated "Last updated" date.
                                            Continued use of the website after changes indicates acceptance of the updated policy.
                                        </p>
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
                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center">
                                <i class="fas fa-envelope text-xl text-unik-primary"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">Contact Information</h2>
                                <p class="text-unik-muted">How to reach us</p>
                            </div>
                        </div>
                        
                        <div class="policy-section">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Get in Touch</h3>
                                    
                                    <div class="space-y-6">
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-envelope text-unik-primary"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Email</p>
                                                <a href="mailto:{{ config('app.email', 'privacy@unikmuse.com') }}" 
                                                   class="font-semibold text-unik-primary hover:text-unik-secondary transition-colors">
                                                    {{ config('app.email', 'privacy@unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-primary/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-globe text-unik-primary"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Website</p>
                                                <a href="{{ config('app.url', url('/')) }}" 
                                                   class="font-semibold text-unik-primary hover:text-unik-secondary transition-colors">
                                                    {{ config('app.url', 'unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Response Time</h3>
                                    <p class="mb-4">We strive to respond to all privacy-related inquiries within 48 hours during business days.</p>
                                    
                                    <div class="bg-unik-light rounded-lg p-4">
                                        <p class="text-sm text-unik-muted mb-0">
                                            <i class="fas fa-lightbulb text-unik-primary mr-2"></i>
                                            For data-related requests, please include "Privacy Request" in the subject line.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Policy Footer -->
                <footer class="mt-12 pt-8 border-t border-unik-border text-center">
                    
                    <p class="text-unik-muted mb-6">
                        <small>
                            This Privacy Policy is effective as of {{ date('F d, Y') }}.
                        </small>
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-outline inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Home
                    </a>
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
                            item.classList.remove('bg-unik-primary/5', 'border', 'border-unik-primary/20');
                            if (item.getAttribute('href') === `#${id}`) {
                                item.classList.add('bg-unik-primary/5', 'border', 'border-unik-primary/20');
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
