---
name: gutconference-orchestrator
description: GutConference Orchestrator Skill. Use for broad GutConference work spanning UI, backend, schema, admin, Razorpay, analytics, product design, creative direction, CodeRabbit-style review, validation, PR summaries, commits, or production-release preparation.
---

# GutConference Orchestrator

- Start by reading `AGENTS.md`, `README.md`, `storage/schema/collections.json`, `docs/PROJECT_MAP.md`, `example-Agent.md`, and the narrow GutConference skill for the touched area.
- Keep PHP as routing/controllers/services/shared-hosting data binding. Keep UI modern HTML/CSS fed by JSON/schema data.
- Do not edit external plugin skills. Use Product Design, Build Web Apps, Data Analytics, Creative Production, Game Studio, Razorpay, and CodeRabbit as workflow lenses when relevant.
- For UI work, read `Design.md`; preserve visible text/content meaning unless copy changes are explicitly requested.
- For small interactive UI such as countdowns, support typing states, and focused conversion controls, use DOM/CSS and progressive JavaScript with purposeful motion and `prefers-reduced-motion`.
- For backend and admin changes, update schema first when collection fields/admin fields/media fields/agent context change.
- For Razorpay, keep external payment page and internal verified checkout modes; verify internal payments server-side before marking registrations paid or queuing notifications.
- For analytics, use admin/reporting insights from JSON-backed data and configured tags; do not introduce SQL or a new analytics stack unless requested.
- After route/controller/service changes, run or regenerate the project map as appropriate.
- Before release comparison, rebase, or push preparation, ensure `origin` is `https://github.com/getwinharris/GutConference-phpCMS.git`, run `git fetch origin main`, and verify against the freshly fetched GitHub baseline. If the remote moved, rebase local commits onto it before validation.
- Preserve the env migration: do not use, create, restore, or track `.env`, `.env.example`, or `.env.*` files. Secrets must be supplied through encrypted admin integrations or server-level environment configuration outside the repo. Resolve env modify/delete conflicts by keeping the GitHub deletion.
- Before commit or release prep, write a PR-style summary with changed areas, new/deleted files, risks, migration notes, and verification.
- Never push to `origin/main` without explicit approval because it deploys to `gutconference.online`.
