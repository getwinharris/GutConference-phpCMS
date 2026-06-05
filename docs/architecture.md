# Architecture

GutConference Online is a PHP-template app with JSON storage.

- `index.php` routes public, admin, and API requests into the PHP router.
- `app/Controllers` handles public pages, auth, and admin resources.
- `app/Services` owns JSON storage, schema, events, media, settings, secrets, audit logs, and environment checks.
- `views/public` renders the home, events list, event landing page, contact, auth, and 404 pages.
- `views/admin` renders schema-driven admin CRUD, integrations, settings, media, audit, environment, and project map pages.
- `storage/data/*.json` stores runtime records.
- `storage/schema/collections.json` is the source of truth for admin fields and collection shapes.

Public users cannot create events. Admins create and publish conference pages.
