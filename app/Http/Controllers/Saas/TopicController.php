<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Services\AiBlogDraftGeneratorService;
use App\Services\Saas\CurrentProjectResolver;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function __construct(
        private readonly AiBlogDraftGeneratorService $generator,
        private readonly CurrentProjectResolver $projectResolver,
        private readonly UsageLimitService $usageLimitService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $project = $this->projectResolver->resolveForUser($user);

        $topics = Topic::query()
            ->where('user_id', $user->id)
            ->where('project_id', $project?->id)
            ->orderByDesc('score')
            ->orderByDesc('published_at')
            ->paginate(20);

        return view('saas.topics.index', [
            'project' => $project,
            'topics' => $topics,
            'generationSnapshot' => $this->usageLimitService->generationSnapshot($user),
            'topScore' => (int) (Topic::query()
                ->where('user_id', $user->id)
                ->where('project_id', $project?->id)
                ->max('score') ?? 0),
        ]);
    }

    public function fetch(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $project = $this->projectResolver->resolveForUser($user);
        abort_if($project === null, 422, 'Please create/select a project first.');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $this->generator->fetchAndStoreTopics($user->id, $project, (int) ($validated['limit'] ?? 30));

        return redirect()->route('saas.topics.index')->with('success', 'Topics fetched and scored.');
    }

    public function generate(Request $request, Topic $topic): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $project = $this->projectResolver->resolveForUser($user);
        abort_if($project === null, 422, 'Please create/select a project first.');
        abort_if($topic->project_id !== $project->id || $topic->user_id !== $user->id, 403);

        $access = $this->usageLimitService->generationSnapshot($user);
        if (!($access['can_generate'] ?? false)) {
            return redirect()->route('saas.settings.index')->withErrors([
                'subscription' => (string) $access['message'],
            ]);
        }

        $result = $this->generator->runFromProvidedTopics([$topic->raw_payload ?? $topic->toArray()], 1, $user->id, $project->id);

        return redirect()->route('saas.articles.index')->with(
            $result['generated'] > 0 ? 'success' : 'warning',
            $result['generated'] > 0 ? 'Article generated successfully.' : (string) ($result['message'] ?? 'No article was generated.')
        );
    }

}
