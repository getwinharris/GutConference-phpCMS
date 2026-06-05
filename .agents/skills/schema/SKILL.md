---
name: schema
description: Use when changing JSON database collections, fields, admin forms, media fields, or agent context payloads.
---

# JSON Schema

- `storage/schema/collections.json` is the source of truth for collection shapes, admin fields, media fields, and agent-visible fields.
- Update schema before code or seed JSON changes.
- Keep event slug, payment mode, payment page link, sections, speakers, speaker credentials, sessions, venues, registrations, and notification collections admin-managed.
- Prefer source fields over duplicate manual fields: event date/time, timezone, capacity, and registration status should support derived labels, countdowns, availability, and notification scheduling.
- If derived behavior needs persisted config, model it in schema before changing admin forms or JSON seed data.
- Update this skill when schema workflow rules change.
