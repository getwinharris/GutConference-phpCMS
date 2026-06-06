---
name: gutconference-deployment
description: GutConference Deployment Skill. Use when editing Hostinger deployment, Git auto-deploy, environment, permissions, cron, local port, admin secrets storage, production setup documentation, commits, or origin/main release preparation.
---

# GutConference Deployment

- Production target is shared PHP hosting, typically Hostinger Git auto-deploy to `/public_html`.
- Local development default: `php -S 127.0.0.1:6040 index.php`.
- Keep `storage/` writable on host and keep secrets in host `.env` or encrypted admin integrations through `storage/data/adminsecrets.json`.
- Treat pushes to `origin/main` as production releases. Write the PR-style summary and wait for explicit approval before pushing.
- Update this skill when deployment or local-run workflow changes.
