<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Unik Muse</title>
    <meta name="description" content="Welcome to Unik Muse. Explore inspiring stories and practical insights.">
    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-unik-light text-unik-dark font-sans">
    @include('common.header')

    <main class="container-unik py-14 md:py-20">
        <section class="rounded-2xl border border-unik-border bg-white p-8 shadow-sm md:p-12 text-center">
            <p class="mb-2 text-xs uppercase tracking-[0.16em] text-unik-muted">Unik Muse</p>
            <h1 class="mb-4 text-3xl md:text-5xl font-bold text-slate-900">Welcome to the Blog Platform</h1>
            <p class="mx-auto mb-8 max-w-2xl text-slate-600 text-base md:text-lg">
                Explore fresh content across technology, lifestyle, travel, creativity, and productivity.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('home') }}" class="rounded-md bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">Go to Home</a>
                <a href="{{ route('blog.index') }}" class="rounded-md border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100 transition-colors">Browse Blog</a>
            </div>
        </section>
    </main>

    @include('common.footer')
</body>

</html>
