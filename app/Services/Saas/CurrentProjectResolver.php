<?php

namespace App\Services\Saas;

use App\Models\Project;
use App\Models\User;

class CurrentProjectResolver
{
    public function resolveForUser(User $user): ?Project
    {
        if ($user->current_project_id !== null) {
            $current = Project::query()
                ->ownedBy($user->id)
                ->find($user->current_project_id);

            if ($current !== null) {
                return $current;
            }
        }

        return Project::query()
            ->ownedBy($user->id)
            ->latest()
            ->first();
    }
}

