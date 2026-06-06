---
description: Repository instructions for agents working on the GutConference PHP/JSON CMS.
globs: *
alwaysApply: true
---

# Agent Operating Guide

This repo is a GutConference Online PHP/JSON full-stack CMS for shared hosting. It is not a SPA, not a SQL app, and not a public event marketplace.

## Core Shape

- Frontend/UI: modern HTML and CSS in `views/`, fed by JSON/schema data. PHP runs the shared-hosted app and binds data, but UI decisions should be HTML/CSS-first.
- Backend: PHP controllers and services in `app/`.
- Database: JSON collections in `storage/data/`.
- Schema: `storage/schema/collections.json`.
- Admin: owner tools for events, sections, speakers, agenda, venues/maps, registrations, notifications, media, integrations, environment, settings, audit logs, and project map.
- Agent workflow: `AGENTS.md`, `example-Agent.md`, `Design.md`, and `.agents/skills/`.

## Mandatory Read Order

1. `README.md`
2. `storage/schema/collections.json`
3. `docs/PROJECT_MAP.md`
4. `example-Agent.md`
5. For broad work spanning UI/backend/schema/payment/review, read `.agents/skills/gutconference-orchestrator/SKILL.md`.
6. The narrow GutConference skill under `.agents/skills/<skill-name>/SKILL.md` that matches the task. For admin work use the `gutconference-admin` skill.

## Rules

- Keep JSON storage first. Do not introduce SQL/Postgres/MySQL unless the user explicitly asks for a separate migration.
- Update `storage/schema/collections.json` before changing collection shapes, admin fields, media fields, or agent-visible context.
- Keep route -> controller -> service -> JSON-store boundaries.
- Do not add React, CDN React, or a SPA fallback.
- Do not create a second frontend.
- Build UI with modern HTML, CSS, JSON-backed data, and `storage/schema/collections.json`. Use PHP for routing, controllers, services, and shared-hosting data binding, not as the visual design model. Do not introduce a frontend framework unless explicitly requested.
- Read `Design.md` before broad UI changes. For UI-only refactors, preserve visible text/content meaning unless the user explicitly asks for copy changes.
- Public users must not be able to create conferences. Only admins create and publish events.
- Admin mutations should be auditable.
- Event media should use the media library picker/upload flow.
- Environment and storage permission changes belong in `/admin/environment`, but do not introduce project `.env` files.
- Do not use, create, restore, or track `.env`, `.env.example`, or other `.env.*` files in this project. Secrets must be configured through encrypted admin integrations in `storage/data/adminsecrets.json` or server-level environment configuration outside the repo. If a rebase reports env-file conflicts, keep the GitHub `main` deletion.
- Keep admin navigation lean. Prefer grouped admin sections and automatic defaults over adding one-off menu items for every small action.
- Maximize automation from event data: event date/time, timezone, ticket capacity, and paid bookings should drive labels, countdown/timer behavior, remaining seats, and notification timing.
- Store timezone once in event/settings context and derive display labels from it instead of forcing repeated manual timezone selection.
- Admin editors should only expose fields that need human judgment; generated labels, counters, queue timings, and derived availability should be computed by services or scripts.
- Compare public event UX against provided competitor/reference URLs, but keep the tone clinical and conference-focused.
- Use `php -S 127.0.0.1:6040 index.php` as the default local server command unless the port is already occupied.
- When the product evolves, update the matching GutConference skill files under `.agents/skills/` so future agents inherit new workflow rules.
- When working from a pull request, review previous PR comments, push fixes to the same remote branch when appropriate, and communicate PR follow-up clearly to the remote collaborator by email or the configured project communication channel when requested.

## Validation

Run the smallest useful validation for the change:

```bash
php -l path/to/changed.php
php tests/run.php
php tools/validate-project-map.php
php tools/smoke-local.php
```

For UI changes, use a browser workflow and check home, event detail, admin login, and the changed admin resource.

## Deployment / Push to Production

Pushing to `origin/main` auto-deploys to **gutconference.online** via Hostinger Git auto-deploy. Treat every push as a production release.

The production remote for this repo is GitHub `getwinharris/GutConference-phpCMS.git`. Before comparing, rebasing, validating a release, or saying the branch is current, make sure `origin` points there and fetch GitHub `main` directly:

```bash
git remote set-url origin https://github.com/getwinharris/GutConference-phpCMS.git
git fetch --prune origin +refs/heads/main:refs/remotes/origin/main
git branch --set-upstream-to=origin/main main
```

Treat `refs/remotes/origin/main` as valid only after that forced GitHub fetch. Do not trust a pre-existing local `origin/main` ref from this machine, because the project was created locally before being pushed to GitHub. Use the freshly rebuilt GitHub remote-tracking ref as the baseline. If GitHub `main` moved, rebase local commits onto the fetched remote branch and resolve conflicts in favor of the current secret policy: tracked `.env` files stay deleted and are not reintroduced.

### Before pushing to `origin/main`, the agent MUST:

1. **Write a PR-style change summary** that includes:
   - A short title describing the release.
   - A bullet list of all changed areas (controllers, services, views, schema, data, assets, config, docs).
   - Any new files or deleted files called out explicitly.
   - Known risks, breaking changes, or migration notes.
   - Verification steps already completed (lint, tests, smoke, browser checks).
2. **Present the summary to the user** and explicitly ask: _"This will update the live site at gutconference.online. Approve push to origin/main?"_
3. **Wait for the user's explicit approval** before running `git push`.
4. **Do not push** if the user declines or asks for changes — address the feedback first, amend the commit if needed, and re-present the summary.

### After pushing:

- Confirm the push succeeded and report the commit hash.
- Remind the user to verify the live site at `https://gutconference.online`.
