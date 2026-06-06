---
name: gutconference-deployment
description: GutConference Deployment Skill. Use when editing Hostinger deployment, Git auto-deploy, environment, permissions, cron, local port, admin secrets storage, production setup documentation, commits, or origin/main release preparation.
---

# GutConference Deployment

- Production target is shared PHP hosting, typically Hostinger Git auto-deploy to `/public_html`.
- Local development default: `php -S 127.0.0.1:6040 index.php`.
- Keep `storage/` writable on host and keep secrets in encrypted admin integrations through `storage/data/adminsecrets.json` or server-level environment configuration outside the repo.
- Do not use, create, restore, or track `.env`, `.env.example`, or `.env.*` files. GitHub `main` is the source of truth for their removal; if a rebase reports modify/delete conflicts for env files, keep the remote deletion and continue with secrets supplied outside Git.
- Before comparing or rebasing for release, ensure `origin` is `https://github.com/getwinharris/GutConference-phpCMS.git`, then force-refresh GitHub main with `git fetch --prune origin +refs/heads/main:refs/remotes/origin/main` and `git branch --set-upstream-to=origin/main main`. Treat `origin/main` as valid only after this refresh; never trust a stale local remote-tracking ref from this machine.
- Treat pushes to `origin/main` as production releases. Write the PR-style summary and wait for explicit approval before pushing.
- Update this skill when deployment or local-run workflow changes.
