# Schema

The schema is `storage/schema/collections.json`.

Core collections:

- `events`: event identity, slug, status, CTA, fee, contact, logo, hero/thumbnail media, real start/end time, timezone, and slot capacity data.
- `event_sections`: admin-editable landing-page blocks.
- `speakers`: event speaker cards.
- `sessions`: agenda timeline.
- `venues`: online/offline venue and map details.
- `registrations`: event/class/appointment registration and payment status records, including certificate status and Google Calendar event ids.
- `notification_templates`: email, WhatsApp, calendar, newsletter, password reset, and certificate template metadata.
- `notification_queue`: queued payment, WhatsApp, email, Google Calendar, newsletter, and certificate jobs.
- `media_files`: uploaded and seeded assets.
- `settings`, `users`, `audit_events`, `contact_submissions`, `support_tickets`: platform support collections.

Customer identity is Google OAuth-first for calendar automation. Manual password users are legacy/dev fallback users and must connect Google before payment. After a successful Razorpay payment, `PurchaseNotificationService` should enqueue payment success email, Meta WhatsApp confirmation, previous-day/event-day calendar reminders, and certificate jobs when applicable.

Update the schema before changing JSON shapes, admin fields, or media fields.
