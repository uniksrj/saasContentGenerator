<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Saas\ProjectLifecycleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectLifecycleService $projectLifecycleService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $includeInactive = $request->boolean('include_inactive');
        $includeRemoved = $request->boolean('include_removed');

        $projectsQuery = Project::query()
            ->ownedBy($user->id);

        if (!$includeInactive) {
            $projectsQuery->active();
        } elseif (!$includeRemoved) {
            $projectsQuery->notRemoved();
        }

        $projects = $projectsQuery
            ->withCount(['topics', 'articles'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $projects,
            'meta' => [
                'total_projects' => $this->projectLifecycleService->totalProjectsForUser($user),
                'active_projects' => Project::query()->ownedBy($user->id)->active()->count(),
                'disabled_projects' => Project::query()->ownedBy($user->id)->where('status', Project::STATUS_DISABLED)->count(),
                'removed_projects' => Project::query()->ownedBy($user->id)->where('status', Project::STATUS_REMOVED)->count(),
                'project_limit' => $this->projectLifecycleService->projectLimitForUser($user),
                'remaining_project_slots' => $this->projectLifecycleService->remainingProjectSlots($user),
            ],
        ]);
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
        $project = $this->projectLifecycleService->createProject($user, $validated);

        return response()->json([
            'message' => 'Project created successfully.',
            'data' => $project->fresh()->loadCount(['topics', 'articles']),
        ], 201);
    }

    public function updateStatus(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:active,disabled,removed'],
        ]);

        $project = $this->projectLifecycleService->updateStatus($project, $validated['status']);

        return response()->json([
            'message' => 'Project status updated successfully.',
            'data' => $project,
        ]);
    }
}
