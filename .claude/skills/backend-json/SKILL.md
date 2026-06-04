---
name: backend-json
description: Use when editing PHP controllers, services, JSON persistence, auth, event CMS services, registrations, notifications, media, or audit behavior.
---

# Backend JSON

- Keep all persistent data in `storage/data/*.json` unless the user requests a separate database migration.
- Update `storage/schema/collections.json` first for collection shape changes.
- Backend behavior must stay PHP-runnable on the PHP server. Frontend must be modern HTML/CSS fed by JSON-backed data; PHP handles routing, services, validation, persistence, and shared-hosting data binding.
- Customer signup is Google OAuth-first so Google Calendar reminders can be created for buyers without a second OAuth step.
- Manual password users are legacy/dev fallback users. Keep SMTP forgot-password reset support for them, but require Google connection before payment.
- Payment success should queue SMTP email, Meta WhatsApp, Google Calendar reminder, and certificate jobs through JSON-backed services.
- Calendar reminders for event/class purchases: previous day morning, previous day evening, and event day morning.
- Admin newsletter should target opted-in Google-authenticated users and use template content that can be enhanced before sending.
- Derive event display labels, countdown/timer values, remaining ticket counts, and notification timings from event/settings data instead of storing duplicate manual values where possible.
- Default local server command: `php -S 127.0.0.1:6040 index.php`.
- Update this skill when backend workflow rules change.
