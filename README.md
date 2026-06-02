# GutConference PHP CMS

GutConference is a PHP/JSON conference CMS for `gutconference.online`. It is built for shared PHP hosting with no Node build step and no SQL requirement.

The app is brand-owned: public users cannot post events. Admins create and edit conferences, page sections, speakers, agenda sessions, venues/maps, registrations, payment links, notification templates, media, settings, and integrations.

## Stack

- Frontend: PHP-rendered templates in `views/`.
- Backend: PHP controllers and services in `app/`.
- Data: JSON collections in `storage/data/`.
- Schema contract: `storage/schema/collections.json`.
- Admin: `/admin`.
- Integrations: Razorpay keys, SMTP settings, and official Meta WhatsApp Cloud API settings.
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
- `notification_queue`: future booking, payment, and reminder messages
- `media_files`: uploaded event, speaker, venue, and shared assets

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

Follow `AGENTS.md`, `example-Agent.md`, and the repo skill folders before changing backend, schema, admin, frontend, docs, or deployment behavior. Keep JSON storage first and update `storage/schema/collections.json` before changing collection shapes.
