<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $projects = Project::query()
            ->ownedBy($user->id)
            ->withCount(['topics', 'articles'])
            ->latest()
            ->get();

        return view('saas.projects.index', [
            'projects' => $projects,
            'currentProjectId' => $user->current_project_id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
        ]);

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

        $user->update(['current_project_id' => $project->id]);

        return redirect()->route('saas.projects.index')->with('success', 'Project created and selected.');
    }

    public function switch(Request $request, Project $project): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        abort_if($project->user_id !== $user->id, 403);

        $user->update(['current_project_id' => $project->id]);

        return back()->with('success', 'Switched to project: ' . $project->name);
    }
}
