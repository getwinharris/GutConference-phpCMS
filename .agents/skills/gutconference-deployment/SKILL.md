---
name: gutconference-deployment
description: GutConference Deployment Skill. Use when editing Hostinger deployment, Git auto-deploy, environment, permissions, cron, local port, admin secrets storage, production setup documentation, commits, or origin/main release preparation.
---

# GutConference Deployment

- Production target is shared PHP hosting, typically Hostinger Git auto-deploy to `/public_html`.
- Local development default: `php -S 127.0.0.1:6040 index.php`.
- Keep `storage/` writable on host and keep secrets in host/local `.env` or encrypted admin integrations through `storage/data/adminsecrets.json`.
- Do not track `.env` or `.env.example`. GitHub `main` is the source of truth for their removal; if a rebase reports modify/delete conflicts for env files, keep the remote deletion and continue with secrets supplied outside Git.
- Before comparing or rebasing for release, run `git fetch origin main` and use the freshly fetched `origin/main` / `FETCH_HEAD` as the remote baseline instead of assuming the local tracking ref is current.
- Treat pushes to `origin/main` as production releases. Write the PR-style summary and wait for explicit approval before pushing.
- Update this skill when deployment or local-run workflow changes.
