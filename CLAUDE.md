---
description: Claude instructions for agents working on the GutConference PHP/JSON CMS.
globs: *
alwaysApply: true
---

# Claude Operating Guide

Read `AGENTS.md` first. This file exists so Claude-compatible agents enter the same workflow as Codex, OpenCode, and other repo-aware agents.

Use the matching skill under `.claude/skills/<skill-name>/SKILL.md`, then follow the canonical implementation guidance in `.codex/skills/<skill-name>/SKILL.md`. For admin work use `gutconference-admin`.

## Required Habits

- Keep the backend framework focused on GutConference: PHP controllers/services, JSON storage, schema, admin event tools, and shared-hosting execution.
- Build UI as modern HTML/CSS driven by JSON-backed data and schema-driven admin forms. PHP should do routing, service orchestration, and data binding for shared hosting; it should not be treated as the UI design layer. Do not introduce React, a SPA fallback, second frontend, public event posting, SQL migration, or direct all-user JSON access unless the user explicitly asks for that architecture change.
- Keep admin compact and automation-first: derive countdowns, labels, timezones, remaining tickets, and notification timing from event/settings data instead of adding manual fields.
- When a code change creates a reusable rule, update the matching skill so future agents inherit it.
- Validate changed PHP, run the project checks, and inspect changed UI like a user in the browser.
- Before finishing, search the changed workflow for placeholders, dead buttons, stale labels, duplicate fallbacks, and incomplete wiring.
