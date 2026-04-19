<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $projects = Project::query()
            ->ownedBy($user->id)
            ->withCount(['topics', 'articles'])
            ->latest()
            ->get();

        return response()->json(['data' => $projects]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
        ]);

        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $baseSlug = Str::slug($validated['name']) ?: 'project';
        $slug = $baseSlug;
        $counter = 1;

        while (Project::query()->where('user_id', $user->id)->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $project = Project::query()->create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'settings' => $validated['settings'] ?? null,
            'is_active' => true,
        ]);

        if ($user->current_project_id === null) {
            $user->update(['current_project_id' => $project->id]);
        }

        return response()->json([
            'message' => 'Project created successfully.',
            'data' => $project->fresh()->loadCount(['topics', 'articles']),
        ], 201);
    }
}
