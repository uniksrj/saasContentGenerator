<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Topic;
use App\Models\User;
use App\Services\Saas\UsageLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly UsageLimitService $usageLimitService,
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $plan = $this->starterPlan();
        $trialEndsAt = now()->addDays(14);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user',
            'plan_id' => $plan?->id,
            'subscription_status' => 'trialing',
            'subscribed_at' => now(),
            'current_period_starts_at' => now(),
            'current_period_ends_at' => $trialEndsAt,
            'subscription_ends_at' => $trialEndsAt,
        ]);

        return response()->json([
            'message' => 'Registration successful.',
            'token' => $user->createToken('nextjs-frontend')->plainTextToken,
            'token_type' => 'Bearer',
            'data' => [
                'user' => $user->loadMissing('plan', 'currentProject'),
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'message' => 'Login successful.',
            'token' => $user->createToken('nextjs-frontend')->plainTextToken,
            'token_type' => 'Bearer',
            'data' => [
                'user' => $user->loadMissing('plan', 'currentProject'),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401, 'Unauthenticated.');

        $user->loadMissing('plan', 'currentProject');

        return response()->json([
            'data' => [
                'user' => $user,
                'plan' => $user->plan,
                'current_project' => $user->currentProject,
                'usage' => $this->usageLimitService->generationSnapshot($user),
                'counts' => [
                    'projects' => Project::query()->where('user_id', $user->id)->count(),
                    'topics' => Topic::query()->where('user_id', $user->id)->count(),
                    'articles' => Article::query()->where('user_id', $user->id)->count(),
                ],
            ],
        ]);
    }

    private function starterPlan(): ?Plan
    {
        return Plan::query()->firstOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Entry-level plan for getting started with automated article generation.',
                'monthly_article_limit' => 10,
                'monthly_token_limit' => 120000,
                'price_cents' => 0,
                'currency' => 'USD',
                'billing_interval' => 'month',
                'is_active' => true,
                'features' => [
                    '10 AI articles per month',
                    'Single active subscription',
                    'Usage dashboard',
                    'Project management',
                ],
            ]
        );
    }
}
