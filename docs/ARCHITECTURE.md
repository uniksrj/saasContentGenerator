# Architecture Overview

## High-Level Stack

- `Laravel backend`
  Owns auth, plans, billing sync, topic fetching, ranking, generation, and activity logging.

- `Next.js frontend`
  Owns dashboard UX, topic workflow, activity history, billing UI, and profile pages.

## Backend Structure

### API Routes

Main file:

- `routes/api.php`

Important endpoints:

- `GET /profile`
- `GET /projects`
- `POST /projects`
- `GET /topics/{project}`
- `POST /generate/{project}`
- `POST /projects/{project}/topics/{topic}/generate`
- `GET /articles/{project}`
- `GET /activity`
- billing endpoints under `/billing/*`

### Controllers

- `app/Http/Controllers/Api/AuthController.php`
  Handles login, register, logout, and profile.

- `app/Http/Controllers/Api/TopicController.php`
  Returns ranked topics and handles single-topic generation.

- `app/Http/Controllers/Api/GenerationController.php`
  Handles project-wide generation.

- `app/Http/Controllers/Api/ActivityController.php`
  Returns recent activity feed for the logged-in user.

### Models

- `app/Models/User.php`
  Customer account and subscription state.

- `app/Models/Project.php`
  User workspace container.

- `app/Models/Topic.php`
  Ranked topic opportunities scoped by `user_id` and `project_id`.

- `app/Models/Article.php`
  Generated article drafts and publishing records.

- `app/Models/UsageLog.php`
  Activity and usage ledger.

### Services

- `app/Services/AiBlogDraftGeneratorService.php`
  Stores topics, generates article drafts, and logs article/token usage.

- `app/Services/RssTopicFetcherService.php`
  Pulls topic signals from external sources.

- `app/Services/Saas/TopicScoringService.php`
  Scores and filters topic opportunities.

- `app/Services/Saas/UsageLimitService.php`
  Calculates article and token limits for the user plan.

- `app/Services/Saas/ActivityLogService.php`
  Central place for recording and reading workflow activity.

## Frontend Structure

### App Pages

- `frontend/app/dashboard/page.tsx`
- `frontend/app/projects/page.tsx`
- `frontend/app/topics/page.tsx`
- `frontend/app/articles/page.tsx`
- `frontend/app/activity/page.tsx`
- `frontend/app/profile/page.tsx`
- `frontend/app/dashboard/billing/page.tsx`

### Shared Frontend Files

- `frontend/components/app-shell.tsx`
  Sidebar, navigation, and authenticated app layout.

- `frontend/lib/api.ts`
  Client API wrapper for Laravel endpoints.

- `frontend/lib/types.ts`
  Shared TypeScript data contracts.

- `frontend/proxy.ts`
  Protects authenticated frontend routes.

## Data Ownership Rules

- Projects belong to a `user_id`
- Topics belong to a `user_id` and `project_id`
- Articles belong to a `user_id` and `project_id`
- Usage logs belong to a `user_id` and optionally a `project_id`

That means the product is already structured around per-user ownership, which is why the activity feed can reliably explain who triggered which action.
