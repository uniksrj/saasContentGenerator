@extends('saas.layouts.app')

@section('title', 'Topics')

@section('content')
<div class="space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Topic Discovery</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $project?->name ?? 'No Project Selected' }}</h2>
                <p class="mt-2 text-sm text-slate-500">Topics are scored and prioritized before article generation.</p>
            </div>
            <form action="{{ route('saas.topics.fetch') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="number" name="limit" min="10" max="100" value="30" class="w-24 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-slate-700 focus:outline-none">
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Refresh Topics</button>
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
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total Topics</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $topics->total() }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Top Score</p>
            <p class="mt-3 text-3xl font-semibold text-indigo-600">{{ $topScore }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Project</p>
            <p class="mt-3 truncate text-xl font-semibold text-slate-900">{{ $project?->name ?? '-' }}</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Topic</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Score</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($topics as $topic)
                        <tr class="align-top">
                            <td class="px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $topic->title }}</p>
                                <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $topic->description }}</p>
                                @if(!empty($topic->score_breakdown))
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach($topic->score_breakdown as $key => $value)
                                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] text-slate-500">{{ ucfirst($key) }}: {{ $value }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">{{ $topic->score }}</td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <p>{{ strtoupper($topic->source_type) }}</p>
                                @if($topic->source_url)
                                    <a href="{{ $topic->source_url }}" target="_blank" class="mt-1 inline-block text-slate-900 underline">Source</a>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ ucfirst($topic->status) }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <form action="{{ route('saas.topics.generate', $topic) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50" @disabled(!($generationSnapshot['can_generate'] ?? false))>
                                        Generate Article
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No topics found. Click "Refresh Topics" to fetch and score new ideas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-4 py-3">
            {{ $topics->links() }}
        </div>
    </section>
</div>
@endsection
