<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AiBlogDraftGeneratorService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    public function __construct(
        private readonly AiBlogDraftGeneratorService $generator,
        private readonly UsageLimitService $usageLimitService,
    )
    {
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $access = $this->usageLimitService->generationSnapshot($user);
        if (!($access['can_generate'] ?? false)) {
            return response()->json(['message' => $access['message']], 402);
        }

        $result = $this->generator->generateForProject($user->id, $project->id, (int) ($validated['limit'] ?? 5));

        return response()->json([
            'message' => (string) ($result['message'] ?? 'Generation completed.'),
            'data' => $result,
        ]);
    }
}
