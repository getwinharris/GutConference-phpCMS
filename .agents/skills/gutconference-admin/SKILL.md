---
name: gutconference-admin
description: GutConference Admin Skill. Use when editing owner/admin pages, CRUD forms, media library, environment editor, permissions, audit log, integrations, automation, or admin navigation.
---

# GutConference Admin Skill

- Admin is the only place where conferences, sections, speakers, agenda sessions, venues/maps, registrations, payments, support tickets, newsletters, certificates, and notifications are managed.
- Do not add public event posting.
- Keep admin labels and forms conference-specific and owner-focused.
- Build admin UI as modern HTML/CSS fed by JSON collections and schema-driven forms. PHP should route, authorize, validate, audit, and bind data for shared hosting; it should not drive the visual design model. Do not add React, CDN React, SPA shells, or a second frontend.
- Keep admin navigation lean. Group Events & Classes, Page Sections, Speakers, Agenda, Venues & Maps, and Registrations under Event Management.
- Prefer automation over manual admin fields. Event date, start/end time, timezone, ticket capacity, and paid registrations should drive countdowns, display labels, remaining seats, reminders, and notification queue timing.
- Store timezone once in event/settings context, then derive timers and labels from it.
- Only expose fields in admin when the owner needs to make a judgment; generated labels, counters, availability, and queue timings should be handled by PHP services or scripts.
- Integrations must expose Razorpay, SMTP, official Meta WhatsApp Cloud API, Google OAuth, and Google Calendar configuration.
- Each event/class must expose a payment mode: external Razorpay payment page link or internal Razorpay integration.
- Notification templates should cover payment success, password reset, WhatsApp confirmations, calendar reminders, newsletter, and certificate delivery.
- Support Agent must remain visible in admin navigation when support ticket data exists.
- Update schema before changing admin collection fields.
- When working from a PR, review earlier PR comments, fix the same remote branch when appropriate, and send requested follow-up through the configured remote email or project channel.
- Update this skill when admin workflow rules change.
