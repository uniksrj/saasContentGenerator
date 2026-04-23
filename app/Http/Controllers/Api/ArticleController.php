<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');
        abort_if(!$project->isAccessible(), 422, 'This project is not active.');

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:draft,published'],
        ]);

        $query = Article::query()
            ->where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->with(['topic:id,title,project_id'])
            ->latest();

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        return response()->json($query->paginate(20));
    }

    public function update(Request $request, Project $project, Article $article): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');
        abort_if(!$project->isAccessible(), 422, 'This project is not active.');
        abort_if($article->project_id !== $project->id || $article->user_id !== $user->id, 403, 'Unauthorized article access.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'string', 'in:draft,published'],
        ]);

        $wordCount = str_word_count(strip_tags($validated['content']));

        $article->update([
            'title' => $validated['title'],
            'slug' => $article->slug ?: Str::slug($validated['title']),
            'meta_title' => Str::limit($validated['title'], 255, ''),
            'meta_description' => $validated['meta_description'] ?? null,
            'content' => $validated['content'],
            'category' => $validated['category'] ?? $article->category,
            'status' => $validated['status'],
            'is_published' => $validated['status'] === 'published',
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'word_count' => $wordCount,
            'reading_time' => max(1, (int) ceil($wordCount / 200)),
        ]);

        return response()->json([
            'message' => 'Article updated.',
            'data' => $article->fresh(),
        ]);
    }

    public function publish(Request $request, Project $project, Article $article): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');
        abort_if(!$project->isAccessible(), 422, 'This project is not active.');
        abort_if($article->project_id !== $project->id || $article->user_id !== $user->id, 403, 'Unauthorized article access.');

        $article->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Article published.',
            'data' => $article->fresh(),
        ]);
    }
}
