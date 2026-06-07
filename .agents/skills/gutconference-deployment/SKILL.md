---
name: gutconference-deployment
description: GutConference Deployment Skill. Use when editing Hostinger deployment, Git auto-deploy, environment, permissions, cron, local port, admin secrets storage, production setup documentation, commits, or origin/main release preparation.
---

# GutConference Deployment

- Production target is shared PHP hosting, typically Hostinger Git auto-deploy to `/public_html`.
- Keep `storage/` writable on host and keep secrets in encrypted admin integrations through `storage/data/adminsecrets.json` or server-level environment configuration outside the repo.
