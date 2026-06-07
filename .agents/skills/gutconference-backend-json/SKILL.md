---
name: gutconference-backend-json
description: GutConference Backend JSON Skill. Use when editing PHP controllers, services, JSON persistence, auth, event CMS services, registrations, notifications, media, audit behavior, payment verification, support-agent data, or admin secrets.
---

# GutConference Backend JSON

- Backend behavior must stay PHP-runnable on the PHP server. Frontend must be modern HTML/CSS fed by JSON-backed data; PHP handles routing, services, validation, persistence, and shared-hosting data binding.
- Admin-managed integration secrets live in encrypted `storage/data/adminsecrets.json` through `SecretService`; keep legacy `settings.secrets.json` readable for migration and never expose secrets to public views or commits.
- Calendar reminders for event/class purchases: previous day morning, previous day evening, and event day morning.
- Admin newsletter should target opted-in Google-authenticated users and use template content that can be enhanced before sending.
