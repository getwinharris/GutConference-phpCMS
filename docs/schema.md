# Schema

The schema is `storage/schema/collections.json`.

Core collections:

- `events`: event identity, slug, status, CTA, fee, contact, logo, and hero data.
- `event_sections`: admin-editable landing-page blocks.
- `speakers`: event speaker cards.
- `sessions`: agenda timeline.
- `venues`: online/offline venue and map details.
- `registrations`: registration and payment status records.
- `notification_templates`: email and WhatsApp template metadata.
- `notification_queue`: queued reminders and payment/booking notifications.
- `media_files`: uploaded and seeded assets.
- `settings`, `users`, `audit_events`, `contact_submissions`: platform support collections.

Update the schema before changing JSON shapes, admin fields, or media fields.
