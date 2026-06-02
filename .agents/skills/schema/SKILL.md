---
name: schema
description: Use when changing JSON database collections, fields, admin forms, media fields, or agent context payloads.
---

# JSON Schema

- `storage/schema/collections.json` is the source of truth for collection shapes, admin fields, media fields, and agent-visible fields.
- Update schema before code or seed JSON changes.
- Keep event slug, sections, speakers, sessions, venues, registrations, and notification collections admin-managed.
- Update this skill when schema workflow rules change.
