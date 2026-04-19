<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Topic;
use App\Services\AiBlogDraftGeneratorService;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(
        private readonly AiBlogDraftGeneratorService $generator,
        private readonly UsageLimitService $usageLimitService,
    )
    {
    }

    public function index(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');

        $validated = $request->validate([
            'refresh' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $shouldRefresh = (bool) ($validated['refresh'] ?? false);
        if ($shouldRefresh) {
            $this->generator->fetchAndStoreTopics($user->id, $project, (int) ($validated['limit'] ?? 25));
        }

        $topics = Topic::query()
            ->where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->withCount('articles')
            ->orderByDesc('score')
            ->orderByDesc('published_at')
            ->paginate(20);

        return response()->json($topics);
    }

    public function generate(Request $request, Project $project, Topic $topic): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');
        abort_if($project->user_id !== $user->id, 403, 'Unauthorized project access.');
        abort_if($topic->project_id !== $project->id || $topic->user_id !== $user->id, 403, 'Unauthorized topic access.');

        $access = $this->usageLimitService->generationSnapshot($user);
        if (!($access['can_generate'] ?? false)) {
            return response()->json(['message' => $access['message']], 402);
        }

        $result = $this->generator->runFromProvidedTopics(
            [$topic->raw_payload ?? $topic->toArray()],
            1,
            $user->id,
            $project->id
        );

        return response()->json([
            'message' => (string) ($result['message'] ?? 'Article generation completed for topic.'),
            'data' => $result,
        ]);
    }
}
