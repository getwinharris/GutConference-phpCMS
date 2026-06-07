# Systematic Project Map

_Generated: 2026-06-07T18:54:31+05:30_

## Summary

- **routes**: 65
- **controllers**: 6
- **services**: 23
- **views**: 25
- **integrations**: 2
- **schema_collections**: 15
- **storage_collections**: 17
- **gap_count**: 10

## Gaps (sorted by severity)

- **[high] controller_unwired** — Controller BaseController is declared in app/Controllers/ but not referenced by any route.
- **[high] service_unwired** — Service EmailTemplateService is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage).
- **[high] service_unwired** — Service EnvService is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage).
- **[high] service_unwired** — Service GeminiModelRouter is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage).
- **[high] service_unwired** — Service SmtpMailer is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage).
- **[high] service_unwired** — Service UserContextService is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage).
- **[low] storage_collection_unused** — Collection settings.secrets has no service that reads it (static search). May still be used via a generic path.
- **[medium] storage_file_no_schema** — storage/data/adminsecrets.json exists on disk but has no schema entry in storage/schema/collections.json.
- **[medium] storage_file_no_schema** — storage/data/settings.secrets.json exists on disk but has no schema entry in storage/schema/collections.json.
- **[medium] view_unwired** — View views/public/404.php is on disk but not bound to any route in the registry.

## Controllers

- `AdminController` (42 methods) — wired
- `AuthController` (9 methods) — wired
- `BaseController` (0 methods) — NOT in route registry
- `PaymentController` (2 methods) — wired
- `PublicController` (9 methods) — wired
- `SupportController` (2 methods) — wired

## Services

- `AgentContextService` (2 methods) — wired
- `AuditLogService` (3 methods) — wired
- `AuthService` (3 methods) — wired
- `ContactService` (8 methods) — wired
- `EmailTemplateService` (2 methods) — NOT in route registry
- `EnvService` (2 methods) — NOT in route registry
- `EventService` (14 methods) — wired
- `GeminiModelRouter` (3 methods) — NOT in route registry
- `JsonStoreService` (5 methods) — wired
- `MediaService` (3 methods) — wired
- `NotificationQueueService` (6 methods) — wired
- `PasswordResetService` (3 methods) — wired
- `PaymentService` (2 methods) — wired
- `ProjectMapService` (0 methods) — wired
- `PurchaseNotificationService` (3 methods) — wired
- `ResourceService` (4 methods) — wired
- `SchemaService` (4 methods) — wired
- `SecretService` (3 methods) — wired
- `SettingsService` (3 methods) — wired
- `SmtpMailer` (4 methods) — NOT in route registry
- `StoragePermissionService` (2 methods) — wired
- `SupportAgentService` (2 methods) — wired
- `UserContextService` (3 methods) — NOT in route registry

## Integrations

- `GoogleOAuthClient` (5 methods) at `integrations/google-oauth/GoogleOAuthClient.php`
- `RazorpayClient` (2 methods) at `integrations/razorpay/RazorpayClient.php`

## Views

- `views/admin/branding.php` (3524 bytes)
- `views/admin/dashboard.php` (1236 bytes)
- `views/admin/environment.php` (3391 bytes)
- `views/admin/integrations.php` (8082 bytes)
- `views/admin/list.php` (2907 bytes)
- `views/admin/media.php` (1854 bytes)
- `views/admin/project-map.php` (2297 bytes)
- `views/admin/resource.php` (12708 bytes)
- `views/admin/settings.php` (2509 bytes)
- `views/layouts/admin.php` (13847 bytes)
- `views/layouts/app.php` (17095 bytes)
- `views/partials/support-widget.php` (5778 bytes)
- `views/public/404.php` (575 bytes)
- `views/public/checkout.php` (4543 bytes)
- `views/public/contact.php` (2531 bytes)
- `views/public/dashboard.php` (5606 bytes)
- `views/public/event.php` (38545 bytes)
- `views/public/events.php` (2536 bytes)
- `views/public/forgot-password.php` (838 bytes)
- `views/public/home.php` (16034 bytes)
- `views/public/login.php` (1520 bytes)
- `views/public/privacy.php` (1273 bytes)
- `views/public/reset-password.php` (1138 bytes)
- `views/public/signup.php` (1781 bytes)
- `views/public/terms.php` (1317 bytes)

## Schema collections

- `audit_events` — 6 fields, file `storage/data/audit_events.json`
- `contact_submissions` — 9 fields, file `storage/data/contact_submissions.json`
- `event_sections` — 9 fields, file `storage/data/event_sections.json`
- `events` — 31 fields, file `storage/data/events.json`
- `media_files` — 7 fields, file `storage/data/media_files.json`
- `notification_queue` — 13 fields, file `storage/data/notification_queue.json`
- `notification_templates` — 8 fields, file `storage/data/notification_templates.json`
- `publishers` — 9 fields, file `storage/data/publishers.json`
- `registrations` — 13 fields, file `storage/data/registrations.json`
- `sessions` — 7 fields, file `storage/data/sessions.json`
- `settings` — 3 fields, file `storage/data/settings.json`
- `speakers` — 10 fields, file `storage/data/speakers.json`
- `support_tickets` — 7 fields, file `storage/data/support_tickets.json`
- `users` — 13 fields, file `storage/data/users.json`
- `venues` — 9 fields, file `storage/data/venues.json`

## Storage data files

- `adminsecrets` — 2 records, 581 bytes
- `audit_events` — 2 records, 782 bytes
- `contact_submissions` — 9 records, 2927 bytes
- `event_sections` — 5 records, 2776 bytes
- `events` — 1 records, 1597 bytes
- `media_files` — 12 records, 4254 bytes
- `notification_queue` — 4 records, 3582 bytes
- `notification_templates` — 11 records, 3504 bytes
- `publishers` — 2 records, 1224 bytes
- `registrations` — 1 records, 471 bytes
- `sessions` — 11 records, 3941 bytes
- `settings` — 1 records, 746 bytes
- `settings.secrets` — 2 records, 195 bytes
- `speakers` — 9 records, 7667 bytes
- `support_tickets` — 0 records, 2 bytes
- `users` — 1 records, 334 bytes
- `venues` — 1 records, 331 bytes

## Collection usage (static search)

- `adminsecrets` ← ProjectMapService
- `audit_events` ← AuditLogService, ProjectMapService
- `contact_submissions` ← ContactService, ProjectMapService, UserContextService
- `event_sections` ← EventService, ProjectMapService, SupportAgentService
- `events` ← AgentContextService, EventService, MediaService, NotificationQueueService, ProjectMapService, SupportAgentService
- `media_files` ← MediaService, ProjectMapService
- `notification_queue` ← NotificationQueueService, ProjectMapService, PurchaseNotificationService
- `notification_templates` ← EmailTemplateService, ProjectMapService
- `publishers` ← ProjectMapService, SupportAgentService
- `registrations` ← AgentContextService, EventService, ProjectMapService, SupportAgentService, UserContextService
- `sessions` ← AgentContextService, EventService, ProjectMapService, SupportAgentService
- `settings` ← AgentContextService, ProjectMapService, SettingsService, SupportAgentService
- `speakers` ← AgentContextService, EventService, MediaService, ProjectMapService, SupportAgentService
- `support_tickets` ← ProjectMapService, SupportAgentService, UserContextService
- `user-context` ← ProjectMapService, UserContextService
- `users` ← AgentContextService, NotificationQueueService, PasswordResetService, ProjectMapService, PurchaseNotificationService
- `venues` ← EventService, MediaService, ProjectMapService, SupportAgentService
