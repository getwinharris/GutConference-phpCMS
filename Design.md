# GutConference Design Guide

## Comparison

- Current GutConference UI: clinical, light, profile-first, teal/blue/green palette, PHP-rendered templates with JSON-backed content, restrained cards, sticky event countdown, and support-agent panel.
- Current project UI skill: correctly enforces modern HTML/CSS, JSON-backed data, no React/SPA, login-before-payment, Google OAuth-first identity, and browser validation, but it did not yet capture the newer interaction and reference-style rules.
- Neuform reference: denser first viewport, strong conversion path, orange/black contrast, Inter/Geist/JetBrains Mono typography, masked/ambient motion, hover lift, focused auth/CTA controls, and atmospheric effects behind content.

## Adopted Direction

- Use the Neuform reference for interaction tone, density, motion discipline, and focused conversion hierarchy, not for copying its product copy or generic SaaS content.
- Keep GutConference as a clinical conference/profile CMS. Use Dr. Praveen Jacob, event, booking, support, and certificate workflows as the content source.
- Preserve visible text/content meaning during UI-only refactors unless the user explicitly asks for copy changes.

## Tokens

- Primary: `#087FC0`
- Secondary: `#073A66`
- Accent: `#F97316`
- Warm accent: `#FB923C`
- Success/health: `#66B63F`
- Teal: `#39B9A8`
- Background: `#FAF9F9`
- Surface: `#FFFFFF`
- Deep surface: `#191C21`
- Text primary: `#111827`
- Text project: `#10263A`
- Muted: `#5E7487`
- Border: `#D8ECE8`
- Base radius: `8px`
- Card radius: `16px` only for large feature or auth-style reference surfaces; default project cards stay at `8px`.
- Control radius: `8px`
- Pill radius: `9999px`
- Gap: `16px`
- Card padding: `24px`
- Section padding: `80px`

## Typography

- Keep the current project fonts unless a page is being redesigned from the reference direction.
- For reference-inspired pages, use Inter for display moments, Geist or the project body fallback for body copy, and JetBrains Mono or a mono fallback for labels, counters, and technical metadata.
- Do not scale text with viewport width beyond existing `clamp()` patterns. Keep letter spacing at `0` except short uppercase labels.

## Components

- Authentication and CTA controls should keep a focused conversion path, clear button hierarchy, compact input density, and visible validation states.
- Countdown and support-agent states should behave like lightweight HUD elements: active, readable, and secondary to the content.
- Use orange accents for live/urgent states only; do not replace the GutConference teal/blue/green brand system with a full orange theme.
- Avoid generic SaaS card grids, nested cards, decorative orbs, and effects that obscure content.

## Motion

- Prefer masked reveals, subtle hover lift, restrained scan/pulse states, and clear loading/typing indicators.
- Use DOM/CSS and progressive JavaScript for countdowns and support-agent typing states.
- Do not add canvas, WebGL, Three.js, or particles unless explicitly requested for a specific surface.
- Respect `prefers-reduced-motion` for countdown flips, support-agent orbit loaders, carousel timers, hover effects, and ambient motion.

## Validation

- For UI changes, check home, event detail, admin login, and the changed admin resource in a browser.
- For backend/schema adjacency, run `php tests/run.php` and `php tools/validate-project-map.php`.
- Regenerate project map docs only after route/controller/service changes.
