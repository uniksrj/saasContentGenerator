<style>
    .site-footer-wrap a {
        text-decoration: none !important;
    }

    .site-footer-wrap .footer-link {
        color: rgba(226, 232, 240, 0.86) !important;
    }

    .site-footer-wrap .footer-link:hover,
    .site-footer-wrap .footer-link:focus-visible {
        color: #ffffff !important;
    }

    .site-footer-wrap .footer-title {
        color: #ffffff !important;
    }

    .site-footer-wrap p,
    .site-footer-wrap li,
    .site-footer-wrap span {
        color: rgba(226, 232, 240, 0.82);
    }

    .site-footer-wrap .footer-social {
        color: #f8fafc !important;
    }
</style>

<footer class="site-footer-wrap mt-16 bg-slate-950 text-white">
    <div class="border-b border-slate-800">
        <div class="container-unik py-10 md:py-12">
            <div class="grid gap-6 rounded-2xl border border-slate-800 bg-slate-900/70 p-6 md:grid-cols-[1.6fr_1fr] md:p-8">
                <div>
                    <p class="mb-2 text-xs uppercase tracking-[0.16em] text-cyan-300">Unik Muse</p>
                    <h2 class="footer-title mb-2 text-2xl font-bold md:text-3xl">Build a better blog experience for your readers</h2>
                    <p class="mb-0 max-w-2xl text-sm md:text-base">
                        Fresh insights, practical writing, and topic-focused stories designed for readability and long-term SEO growth.
                    </p>
                </div>
                <div class="flex items-center md:justify-end">
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-cyan-300">
                        Visit Blog <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-unik py-10">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <img src="{{ asset('assets/unikmusewhite.webp') }}" alt="Unik Muse" class="mb-4 h-9 w-auto">
                <p class="mb-4 text-sm leading-7">
                    Unik Muse publishes quality content on technology, lifestyle, travel, digital trends, and creative growth.
                </p>
                <div class="flex items-center gap-3">
                    <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" class="footer-social inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-700 bg-slate-900 transition hover:border-cyan-400 hover:bg-slate-800" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer" class="footer-social inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-700 bg-slate-900 transition hover:border-cyan-400 hover:bg-slate-800" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" class="footer-social inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-700 bg-slate-900 transition hover:border-cyan-400 hover:bg-slate-800" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="footer-title mb-4 text-lg font-semibold">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                    <li><a href="{{ route('blog.index') }}" class="footer-link">Blog</a></li>
                    <li><a href="{{ route('categories.index') }}" class="footer-link">Categories</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link">About</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-title mb-4 text-lg font-semibold">Top Categories</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('category.show', 'technology') }}" class="footer-link">Technology</a></li>
                    <li><a href="{{ route('category.show', 'travel') }}" class="footer-link">Travel</a></li>
                    <li><a href="{{ route('category.show', 'life-style') }}" class="footer-link">Lifestyle</a></li>
                    <li><a href="{{ route('category.show', 'digital-trends') }}" class="footer-link">Digital Trends</a></li>
                    <li><a href="{{ route('category.show', 'productivity') }}" class="footer-link">Productivity</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer-title mb-4 text-lg font-semibold">Contact</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope mt-1 text-cyan-300"></i>
                        <a href="mailto:info@unikmuse.com" class="footer-link">info@unikmuse.com</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone mt-1 text-cyan-300"></i>
                        <a href="tel:+919877654765" class="footer-link">+91 987 7654 765</a>
                    </li>
                </ul>

                <div class="mt-5 rounded-xl border border-slate-800 bg-slate-900 p-4">
                    <p class="mb-3 text-xs uppercase tracking-[0.16em] text-cyan-300">Newsletter</p>
                    <form class="flex gap-2" action="{{ route('contact') }}" method="GET">
                        <label for="newsletter-email" class="sr-only">Email</label>
                        <input id="newsletter-email" type="email" placeholder="Your email" class="min-w-0 flex-1 rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none">
                        <button type="submit" class="rounded-md bg-cyan-400 px-3 py-2 text-sm font-semibold text-slate-900 transition hover:bg-cyan-300">Join</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="container-unik flex flex-col gap-3 py-5 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p class="mb-0">&copy; {{ date('Y') }} Unik Muse. All rights reserved.</p>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('privacy.policy') }}" class="footer-link text-xs">Privacy Policy</a>
                <a href="{{ route('terms.conditions') }}" class="footer-link text-xs">Terms & Conditions</a>
                <a href="{{ route('disclaimer') }}" class="footer-link text-xs">Disclaimer</a>
            </div>
        </div>
    </div>
</footer>
