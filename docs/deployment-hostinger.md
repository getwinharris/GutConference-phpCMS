# Hostinger Deployment

GutConference Online is built for shared PHP hosting.

1. Connect GitHub in Hostinger hPanel under Advanced -> Git.
2. Select the production branch, normally `main`.
3. Deploy to `/public_html`.
4. Keep `storage/` and `storage/data/` writable by PHP.
5. Do not add project `.env` files. Configure `APP_URL`, admin credentials, Razorpay, SMTP, Meta WhatsApp Cloud API, Google OAuth, and Google Calendar in Admin -> Integrations after deployment. Server-level environment variables are external fallback only when the host requires them.
6. Log in to `/admin` and change admin credentials through Integrations.
7. Add cron for queue processing only after notification sending is implemented.

No Node build, Vercel runtime, SQL database, Redis, or queue worker is required for the current CMS.

Local development uses:

```bash
php -S 127.0.0.1:6040 index.php
```
