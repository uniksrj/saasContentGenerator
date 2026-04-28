# Dry Run Structure

This file is a simple step-by-step test flow you can use to understand the product without guessing.

## Goal

Verify the full customer journey from project selection to ranked topics, article generation, and activity history.

## Dry Run Steps

1. Log in with a subscribed user.
2. Open `/projects` and confirm at least one active project exists.
3. Open `/topics`.
4. Select a project from the dropdown.
5. Click `Refresh topics`.
6. Confirm that:
   - topics appear
   - scores are visible
   - status badges are visible
   - source information is visible
7. Click `Generate article` on one topic.
8. Confirm that:
   - a success message appears
   - topic status updates after reload
   - article count changes if a draft is created
9. Click `Generate project articles`.
10. Open `/activity`.
11. Confirm that the feed shows:
   - `topics_refreshed`
   - `topic_generation_requested`
   - `topic_generation_completed`
   - `project_generation_requested`
   - `project_generation_completed`
   - `article_generated`
   - `tokens_used`

## Expected Backend Flow

### Refresh Topics

- `TopicController@index`
- optional refresh branch
- `AiBlogDraftGeneratorService::fetchAndStoreTopics()`
- `TopicScoringService`
- `ActivityLogService::record(topics_refreshed)`

### Generate One Topic

- `TopicController@generate`
- `UsageLimitService::generationSnapshot()`
- `ActivityLogService::record(topic_generation_requested)`
- `AiBlogDraftGeneratorService::runFromProvidedTopics()`
- `UsageLog` entries for article and token usage
- `ActivityLogService::record(topic_generation_completed)`

### Generate Project Batch

- `GenerationController@store`
- `UsageLimitService::generationSnapshot()`
- `ActivityLogService::record(project_generation_requested)`
- `AiBlogDraftGeneratorService::generateForProject()`
- `UsageLog` entries for article and token usage
- `ActivityLogService::record(project_generation_completed)`

## Useful Places To Inspect While Testing

- `frontend/app/topics/page.tsx`
- `frontend/app/activity/page.tsx`
- `frontend/lib/api.ts`
- `routes/api.php`
- `app/Http/Controllers/Api/TopicController.php`
- `app/Http/Controllers/Api/GenerationController.php`
- `app/Services/Saas/ActivityLogService.php`

## If Something Fails

- Check subscription state in `/profile`
- Check whether the selected project is active
- Check API auth token state
- Check Laravel logs
- Check whether topic fetchers returned enough valid topic candidates
