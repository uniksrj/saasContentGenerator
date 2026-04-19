<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\AiBlogDraftGeneratorService;
use App\Services\Saas\CurrentProjectResolver;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
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
        $status = (string) $request->query('status', 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        $articles = Article::query()
            ->where('user_id', $user->id)
            ->where('project_id', $project?->id)
            ->where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('saas.articles.index', [
            'project' => $project,
            'status' => $status,
            'generationSnapshot' => $this->usageLimitService->generationSnapshot($user),
            'articles' => $articles,
            'draftCount' => Article::query()
                ->where('user_id', $user->id)
                ->where('project_id', $project?->id)
                ->where('status', 'draft')
                ->count(),
            'publishedCount' => Article::query()
                ->where('user_id', $user->id)
                ->where('project_id', $project?->id)
                ->where('status', 'published')
                ->count(),
        ]);
    }

    public function generate(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $project = $this->projectResolver->resolveForUser($user);
        abort_if($project === null, 422, 'Please create/select a project first.');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $access = $this->usageLimitService->generationSnapshot($user);
        if (!($access['can_generate'] ?? false)) {
            return redirect()->route('saas.settings.index')->withErrors([
                'subscription' => (string) $access['message'],
            ]);
        }

        $result = $this->generator->generateForProject($user->id, $project->id, (int) ($validated['limit'] ?? 5));

        return redirect()->route('saas.articles.index')->with(
            $result['generated'] > 0 ? 'success' : 'warning',
            'Generation run completed. ' . ($result['message'] ?? '')
        );
    }

    public function edit(Request $request, Article $article): View
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        abort_if($article->user_id !== $user->id, 403);

        return view('saas.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        abort_if($article->user_id !== $user->id, 403);

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

        return redirect()->route('saas.articles.index', ['status' => $validated['status']])->with('success', 'Article updated.');
    }

    public function publish(Request $request, Article $article): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);
        abort_if($article->user_id !== $user->id, 403);

        $article->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Article published.');
    }

}
