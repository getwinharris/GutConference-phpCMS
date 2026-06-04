# GutConference PHP CMS

GutConference is a PHP/JSON portfolio and booking CMS for `gutconference.online`. The public website is primarily Dr. Praveen Jacob's professional profile platform; events, classes, online consultations, and future course selling are booking options layered on top. It is built for shared PHP hosting with no Node build step and no SQL requirement.

The app is brand-owned: public users cannot post events. Admins create and edit conferences, page sections, speakers, agenda sessions, venues/maps, registrations, support tickets, payment links, notification templates, media, settings, and integrations.

Harris / bapxmediahub is the developer, branding, design, and digital marketing agency context. The client-facing website owner/profile is Dr. Praveen Jacob.

## Stack

- Frontend: modern HTML/CSS templates in `views/`, fed by JSON/schema data through PHP controllers and services.
- Backend: PHP controllers and services in `app/`.
- Data: JSON collections in `storage/data/`.
- Schema contract: `storage/schema/collections.json`.
- Admin: `/admin`.
- Integrations: Razorpay keys, SMTP settings, official Meta WhatsApp Cloud API settings, Google OAuth, and Google Calendar.
- Deployment: Hostinger-style `public_html` PHP hosting with writable `storage/`.

## Current Seed Event

- Event: International Conference On Microbiome, Probiotics & Gut Nutrition
- Slug: `/events/global-gut-summit-2026`
- Edition copy: The Global Gut Summit 2026
- Date/time: 28 June 2026, 9:30 AM - 4:30 PM IST
- Slots: 500 live online access slots seeded from the event record
- Fee: Rs.299
- Organizers: Alpha Naturals & Salus Nutri

## Admin CMS

Admins can manage:

- `events`: event identity, slug URL, CTA, price, organizer/contact, logo, hero image, thumbnail, start/end time, timezone, and slots
- `event_sections`: editable landing-page sections such as outcomes, audience, trust, FAQ, policy, and custom blocks
- `speakers`: speaker cards and session topics
- `sessions`: agenda timeline
- `venues`: online/offline venue, map link, map embed, joining note
- `registrations`: registration/payment status records
- `notification_templates`: email and WhatsApp template metadata
- `notification_queue`: booking, payment, WhatsApp, email, Google Calendar, newsletter, and certificate jobs
- `media_files`: uploaded event, speaker, venue, and shared assets
- `support_tickets`: support-agent tickets

Admin should stay compact and automation-first: event date/time, timezone, capacity, and paid registrations should drive countdowns, time labels, ticket availability, reminders, and notification queue timing so owners edit only the fields that require judgment.

## Customer Flow

- Public navigation uses Login, not Register.
- Customer signup should be Google OAuth-first so calendar access is available before event/class payment.
- Manual password signup exists only as a legacy/dev fallback; password reset remains SMTP-backed for those users.
- Manual users must connect Google before payment so event/class reminders can be written to Google Calendar.
- After successful payment, the PHP backend should queue SMTP email, Meta WhatsApp confirmation, Google Calendar reminders, and certificate delivery jobs.
- Calendar reminders are planned for previous-day morning, previous-day evening, and event-day morning.
- Admin newsletter sending should target opted-in Google-authenticated users and use editable notification templates.
- Do not build course selling yet; keep future course behavior documented and avoid adding course commerce screens until requested.

## Local Development

```bash
php -S 127.0.0.1:6040 index.php
```

Validate:

```bash
php tests/run.php
php tools/validate-project-map.php
php tools/smoke-local.php
```

Regenerate project map docs after changing routes:

```bash
php tools/generate-project-map.php
```

## Deployment

1. Connect the GitHub repository to Hostinger hPanel under Advanced -> Git.
2. Deploy the `main` branch to `/public_html`.
3. Keep `storage/` and `storage/data/` writable by PHP.
4. Configure `.env` on the host for `APP_URL`, admin username, email, and password.
5. Configure Razorpay, SMTP, and Meta WhatsApp Cloud API values in Admin -> Integrations.
6. Add cron later for queue processing when notification sending is implemented.

## Agent Workflow

Follow `AGENTS.md`, `example-Agent.md`, and the repo skill folders before changing backend, schema, admin, frontend, docs, or deployment behavior. Keep JSON storage first, update `storage/schema/collections.json` before changing collection shapes, and use `gutconference-admin` for admin workflow changes.
