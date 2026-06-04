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
- Agent workflow: `AGENTS.md`, `CLAUDE.md`, `example-Agent.md`, `.codex/skills/`, `.claude/skills/`, and `.agents/skills/`.

## Mandatory Read Order

1. `README.md`
2. `storage/schema/collections.json`
3. `docs/PROJECT_MAP.md`
4. `example-Agent.md`
5. The narrow skill under `.codex/skills/<skill-name>/SKILL.md`, `.claude/skills/<skill-name>/SKILL.md`, or `.agents/skills/<skill-name>/SKILL.md` that matches the task. For admin work use the `gutconference-admin` skill.

## Rules

- Keep JSON storage first. Do not introduce SQL/Postgres/MySQL unless the user explicitly asks for a separate migration.
- Update `storage/schema/collections.json` before changing collection shapes, admin fields, media fields, or agent-visible context.
- Keep route -> controller -> service -> JSON-store boundaries.
- Do not add React, CDN React, or a SPA fallback.
- Do not create a second frontend.
- Build UI with modern HTML, CSS, JSON-backed data, and `storage/schema/collections.json`. Use PHP for routing, controllers, services, and shared-hosting data binding, not as the visual design model. Do not introduce a frontend framework unless explicitly requested.
- Public users must not be able to create conferences. Only admins create and publish events.
- Admin mutations should be auditable.
- Event media should use the media library picker/upload flow.
- Environment and storage permission changes belong in `/admin/environment`.
- Keep admin navigation lean. Prefer grouped admin sections and automatic defaults over adding one-off menu items for every small action.
- Maximize automation from event data: event date/time, timezone, ticket capacity, and paid bookings should drive labels, countdown/timer behavior, remaining seats, and notification timing.
- Store timezone once in event/settings context and derive display labels from it instead of forcing repeated manual timezone selection.
- Admin editors should only expose fields that need human judgment; generated labels, counters, queue timings, and derived availability should be computed by services or scripts.
- Compare public event UX against provided competitor/reference URLs, but keep the tone clinical and conference-focused.
- Use `php -S 127.0.0.1:6040 index.php` as the default local server command unless the port is already occupied.
- When the product evolves, update the matching skill files under `.codex/skills/`, `.claude/skills/`, and `.agents/skills/` so future agents inherit new workflow rules.
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
