# Example Agent Workflow

Use this file as the operating note for coding agents working on the GutConference Online PHP/JSON CMS.

## Product Shape

- The app runs from ordinary PHP shared hosting.
- The frontend is PHP-rendered templates in `views/`.
- The backend is PHP controllers and services in `app/`.
- The data store is JSON under `storage/data/`.
- The schema contract is `storage/schema/collections.json`.
- Built-in skills live under `.codex/skills/`, `.claude/skills/`, and `.agents/skills/`.
- There is no SPA fallback. Unknown routes return the PHP 404 page.

## Required Workflow

1. Read `AGENTS.md`, `README.md`, `docs/PROJECT_MAP.md`, and `storage/schema/collections.json`.
2. Use the project map before editing routes, controllers, services, or pages.
3. Update schema before changing JSON collections or admin fields.
4. Run locally when browser testing is needed:

```bash
php -S 127.0.0.1:6040 index.php
```

5. Validate before finishing:

```bash
php tests/run.php
php tools/validate-project-map.php
php tools/smoke-local.php
```

6. Regenerate project map docs after route changes:

```bash
php tools/generate-project-map.php
```

## Backend Primitives

- Auth: `AuthController`, `AuthService`, `.env` admin credentials.
- JSON database: `JsonStoreService`, `ResourceService`, `storage/data/*.json`.
- Schema: `SchemaService`, `storage/schema/collections.json`.
- Events: `EventService`, `events`, `event_sections`, `speakers`, `sessions`, `venues`.
- Media: `MediaService`, `/admin/media`, `assets/images/media`.
- Integrations: `SecretService`, Razorpay, SMTP, Meta WhatsApp Cloud API settings.
- Audit: `AuditLogService`.
- Skills: update the matching `.codex/skills/`, `.claude/skills/`, and `.agents/skills/` files when the product workflow evolves, especially for default ports, schema rules, admin surfaces, deployment, and browser/UI review expectations.

## Deployment

Use Hostinger-style Git auto deployment from GitHub to `/public_html`, with writable `storage/` and host-managed `.env` secrets. Do not use Vercel as the primary production host.

## Do Not Reintroduce

- Public event posting.
- React/CDN React or SPA fallback.
- Legacy catalog/account UX from the source project unless the user explicitly asks.
- SQL storage unless requested as a separate migration.
