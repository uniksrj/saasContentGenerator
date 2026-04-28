<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Saas\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        return response()->json(
            $this->activityLogService->recentForUser($user, (int) ($validated['limit'] ?? 25))
        );
    }
}
