@extends('saas.layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-slate-900">Edit Article</h2>
        <p class="mt-1 text-sm text-slate-500">Update content and publish state.</p>

        <form action="{{ route('saas.articles.update', $article) }}" method="POST" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                <input name="title" value="{{ old('title', $article->title) }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">{{ old('meta_description', $article->meta_description) }}</textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Category</label>
                <input name="category" value="{{ old('category', $article->category) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Content</label>
                <textarea name="content" rows="18" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">{{ old('content', $article->content) }}</textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-slate-700 focus:outline-none">
                    <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $article->status) === 'published')>Published</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Save Changes</button>
                <a href="{{ route('saas.articles.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-800 hover:text-slate-900">Back</a>
            </div>
        </form>
    </section>
</div>
@endsection
