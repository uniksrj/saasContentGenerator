# SaaS Process Overview

This project is a subscription-based content workflow SaaS built with:

- `Laravel` for authentication, billing, topic fetching, scoring, and article generation APIs
- `Next.js` for the logged-in product UI and customer-facing frontend

## Main Product Flow

1. User signs up or logs in.
2. User selects or creates a project.
3. User opens the `Topics` page.
4. The app fetches topic opportunities from connected sources.
5. Topics are scored and stored for the current user and project.
6. User reviews ranked topics.
7. User generates one article or a project-wide batch.
8. The system stores the article draft.
9. Usage and activity logs are recorded.
10. User reviews the `Activity` page to understand what happened.

## Core User Screens

- `/dashboard`
  Summary of projects, topics, articles, and usage.

- `/projects`
  Workspace management for each customer.

- `/topics`
  Main operating screen for fetched topics, ranking, and generation actions.

- `/articles`
  Generated content library.

- `/activity`
  Feed of refresh actions, generation events, article creation, and token usage.

- `/profile`
  Account and plan usage overview.

## Why `Topics` Is The Main SaaS Screen

This product promise is:

- fetch content opportunities from different platforms
- rank those opportunities
- turn the best ones into articles

Because of that, the `Topics` page is the core workflow screen. Billing matters, but the real value is visible inside the topic pipeline.

## What The Activity Page Adds

The new `Activity` screen helps answer:

- what did the user do
- when did it happen
- which project was affected
- which topic or article was involved
- how many tokens were used

That makes the product easier to trust and easier to debug.
