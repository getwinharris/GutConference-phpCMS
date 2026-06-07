---
name: gutconference-github-issue-pr
description: GutConference GitHub Issue and PR Skill. Use when creating GitHub issues, filing bugs, requesting features, opening pull requests, or managing the issue/PR lifecycle.
---

# GutConference GitHub Issue & PR

## Issue Creation

### Step 1 — Classify

Classify the request as one of:
- **Bug** — something is broken or producing wrong output
- **Feature** — new functionality or enhancement
- **Enhancement** — improvement to existing functionality
- **Docs** — documentation change only

### Step 2 — Check Duplicates

```bash
gh issue list --search "keywords from the request" --limit 10
```

If a duplicate exists, link to it and skip creation.

### Step 3 — Gather Information

Before drafting, verify the code. Read affected files and quote:
- File paths and line numbers demonstrating the current state
- The project map edge or gap node from `docs/systematic-map.mmd` that justifies the issue
- The schema field from `storage/schema/collections.json` that the change must respect

If any of these are missing, investigate the code first. Issues must be backed by verified files, not speculation.

### Step 4 — Draft Title and Body

**Title format:** `[Type]: Short description`

Examples:
- `[Bug]: Auth layout logo missing decorative line below brand name`
- `[Feature]: Add per-user data isolation section to privacy policy`
- `[Enhancement]: Deduplicate skill instructions across AGENTS.md and skills`
- `[Docs]: Update README with new skill references`

**Body format by type:**

**Bug:**
```markdown
## What's the bug?

[Description of what went wrong]

## Current state

[File paths and line numbers showing the problem]

## Expected behavior

[What should happen instead]

## Environment (optional)

[Any relevant context]
```

**Feature / Enhancement:**
```markdown
## What do you want?

[Description of the feature or enhancement]

## Why?

[Problem it solves or value it adds]

## Current state

[File paths and line numbers showing the gap]

## Fix outline

[High-level approach referencing existing files and services]
```

**Docs:**
```markdown
## What needs updating?

[Description of the documentation change]

## Current state

[File paths and line numbers]
```

### Step 5 — Confirm Before Creating

Show the draft title and body to the user. Ask for edits. Get explicit confirmation before running `gh issue create`.

### Step 6 — Create Issue

```bash
gh issue create --title "[Bug]: description" --body "## What's the bug?

Description here

## Current state

File: path/to/file.php:42

## Expected behavior

Expected here" --label "bug"
```

Use `--label` to apply available labels: `bug`, `enhancement`, `documentation`.

### Step 7 — Report

Display the created issue URL to the user.

---

## Pull Request Creation

### Step 1 — Prerequisites

Verify before proceeding:
1. `gh` CLI is installed: `gh --version`
2. Authenticated: `gh auth status`
3. Working directory is clean: `git status`
4. All commits are pushed

### Step 2 — Analyze Changes

First, refresh the remote tracking ref to avoid stale local state:

```bash
git fetch --prune origin
```

Then check what commits are waiting to be pushed:

```bash
git log --oneline origin/main..HEAD
git diff origin/main..HEAD --stat
```

Read the diff to understand what changed and why.

### Step 3 — Draft PR Title and Body

**Title:** Natural-language summary of the change (not commit format).

Examples:
- `Enhance /privacy and /terms pages with data isolation content`
- `Deduplicate skill instructions across AGENTS.md and skills`

**Body:**
```markdown
## Summary

[What this PR does and why]

## Changed areas

- **Controllers:** [list]
- **Services:** [list]
- **Views:** [list]
- **Schema:** [list]
- **Assets:** [list]
- **Docs:** [list]

## New files / Deleted files

[Call out any explicitly]

## Risks / Breaking changes

[Any known risks or migration notes]

## Verification completed

- [ ] `php -l` lint clean
- [ ] `php tests/run.php` pass
- [ ] `php tools/generate-project-map.php` regenerated
- [ ] `php tools/validate-project-map.php` valid
- [ ] `php tools/smoke-local.php` pass
```

### Step 4 — Confirm Before Creating

Show the PR title and body to the user. Get explicit confirmation.

### Step 5 — Create PR

```bash
gh pr create --title "Enhance privacy policy with data isolation" --body "## Summary

Adds per-user data isolation section to privacy policy.

## Changed areas

- **Views:** views/public/privacy.php
- **CSS:** assets/css/index.css

## Verification completed

- [ ] php -l lint clean
- [ ] php tests/run.php pass" --base main
```

### Step 6 — Report

Display the created PR URL. Remind user that pushes to `main` auto-deploy to gutconference.online.

---

## Safety Rules

- Never create an issue or PR without explicit user confirmation
- Never push to `main` without approval — it auto-deploys to production
- Always verify code before writing issue body — quote file paths and line numbers
- Check for duplicates before creating issues
- Use full repository issue references when linking: `getwinharris/GutConference-phpCMS#15`
- Use issue-closing keywords in PR body when intended: `Closes #15`, `Fixes #15`
