---
name: deployment
description: Use when editing Hostinger deployment, Git auto-deploy, environment, permissions, cron, local port, or production setup documentation.
---

# Deployment

- Production target is shared PHP hosting, typically Hostinger Git auto-deploy to `/public_html`.
- Local development default: `php -S 127.0.0.1:6040 index.php`.
- Keep `storage/` writable on host and keep secrets in host `.env` or admin integrations.
- Update this skill when deployment or local-run workflow changes.
