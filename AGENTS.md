---
description: Repository instructions for agents working on the GutConference PHP/JSON CMS.
globs: *
alwaysApply: true
---

# Agent Operating Guide

This repo is a GutConference Online PHP/JSON full-stack CMS for shared hosting. It is not a SPA, not a SQL app, and not a public event marketplace.

## Core Shape

- Frontend: PHP templates in `views/`.
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
5. The narrow skill under `.codex/skills/<skill-name>/SKILL.md`, `.claude/skills/<skill-name>/SKILL.md`, or `.agents/skills/<skill-name>/SKILL.md` that matches the task.

## Rules

- Keep JSON storage first. Do not introduce SQL/Postgres/MySQL unless the user explicitly asks for a separate migration.
- Update `storage/schema/collections.json` before changing collection shapes, admin fields, media fields, or agent-visible context.
- Keep route -> controller -> service -> JSON-store boundaries.
- Do not add React, CDN React, or a SPA fallback.
- Do not create a second frontend.
- Public users must not be able to create conferences. Only admins create and publish events.
- Admin mutations should be auditable.
- Event media should use the media library picker/upload flow.
- Environment and storage permission changes belong in `/admin/environment`.
- Compare public event UX against provided competitor/reference URLs, but keep the tone clinical and conference-focused.
- Use `php -S 127.0.0.1:6040 index.php` as the default local server command unless the port is already occupied.
- When the product evolves, update the matching skill files under `.codex/skills/`, `.claude/skills/`, and `.agents/skills/` so future agents inherit new workflow rules.

## Validation

Run the smallest useful validation for the change:

```bash
php -l path/to/changed.php
php tests/run.php
php tools/validate-project-map.php
php tools/smoke-local.php
```

For UI changes, use a browser workflow and check home, event detail, admin login, and the changed admin resource.
