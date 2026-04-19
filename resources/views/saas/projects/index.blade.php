@extends('saas.layouts.app')

@section('title', 'Projects')

@section('content')
<div class="space-y-8">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Project Workspace</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Manage Your SaaS Projects</h2>
                <p class="mt-2 text-sm text-slate-500">Each project keeps its own topics, AI generation pipeline, and article inventory.</p>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Projects</h3>
                <span class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $projects->count() }} total</span>
            </div>

            <div class="space-y-3">
                @forelse($projects as $project)
                    <article class="rounded-xl border {{ $currentProjectId === $project->id ? 'border-slate-800 bg-slate-50' : 'border-slate-200' }} p-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-slate-900">{{ $project->name }}</h4>
                                <p class="mt-1 text-sm text-slate-500">{{ $project->description ?: 'No description provided.' }}</p>
                                <div class="mt-3 flex flex-wrap gap-2 text-xs font-medium">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-600">{{ $project->topics_count }} topics</span>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-600">{{ $project->articles_count }} articles</span>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-600">{{ $project->slug }}</span>
                                </div>
                            </div>
                            <form action="{{ route('saas.projects.switch', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-xl border px-4 py-2 text-sm font-semibold {{ $currentProjectId === $project->id ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-300 text-slate-700 hover:border-slate-900 hover:text-slate-900' }}">
                                    {{ $currentProjectId === $project->id ? 'Current Project' : 'Switch' }}
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">
                        No projects yet. Create your first project from the form.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Create New Project</h3>
            <p class="mt-1 text-sm text-slate-500">Start a separate content engine for a niche, client, or campaign.</p>

            <form action="{{ route('saas.projects.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Project Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none" placeholder="Fintech Authority Hub">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="5" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none" placeholder="Describe this content workspace strategy.">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    Create And Select Project
                </button>
            </form>
        </div>
    </section>
</div>
@endsection
