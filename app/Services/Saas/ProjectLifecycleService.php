<?php

namespace App\Services\Saas;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProjectLifecycleService
{
    private const FREE_PROJECT_LIMIT = 5;

    public function totalProjectsForUser(User $user): int
    {
        return (int) Project::query()
            ->ownedBy($user->id)
            ->count();
    }

    public function projectLimitForUser(User $user): ?int
    {
        $user->loadMissing('plan');

        if ($user->isAdmin()) {
            return null;
        }

        if (($user->plan?->slug ?? null) === 'starter' || (int) ($user->plan?->price_cents ?? 0) === 0) {
            return self::FREE_PROJECT_LIMIT;
        }

        return null;
    }

    public function remainingProjectSlots(User $user): ?int
    {
        $limit = $this->projectLimitForUser($user);
        if ($limit === null) {
            return null;
        }

        return max(0, $limit - $this->totalProjectsForUser($user));
    }

    /**
     * @param array<string, mixed> $validated
     */
    public function createProject(User $user, array $validated): Project
    {
        $this->ensureProjectCreationAllowed($user);

        $baseSlug = Str::slug((string) ($validated['name'] ?? '')) ?: 'project';
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
            'status' => Project::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $this->syncCurrentProjectForUser($user->fresh());

        return $project;
    }

    public function updateStatus(Project $project, string $status): Project
    {
        $project->update([
            'status' => $status,
            'is_active' => $status === Project::STATUS_ACTIVE,
        ]);

        $this->syncCurrentProjectForUser($project->user()->firstOrFail());

        return $project->fresh(['user'])->loadCount(['topics', 'articles']);
    }

    public function syncCurrentProjectForUser(User $user): void
    {
        if ($user->current_project_id !== null) {
            $hasActiveCurrentProject = Project::query()
                ->ownedBy($user->id)
                ->active()
                ->whereKey($user->current_project_id)
                ->exists();

            if ($hasActiveCurrentProject) {
                return;
            }
        }

        $fallbackProjectId = Project::query()
            ->ownedBy($user->id)
            ->active()
            ->latest()
            ->value('id');

        $user->update([
            'current_project_id' => $fallbackProjectId,
        ]);
    }

    public function ensureProjectCreationAllowed(User $user): void
    {
        $limit = $this->projectLimitForUser($user);
        if ($limit === null) {
            return;
        }

        if ($this->totalProjectsForUser($user) < $limit) {
            return;
        }

        throw ValidationException::withMessages([
            'name' => [
                "You have reached the {$limit}-project limit for the free plan. Disabled and removed projects still count toward this limit.",
            ],
        ]);
    }
}
