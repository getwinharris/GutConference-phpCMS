---
name: gutconference-frontend
description: GutConference Frontend Skill. Use when editing public conference, event landing, admin, contact, auth, checkout, support chat, countdown, CSS, progressive JavaScript, or CMS templates.
---

# GutConference Frontend

- Primary website goal: premium portfolio for Dr. Praveen Jacob. Events/classes/appointments are booking options, not the whole brand.
- Harris / bapxmediahub is the developer, branding, design, and digital marketing agency context. Do not present Harris as the client or website owner.
- Keep the UI fully rebranded for GutConference and Dr. Praveen Jacob using the supplied logo/theme.
- Build UI as modern HTML/CSS fed by JSON/schema data. PHP may route, load data, and include templates for shared hosting, but visual structure, styling, and interaction decisions should be designed as HTML/CSS.
- Read `Design.md` before broad UI changes. Use its GutConference-adapted Neuform comparison for density, motion, color, and component direction.
- Preserve existing visible text/content meaning during UI-only refactors unless the user explicitly requests copy changes.
- Use small progressive JavaScript only for browser behavior that cannot be handled by HTML/CSS and server-provided JSON data.
- Do not add React, CDN React, SPA shells, or a second frontend.
- Compare event pages against provided competitor/reference URLs, but keep the result corporate, clinical, luxury, credible, and profile-first.
- Use quiet luxury cards: clear hierarchy, soft light backgrounds, restrained shadows, one primary action per card, and no nested card clutter.
- For small interactive surfaces such as countdowns and support-agent typing states, use DOM/CSS with purposeful motion, clear state feedback, and `prefers-reduced-motion`; do not introduce canvas/WebGL unless explicitly requested.
- Use the supplied logo assets from `assets/images/media/` unless the user provides replacements.
- Public top-nav CTA is Login. Ticket/course payments must require login first, then use the event/class payment mode: external Razorpay payment page or internal Razorpay integration.
- Signup/customer identity should be Google OAuth-first. Manual signup is only a legacy/dev fallback and must connect Google before payment.
- Speaker cards should render data-driven photos and credentials, with a polished initials fallback when a photo is not yet uploaded.
- Validate with the in-app browser for local pages when UI changes are significant, including home, event detail, admin login, and the changed admin resource.
- Default local server command: `php -S 127.0.0.1:6040 index.php`.
- Update this skill when frontend workflow or product rules change.
