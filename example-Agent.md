# Example Agent Workflow

Use this file as the operating note for coding agents working on the GutConference Online PHP/JSON CMS.

## Product Shape

- The app runs from ordinary PHP shared hosting.
- The frontend is modern HTML/CSS in `views/`, fed by JSON/schema data through PHP controllers and services.
- The backend is PHP controllers and services in `app/`.
- The data store is JSON under `storage/data/`.
- The schema contract is `storage/schema/collections.json`.
- Built-in skills live under `.agents/skills/`.
- Broad cross-cutting work uses the `gutconference-orchestrator` skill.
- Admin work uses the `gutconference-admin` skill.
- Broad UI work uses `Design.md` plus the `gutconference-frontend` skill.
- There is no SPA fallback. Unknown routes return the PHP 404 page.

## Required Workflow

1. Read `AGENTS.md`, `README.md`, `docs/PROJECT_MAP.md`, and `storage/schema/collections.json`.
2. Use the project map before editing routes, controllers, services, or pages.
3. Update schema before changing JSON collections or admin fields.
4. For broad or reference-driven UI changes, read `Design.md`. Make the surface modern HTML/CSS with JSON-backed data and schema-driven forms. PHP should run the shared-hosted app, route requests, and bind data; do not use PHP as the visual design model. Do not add React, CDN React, or a SPA shell.
5. For UI-only refactors, preserve visible text/content meaning unless the user explicitly asks for copy changes.
6. Run locally when browser testing is needed:

```bash
php -S 127.0.0.1:6040 index.php
```

7. Validate before finishing:

```bash
php tests/run.php
php tools/validate-project-map.php
php tools/smoke-local.php
```

8. Regenerate project map docs after route changes:

```bash
php tools/generate-project-map.php
```

## Backend Primitives

- Auth: `AuthController`, `AuthService`, and encrypted admin credentials through `SecretService`.
- JSON database: `JsonStoreService`, `ResourceService`, `storage/data/*.json`.
- Schema: `SchemaService`, `storage/schema/collections.json`.
- Events: `EventService`, `events`, `event_sections`, `speakers`, `sessions`, `venues`.
- Media: `MediaService`, `/admin/media`, `assets/images/media`.
- Integrations: `SecretService`, encrypted `storage/data/adminsecrets.json`, Razorpay, SMTP, Meta WhatsApp Cloud API settings.
- Audit: `AuditLogService`.
- Skills: update the matching GutConference `.agents/skills/` files when the product workflow evolves, especially for default ports, schema rules, admin surfaces, deployment, and browser/UI review expectations.

## Admin Automation Principles

- Keep admin navigation compact by grouping related owner workflows instead of adding a separate menu item for every helper action.
- Use event date, start/end time, timezone, capacity, and paid registrations as the source for automatic countdowns, labels, availability, and notification timings.
- Store timezone once in event or settings context, then derive display labels and timers from that value.
- Only expose fields in admin when manual editing is genuinely needed. Generate derived labels, remaining ticket counts, queue timings, and reminders through PHP services or scripts.
- When working on a PR, inspect previous PR comments, keep fixes on the same remote branch when appropriate, and send requested follow-up through the configured remote email or project channel.

## Deployment

Use Hostinger-style Git auto deployment from GitHub to `/public_html`, with writable `storage/` and encrypted admin integrations. Do not use project `.env` files or Vercel as the primary production host.

## Do Not Reintroduce

- Public event posting.
- React/CDN React or SPA fallback.
- Legacy catalog/account UX from the source project unless the user explicitly asks.
- SQL storage unless requested as a separate migration.
