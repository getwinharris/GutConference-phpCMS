---
name: frontend-php
description: Use when editing public conference, event landing, admin, contact, auth, or CMS templates.
---

# PHP Frontend

- Primary website goal: premium portfolio for Dr. Praveen Jacob. Events/classes/appointments are booking options, not the whole brand.
- Harris / bapxmediahub is the developer, branding, design, and digital marketing agency context. Do not present Harris as the client or website owner.
- Keep the UI fully rebranded for GutConference and Dr. Praveen Jacob using the supplied logo/theme.
- Compare event pages against provided competitor/reference URLs, but keep the result corporate, clinical, luxury, credible, and profile-first.
- Use quiet luxury cards: clear hierarchy, soft light backgrounds, restrained shadows, one primary action per card, and no nested card clutter.
- Use the supplied logo assets from `assets/images/media/` unless the user provides replacements.
- Public top-nav CTA is Login. Ticket/course payments must require login first, then direct Razorpay payment.
- Signup/customer identity should be Google OAuth-first. Manual signup is only a legacy/dev fallback and must connect Google before payment.
- Validate with the in-app browser for local pages when UI changes are significant.
- Default local server command: `php -S 127.0.0.1:6040 index.php`.
- Update this skill when frontend workflow or product rules change.
