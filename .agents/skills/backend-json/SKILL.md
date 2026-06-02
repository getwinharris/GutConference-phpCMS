---
name: backend-json
description: Use when editing PHP controllers, services, JSON persistence, auth, event CMS services, registrations, notifications, media, or audit behavior.
---

# Backend JSON

- Keep all persistent data in `storage/data/*.json` unless the user requests a separate database migration.
- Update `storage/schema/collections.json` first for collection shape changes.
- Default local server command: `php -S 127.0.0.1:6040 index.php`.
- Update this skill when backend workflow rules change.
