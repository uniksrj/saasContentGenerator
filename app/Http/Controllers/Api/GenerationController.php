<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AiBlogDraftGeneratorService;
use App\Services\Saas\ActivityLogService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    public function __construct(
        private readonly AiBlogDraftGeneratorService $generator,
        private readonly ActivityLogService $activityLogService,
        private readonly UsageLimitService $usageLimitService,
    )
    {
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');
        abort_if(!$project->isAccessible(), 422, 'This project is not active.');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $access = $this->usageLimitService->generationSnapshot($user);
        if (!($access['can_generate'] ?? false)) {
            return response()->json(['message' => $access['message']], 402);
        }

        $requestedLimit = (int) ($validated['limit'] ?? 5);

        $this->activityLogService->record(
            $user,
            $project->id,
            'project_generation_requested',
            $requestedLimit,
            [
                'project_name' => $project->name,
                'requested_limit' => $requestedLimit,
            ],
        );

        $result = $this->generator->generateForProject($user->id, $project->id, $requestedLimit);

        $this->activityLogService->record(
            $user,
            $project->id,
            'project_generation_completed',
            (int) ($result['generated'] ?? 0),
            [
                'project_name' => $project->name,
                'requested_limit' => $requestedLimit,
                'generated' => (int) ($result['generated'] ?? 0),
                'duplicates' => (int) ($result['duplicates'] ?? 0),
                'failed' => (int) ($result['failed'] ?? 0),
                'tokens_used' => (int) ($result['tokens_used'] ?? 0),
                'fetched' => (int) ($result['fetched'] ?? 0),
            ],
        );

        return response()->json([
            'message' => (string) ($result['message'] ?? 'Generation completed.'),
            'data' => $result,
        ]);
    }
}
