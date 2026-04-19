<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disclaimer | Unik Muse</title>
    <meta name="description"
        content="Read our Disclaimer to understand the limitations and scope of information provided on Unik Muse. Learn about content accuracy, professional advice, and liability.">
    <meta name="keywords" content="disclaimer, legal disclaimer, content warning, liability limitation, information accuracy">
    <meta property="og:title" content="Disclaimer - Unik Muse">
    <meta property="og:description"
        content="Important Disclaimer for Unik Muse content. Understand the scope and limitations of information provided.">
    <meta property="og:image" content="{{ asset('assets/snow.webp') }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Disclaimer",
        "description": "Disclaimer for Unik Muse Blog",
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
            "name": "Disclaimer"
        }]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .disclaimer-gradient {
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
            background: linear-gradient(90deg, var(--color-unik-accent), var(--color-unik-light));
            width: 0%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            transition: width 0.3s ease;
        }

        .disclaimer-section {
            border-left: 4px solid var(--color-unik-accent);
            padding-left: 1.5rem;
            margin-left: 0.5rem;
        }

        .warning-badge {
            background: linear-gradient(135deg, var(--color-status-warning), var(--color-status-error));
            color: white;
        }

        .disclaimer-note {
            border: 3px solid var(--color-status-warning);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(220, 38, 38, 0.05));
        }
    </style>
</head>

<body class="bg-unik-light text-unik-dark">
    <!-- Reading Progress Bar -->
    <div class="progress-bar" id="readingProgress"></div>

    @include('common.header')

    <main class="container-unik py-8 md:py-12">
        <!-- Disclaimer Hero Section -->
        <section class="mb-12 fade-in">
            <div class="disclaimer-gradient rounded-unik-xl text-unik-dark p-8 md:p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-32 translate-x-32">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-24 -translate-x-24">
                    </div>
                </div>

                <div class="relative z-10 text-center">
                    <div class="inline-flex items-center gap-3 mb-6">
                        <span class="warning-badge backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Important Notice
                        </span>
                        <span class="bg-white/30 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-file-alt mr-2"></i> Legal Document
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-4 font-serif">
                        Disclaimer
                    </h1>

                    <p class="text-xl opacity-90 max-w-3xl mx-auto mb-8">
                        Please read this disclaimer carefully before using our website
                    </p>

                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ date('Y') }}</div>
                            <div class="text-sm opacity-80">Last Updated</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">9</div>
                            <div class="text-sm opacity-80">Sections</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">⚠️</div>
                            <div class="text-sm opacity-80">Important Notice</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Critical Notice -->
        <div class="disclaimer-note rounded-unik-lg p-6 mb-8 fade-in">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-status-error/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-2xl text-status-error"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-unik-dark mb-2">Critical Legal Notice</h3>
                    <p class="text-unik-muted mb-3">
                        The information provided on <span class="font-semibold text-unik-dark">Unik Muse</span> is for general informational purposes only. 
                        By using this website, you agree to the terms of this Disclaimer.
                    </p>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-check text-status-error"></i>
                            Effective: {{ date('F d, Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-user-check text-status-error"></i>
                            User Acceptance Required
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
                        <i class="fas fa-list-ol text-unik-accent"></i> Quick Navigation
                    </h3>
                    <nav class="space-y-2">
                        @php
                            $sections = [
                                ['id' => 'general', 'title' => 'General Information', 'icon' => 'fa-info-circle'],
                                ['id' => 'professional', 'title' => 'Professional Advice', 'icon' => 'fa-user-md'],
                                ['id' => 'user-content', 'title' => 'User Content', 'icon' => 'fa-users'],
                                ['id' => 'external-links', 'title' => 'External Links', 'icon' => 'fa-external-link-alt'],
                                ['id' => 'errors', 'title' => 'Errors & Omissions', 'icon' => 'fa-bug'],
                                ['id' => 'fair-use', 'title' => 'Fair Use', 'icon' => 'fa-balance-scale'],
                                ['id' => 'liability', 'title' => 'Liability', 'icon' => 'fa-gavel'],
                                ['id' => 'consent', 'title' => 'Consent', 'icon' => 'fa-check-double'],
                                ['id' => 'contact', 'title' => 'Contact', 'icon' => 'fa-envelope'],
                            ];
                        @endphp
                        @foreach ($sections as $section)
                            <a href="#{{ $section['id'] }}" 
                               class="toc-item flex items-center p-3 rounded-lg hover:bg-unik-light transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-unik-accent/10 flex items-center justify-center mr-3 group-hover:bg-unik-accent/20">
                                    <i class="fas {{ $section['icon'] }} text-unik-accent text-sm"></i>
                                </div>
                                <span class="font-medium text-unik-dark group-hover:text-unik-accent transition-colors">
                                    {{ $section['title'] }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                    
                    <!-- Related Documents -->
                    <div class="mt-8 pt-6 border-t border-unik-border">
                        <h4 class="text-sm font-semibold text-unik-muted mb-3">Related Documents</h4>
                        <div class="space-y-2">
                            <a href="{{ route('terms.conditions') }}" class="flex items-center gap-2 text-sm p-2 rounded hover:bg-unik-light">
                                <i class="fas fa-file-contract text-unik-primary"></i>
                                <span>Terms & Conditions</span>
                            </a>
                            <a href="{{ route('privacy.policy') }}" class="flex items-center gap-2 text-sm p-2 rounded hover:bg-unik-light">
                                <i class="fas fa-shield-alt text-unik-primary"></i>
                                <span>Privacy Policy</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3 space-y-8">
                <!-- General Information Disclaimer -->
                <section id="general" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-info-circle text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">1. General Information Disclaimer</h2>
                                <p class="text-unik-muted">Last updated: {{ date('F d, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <p class="text-lg leading-relaxed mb-6">
                                All content published on this website is provided in good faith and for general information purposes only.
                            </p>
                            
                            <div class="bg-unik-light rounded-lg p-6 mb-6">
                                <h4 class="font-semibold text-unik-dark mb-4">We make no representations or warranties of any kind regarding:</h4>
                                <div class="grid md:grid-cols-2 gap-4">
                                    @foreach(['Accuracy', 'Reliability', 'Completeness', 'Availability', 'Timeliness', 'Suitability'] as $item)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-status-warning/20 flex items-center justify-center">
                                                <i class="fas fa-times text-status-warning text-sm"></i>
                                            </div>
                                            <span class="font-medium">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-red-50 border-l-4 border-status-error pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation text-status-error text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="mb-0 font-medium">
                                            Any action you take upon the information found on this website is strictly at your own risk.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Professional Disclaimer -->
                <section id="professional" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-user-md text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">2. Professional Disclaimer</h2>
                                <p class="text-unik-muted">Scope of information provided</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <p class="mb-6">The content on this website does not constitute professional advice, including but not limited to:</p>
                            
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                @foreach([
                                    ['icon' => 'fa-balance-scale', 'title' => 'Legal Advice', 'color' => 'status-error'],
                                    ['icon' => 'fa-chart-line', 'title' => 'Financial Advice', 'color' => 'status-success'],
                                    ['icon' => 'fa-heartbeat', 'title' => 'Medical Advice', 'color' => 'status-error'],
                                    ['icon' => 'fa-cogs', 'title' => 'Technical Advice', 'color' => 'unik-primary']
                                ] as $advice)
                                    <div class="bg-white rounded-lg border-2 border-{{ $advice['color'] }}/20 p-6 text-center hover-lift">
                                        <div class="w-16 h-16 rounded-full bg-{{ $advice['color'] }}/10 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas {{ $advice['icon'] }} text-2xl text-{{ $advice['color'] }}"></i>
                                        </div>
                                        <h4 class="font-semibold text-unik-dark mb-2">{{ $advice['title'] }}</h4>
                                        <p class="text-sm text-unik-muted">Not professional consultation</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="bg-gradient-to-r from-status-warning/5 to-status-error/5 rounded-lg p-6 border border-status-warning/20">
                                <div class="flex items-start gap-4">
                                    <i class="fas fa-user-shield text-3xl text-status-warning mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Important Warning</h4>
                                        <p class="text-unik-muted mb-0">
                                            You should consult a qualified professional before making decisions based on the information provided on this website.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- User-Generated Content Disclaimer -->
                <section id="user-content" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-users text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">3. User-Generated Content Disclaimer</h2>
                                <p class="text-unik-muted">User submissions and opinions</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-comment-dots text-6xl text-unik-accent"></i>
                                </div>
                                <div>
                                    <p class="mb-4">
                                        This website may allow users to submit content, including posts, comments, and other materials.
                                    </p>
                                    
                                    <div class="space-y-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-full bg-unik-accent/10 flex items-center justify-center mt-1">
                                                <i class="fas fa-comment text-unik-accent"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-unik-dark">Opinions expressed by users are their own</p>
                                                <p class="text-sm text-unik-muted">We do not endorse user opinions</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-full bg-unik-accent/10 flex items-center justify-center mt-1">
                                                <i class="fas fa-shield-alt text-unik-accent"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-unik-dark">We are not responsible for user-submitted content</p>
                                                <p class="text-sm text-unik-muted">Users are solely responsible for their submissions</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-full bg-unik-accent/10 flex items-center justify-center mt-1">
                                                <i class="fas fa-trash-alt text-unik-accent"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-unik-dark">We reserve the right to remove content</p>
                                                <p class="text-sm text-unik-muted">Content violating policies or laws may be removed</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- External Links Disclaimer -->
                <section id="external-links" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-external-link-alt text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">4. External Links Disclaimer</h2>
                                <p class="text-unik-muted">Third-party website references</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="bg-unik-light rounded-lg p-6 mb-6">
                                <div class="flex items-center gap-6">
                                    <div class="text-center">
                                        <i class="fas fa-link text-5xl text-unik-accent"></i>
                                    </div>
                                    <div>
                                        <p class="mb-4">
                                            This website may contain links to external or third-party websites for your convenience.
                                        </p>
                                        <div class="space-y-3">
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-ban text-status-error mt-1"></i>
                                                <span>We do not control, monitor, or guarantee external content</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-ban text-status-error mt-1"></i>
                                                <span>We are not responsible for third-party website content</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-exclamation-triangle text-status-warning mt-1"></i>
                                                <span>The inclusion of any links does not imply endorsement</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-external-link-square-alt text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="mb-0">
                                            <strong>Warning:</strong> Accessing external links is at your own risk. 
                                            We recommend reviewing the privacy policies and terms of any third-party websites.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Errors and Omissions Disclaimer -->
                <section id="errors" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-bug text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">5. Errors and Omissions Disclaimer</h2>
                                <p class="text-unik-muted">Accuracy limitations</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-search text-6xl text-unik-accent"></i>
                                </div>
                                <div>
                                    <p class="mb-4">
                                        While we strive to ensure information accuracy, the website may contain:
                                    </p>
                                    
                                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                                        <div class="bg-status-error/5 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-times-circle text-status-error"></i>
                                                <span class="font-semibold">Errors</span>
                                            </div>
                                            <p class="text-sm text-unik-muted">Mistakes or inaccuracies</p>
                                        </div>
                                        <div class="bg-status-warning/5 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-minus-circle text-status-warning"></i>
                                                <span class="font-semibold">Omissions</span>
                                            </div>
                                            <p class="text-sm text-unik-muted">Missing information</p>
                                        </div>
                                        <div class="bg-unik-muted/5 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-clock text-unik-muted"></i>
                                                <span class="font-semibold">Outdated Info</span>
                                            </div>
                                            <p class="text-sm text-unik-muted">Information no longer current</p>
                                        </div>
                                        <div class="bg-status-info/5 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-exclamation text-status-info"></i>
                                                <span class="font-semibold">Inconsistencies</span>
                                            </div>
                                            <p class="text-sm text-unik-muted">Conflicting information</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-unik-light rounded-lg p-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-unik-accent text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Liability Notice</h4>
                                        <p class="text-unik-muted mb-0">
                                            We are not liable for any inaccuracies or consequences arising from the use of content on this website.
                                            Users should verify information independently when making important decisions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Fair Use Disclaimer -->
                <section id="fair-use" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-balance-scale text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">6. Fair Use Disclaimer</h2>
                                <p class="text-unik-muted">Copyrighted material usage</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="bg-unik-light rounded-lg p-6 mb-6">
                                <div class="flex items-center gap-6">
                                    <div class="text-center">
                                        <i class="fas fa-copyright text-5xl text-unik-accent"></i>
                                    </div>
                                    <div>
                                        <p class="mb-4">
                                            This website may include copyrighted material for educational, commentary, or informational purposes.
                                        </p>
                                        <div class="space-y-3">
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-graduation-cap text-unik-primary mt-1"></i>
                                                <span><strong>Educational Purpose:</strong> For learning and knowledge sharing</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-comment-alt text-unik-primary mt-1"></i>
                                                <span><strong>Commentary Purpose:</strong> For analysis and discussion</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-info-circle text-unik-primary mt-1"></i>
                                                <span><strong>Informational Purpose:</strong> For general information dissemination</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info bg-blue-50 border-l-4 border-status-info pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-scale-balanced text-status-info text-xl mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-unik-dark mb-2">Fair Use Statement</h4>
                                        <p class="text-unik-muted mb-0">
                                            We believe this constitutes fair use under applicable copyright laws. 
                                            If you believe your copyrighted work has been used improperly, please contact us immediately.
                                        </p>
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
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-gavel text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">7. Limitation of Liability</h2>
                                <p class="text-unik-muted">Legal responsibility limits</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="bg-red-50 border border-red-100 rounded-lg p-6 mb-6">
                                <div class="flex items-start gap-4">
                                    <i class="fas fa-shield-alt text-3xl text-status-error mt-1"></i>
                                    <div>
                                        <h4 class="font-bold text-unik-dark mb-3">Important Legal Limitation</h4>
                                        <p class="mb-4">
                                            Under no circumstances shall <span class="font-semibold">Unik Muse</span> be held liable for any loss or damage, including but not limited to:
                                        </p>
                                        <div class="grid md:grid-cols-2 gap-4">
                                            @foreach([
                                                'Indirect or consequential loss',
                                                'Data loss or corruption',
                                                'Business interruption',
                                                'Loss of profits or revenue',
                                                'Reputational damage',
                                                'Personal injury or harm'
                                            ] as $liability)
                                                <div class="flex items-start gap-2">
                                                    <i class="fas fa-ban text-status-error text-sm mt-1"></i>
                                                    <span class="text-sm">{{ $liability }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning bg-yellow-50 border-l-4 border-status-warning pl-4 py-4">
                                <div class="flex items-start">
                                    <i class="fas fa-hand-paper text-status-warning text-xl mr-3 mt-1"></i>
                                    <div>
                                        <p class="mb-0 font-medium">
                                            This limitation applies to all damages of any kind arising from the use of this website.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Consent -->
                <section id="consent" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-check-double text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">8. Consent</h2>
                                <p class="text-unik-muted">User agreement to terms</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="flex flex-col md:flex-row items-center gap-8 mb-6">
                                <div class="text-center">
                                    <i class="fas fa-handshake text-6xl text-unik-accent"></i>
                                </div>
                                <div>
                                    <p class="mb-6">
                                        By using our website, you hereby consent to this Disclaimer and agree to its terms.
                                    </p>
                                    
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="bg-gradient-to-br from-status-success/5 to-unik-light rounded-lg p-6 text-center">
                                            <div class="w-16 h-16 rounded-full bg-status-success/20 flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-check text-2xl text-status-success"></i>
                                            </div>
                                            <h4 class="font-semibold text-unik-dark mb-2">Agreement</h4>
                                            <p class="text-sm text-unik-muted">You accept all disclaimer terms</p>
                                        </div>
                                        <div class="bg-gradient-to-br from-status-warning/5 to-unik-light rounded-lg p-6 text-center">
                                            <div class="w-16 h-16 rounded-full bg-status-warning/20 flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-exclamation text-2xl text-status-warning"></i>
                                            </div>
                                            <h4 class="font-semibold text-unik-dark mb-2">Understanding</h4>
                                            <p class="text-sm text-unik-muted">You understand the limitations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact Us -->
                <section id="contact" class="scroll-mt-24 fade-in">
                    <div class="bg-white rounded-unik-lg shadow-unik-sm p-8 border border-unik-border">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center">
                                <i class="fas fa-envelope text-xl text-unik-accent"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-unik-dark">9. Contact Us</h2>
                                <p class="text-unik-muted">How to reach us for questions</p>
                            </div>
                        </div>
                        
                        <div class="disclaimer-section">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Get in Touch</h3>
                                    
                                    <div class="space-y-6">
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-envelope text-unik-accent"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Email Address</p>
                                                <a href="mailto:{{ config('app.email', 'disclaimer@unikmuse.com') }}" 
                                                   class="font-semibold text-unik-accent hover:text-unik-primary transition-colors">
                                                    {{ config('app.email', 'disclaimer@unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 rounded-full bg-unik-accent/10 flex items-center justify-center mr-4">
                                                <i class="fas fa-globe text-unik-accent"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-unik-muted mb-1">Website</p>
                                                <a href="{{ config('app.url', url('/')) }}" 
                                                   class="font-semibold text-unik-accent hover:text-unik-primary transition-colors">
                                                    {{ config('app.url', 'unikmuse.com') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-unik-dark mb-6">Response Information</h3>
                                    <p class="mb-4">We aim to respond to all disclaimer-related inquiries within 3-5 business days.</p>
                                    
                                    <div class="bg-unik-light rounded-lg p-4">
                                        <p class="text-sm text-unik-muted mb-0">
                                            <i class="fas fa-lightbulb text-unik-accent mr-2"></i>
                                            For copyright infringement notices, please include "Copyright Notice" in the subject line.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Disclaimer Footer -->
                <footer class="mt-12 pt-8 border-t border-unik-border">
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-unik-light rounded-lg p-6">
                            <h4 class="font-semibold text-unik-dark mb-4">Document Status</h4>
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
                                <div class="flex justify-between">
                                    <span class="text-unik-muted">Document Type</span>
                                    <span class="font-medium text-status-warning">Legal Disclaimer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-unik-light rounded-lg p-6">
                            <h4 class="font-semibold text-unik-dark mb-4">Important Reminder</h4>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-status-warning text-xl mt-1"></i>
                                <div>
                                    <p class="text-sm text-unik-muted mb-0">
                                        This disclaimer is a legal document. If you have questions about its content, 
                                        consult with a qualified legal professional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-unik-muted mb-6">
                            <small>
                                This Disclaimer is effective as of {{ date('F d, Y') }} and applies to all website content.
                            </small>
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="{{ url('/') }}" class="btn btn-outline inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Home
                            </a>
                            {{-- <button onclick="window.print()" class="btn btn-primary inline-flex items-center">
                                <i class="fas fa-print mr-2"></i> Print This Page
                            </button> --}}
                        </div>
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
                            item.classList.remove('bg-unik-accent/5', 'border', 'border-unik-accent/20');
                            if (item.getAttribute('href') === `#${id}`) {
                                item.classList.add('bg-unik-accent/5', 'border', 'border-unik-accent/20');
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
