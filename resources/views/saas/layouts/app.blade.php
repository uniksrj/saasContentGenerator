<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim($__env->yieldContent('title', 'SaaS Workspace')) }} | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
@php
    $authUser = auth()->user();
    $sidebarProjects = $authUser?->projects()->latest()->take(6)->get() ?? collect();
    $sidebarPlan = $authUser?->plan;
@endphp
<div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
    @auth
        <aside class="hidden border-r border-slate-200 bg-white lg:block">
            <div class="sticky top-0 flex h-screen flex-col">
                <div class="border-b border-slate-200 px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Workspace</p>
                    <h1 class="mt-2 text-xl font-semibold text-slate-900">{{ config('app.name', 'ContentFlow') }}</h1>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
                    <a href="{{ route('saas.dashboard') }}" class="{{ request()->routeIs('saas.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('saas.projects.index') }}" class="{{ request()->routeIs('saas.projects.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                        <span>Projects</span>
                    </a>
                    <a href="{{ route('saas.topics.index') }}" class="{{ request()->routeIs('saas.topics.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                        <span>Topics</span>
                    </a>
                    <a href="{{ route('saas.articles.index') }}" class="{{ request()->routeIs('saas.articles.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                        <span>Articles</span>
                    </a>
                    <a href="{{ route('saas.settings.index') }}" class="{{ request()->routeIs('saas.settings.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                        <span>Settings</span>
                    </a>
                    @if($authUser?->isAdmin())
                        <a href="{{ route('saas.admin.index') }}" class="{{ request()->routeIs('saas.admin.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition">
                            <span>Admin</span>
                        </a>
                    @endif
                </nav>

                <div class="border-t border-slate-200 px-4 py-4">
                    <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Subscription</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $sidebarPlan?->name ?? 'No plan selected' }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ ucfirst($authUser?->subscription_status ?? 'inactive') }}</p>
                    </div>
                    <p class="px-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Recent Projects</p>
                    <div class="mt-3 space-y-2">
                        @forelse($sidebarProjects as $project)
                            <form action="{{ route('saas.projects.switch', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="{{ $authUser?->current_project_id === $project->id ? 'border-slate-800 bg-slate-50 text-slate-900' : 'border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-900' }} w-full rounded-lg border px-3 py-2 text-left text-sm transition">
                                    {{ $project->name }}
                                </button>
                            </form>
                        @empty
                            <p class="px-2 text-sm text-slate-400">No projects yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </aside>
    @endauth

    <div class="flex min-h-screen flex-col">
        @auth
            <header class="border-b border-slate-200 bg-white">
                <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">SaaS Control</p>
                        <p class="text-sm text-slate-600">{{ now()->format('l, d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-right">
                            <p class="text-xs font-medium text-slate-400">Signed in as</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $authUser?->name }}</p>
                            <p class="text-xs text-slate-500">{{ $authUser?->isAdmin() ? 'Admin' : 'User' }}</p>
                        </div>
                        <form action="{{ route('saas.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
        @endauth

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <p class="font-semibold">Please fix the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
