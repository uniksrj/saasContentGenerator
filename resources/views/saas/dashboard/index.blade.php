@extends('saas.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-900 to-slate-700 p-6 text-white lg:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-200">Overview</p>
        <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold lg:text-3xl">{{ $currentProject?->name ?? 'No Project Selected' }}</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-200">
                    {{ $currentProject?->description ?: 'Create a project to start topic discovery and article generation.' }}
                </p>
                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border border-white/20 px-3 py-1">Plan: {{ $user->plan?->name ?? 'None' }}</span>
                    <span class="rounded-full border border-white/20 px-3 py-1">Subscription: {{ ucfirst($user->subscription_status ?? 'inactive') }}</span>
                    <span class="rounded-full border border-white/20 px-3 py-1">Role: {{ ucfirst($user->role) }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('saas.projects.index') }}" class="inline-flex rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">
                    Manage Projects
                </a>
                <a href="{{ route('saas.settings.index') }}" class="inline-flex rounded-xl border border-white/20 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Billing & Profile
                </a>
            </div>
        </div>
    </section>

    @if(!$hasActiveSubscription)
        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900 shadow-sm">
            <p class="text-sm font-semibold">Subscription required</p>
            <p class="mt-1 text-sm">Choose a plan before generating any AI content.</p>
        </section>
    @elseif(!($generationSnapshot['can_generate'] ?? false))
        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900 shadow-sm">
            <p class="text-sm font-semibold">Generation paused</p>
            <p class="mt-1 text-sm">{{ $generationSnapshot['message'] }}</p>
        </section>
    @endif

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total Articles</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totalArticles) }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Drafts</p>
            <p class="mt-3 text-3xl font-semibold text-amber-600">{{ number_format($draftArticles) }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Published</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ number_format($publishedArticles) }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Projects</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($projectCount) }}</p>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Usage This Month</h3>
                <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $usageThisMonth }} / {{ $usageLimit }}</span>
            </div>
            <div class="mt-5 h-3 w-full rounded-full bg-slate-200">
                <div class="h-3 rounded-full bg-slate-800" style="width: {{ $usagePercent }}%"></div>
            </div>
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="text-slate-500">Remaining credits</span>
                <span class="font-semibold text-slate-800">{{ $remainingCredits }}</span>
            </div>
            <div class="mt-5 border-t border-slate-100 pt-5">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Token usage</span>
                    <span class="text-sm font-semibold text-slate-800">{{ number_format($tokenUsageThisMonth) }} / {{ number_format($tokenUsageLimit) }}</span>
                </div>
                <div class="mt-3 h-3 w-full rounded-full bg-slate-200">
                    <div class="h-3 rounded-full bg-cyan-500" style="width: {{ $tokenUsagePercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
            </div>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <form action="{{ route('saas.topics.fetch') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Fetch Topics</button>
                </form>
                <form action="{{ route('saas.articles.generate') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="number" name="limit" min="1" max="20" value="5" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-700 focus:outline-none" @disabled(!($generationSnapshot['can_generate'] ?? false))>
                    <button type="submit" class="rounded-xl border border-slate-900 px-4 py-2.5 text-sm font-semibold text-slate-900 hover:bg-slate-900 hover:text-white disabled:cursor-not-allowed disabled:opacity-50" @disabled(!($generationSnapshot['can_generate'] ?? false))>Generate</button>
                </form>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Recent Articles</h3>
                <a href="{{ route('saas.articles.index') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">View all</a>
            </div>
            <div class="space-y-3">
                @forelse($recentArticles as $article)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $article->title }}</p>
                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>{{ ucfirst($article->status) }}</span>
                            <span>{{ $article->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No articles yet for this project.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Recent Activity</h3>
            </div>
            <div class="space-y-3">
                @forelse($recentActivity as $log)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-semibold text-slate-800">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>{{ $log->units }} unit(s)</span>
                            <span>{{ $log->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No usage activity yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
