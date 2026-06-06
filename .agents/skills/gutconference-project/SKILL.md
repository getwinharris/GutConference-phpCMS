---
name: gutconference-project
description: GutConference Project Skill. Use when contributing broadly to the GutConference PHP/JSON CMS, coordinating frontend, backend, schema, admin, integrations, docs, validation, PR summaries, commits, or production-release preparation.
---

# GutConference Project

Read `AGENTS.md`, `README.md`, `storage/schema/collections.json`, and `docs/PROJECT_MAP.md` before changing code.

- Keep JSON storage first; do not add SQL unless explicitly requested.
- Keep route -> controller -> service -> JSON-store boundaries.
- Use modern HTML/CSS UI with JSON-backed data and schema-driven admin forms. PHP runs the shared-hosted app and binds data; do not add React or a SPA frontend.
- For UI-only requests, preserve visible text/content meaning unless the user explicitly asks for copy changes. Change structure, styling, and interactivity only.
- For admin automation, prefer deriving labels, timers, remaining tickets, and notification timing from event/settings data.
- For broad UI/backend/payment/review work, use `gutconference-orchestrator` first, then the narrow GutConference skill that matches the file area.
- Before finishing broad work, write a PR-style summary, run the smallest useful validation, and commit only after validation passes or failures are documented.
- Before release comparison, rebase, or push preparation, fetch GitHub `main` with `git fetch origin main` and verify against the freshly fetched remote baseline, not a stale local `origin/main` view.
- Keep `.env` and `.env.example` untracked. Secrets belong in host/local `.env` or encrypted admin integrations, and env-file conflicts during rebase should resolve by preserving the remote deletion.
- Default local server command: `php -S 127.0.0.1:6040 index.php`.
- Update the matching skill files whenever product workflow rules evolve.
