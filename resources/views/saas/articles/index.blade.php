@extends('saas.layouts.app')

@section('title', 'Articles')

@section('content')
<div class="space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Article Pipeline</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $project?->name ?? 'No Project Selected' }}</h2>
                <p class="mt-2 text-sm text-slate-500">Manage generated content, edit drafts, and publish final pieces.</p>
            </div>
            <form action="{{ route('saas.articles.generate') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="number" name="limit" min="1" max="20" value="5" class="w-20 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-700 focus:outline-none" @disabled(!($generationSnapshot['can_generate'] ?? false))>
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50" @disabled(!($generationSnapshot['can_generate'] ?? false))>Generate Batch</button>
            </form>
        </div>
        @if(!($generationSnapshot['can_generate'] ?? false))
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                {{ $generationSnapshot['message'] }} <a href="{{ route('saas.settings.index') }}" class="font-semibold underline">Open billing</a>
            </div>
        @endif
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Drafts</p>
            <p class="mt-3 text-3xl font-semibold text-amber-600">{{ $draftCount }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Published</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ $publishedCount }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Current View</p>
            <p class="mt-3 text-xl font-semibold text-slate-900">{{ ucfirst($status) }} Articles</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
        <div class="flex items-center gap-2 p-2">
            <a href="{{ route('saas.articles.index', ['status' => 'draft']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ $status === 'draft' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Draft</a>
            <a href="{{ route('saas.articles.index', ['status' => 'published']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ $status === 'published' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Published</a>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Article</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Words</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($articles as $article)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $article->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $article->created_at?->format('d M Y, h:i A') }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ $article->category ?: '-' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ number_format($article->word_count) }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($article->status) }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('saas.articles.edit', $article) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:border-slate-800 hover:text-slate-900">Edit</a>
                                    @if($article->status !== 'published')
                                        <form action="{{ route('saas.articles.publish', $article) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">Publish</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No {{ $status }} articles yet. Generate from topics to fill this list.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-4 py-3">
            {{ $articles->links() }}
        </div>
    </section>
</div>
@endsection
