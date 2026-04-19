# MyBlog

## AI Blog Draft Automation

This project now includes an automated AI blog generation pipeline with manual review.

### What it does
- Fetches trending RSS topics for technology and travel.
- Generates original long-form blog drafts using OpenAI.
- Assigns category slug from allowed list.
- Saves posts as `draft` in `newpost_details`.
- Skips duplicates by title/source URL.
- Requires admin review before publish.
- During publish, draft is rewritten with a final SEO optimization pass (OpenAI).

### Setup
1. Run migrations:

```bash
php artisan migrate
```

2. Add env values:

```env
OPENAI_API_KEY=your_key_here
OPENAI_MODEL=gpt-4o-mini
OPENAI_PUBLISH_MODEL=gpt-4o-mini
OPENAI_BASE_URL=https://api.openai.com/v1
AI_BLOG_DEFAULT_LIMIT=5
```

### Manual run

```bash
php artisan blogs:generate-ai-drafts --limit=5
```

### Scheduler
Laravel scheduler now includes daily draft generation at `06:00`.

Set server cron to run Laravel scheduler every minute:

```bash
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

### Admin review flow
- `GET /admin/drafts` to review all draft blogs.
- Preview each draft.
- Publish triggers final SEO rewrite, then changes status from `draft` to `published`.
