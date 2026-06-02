# Hostinger Deployment

GutConference Online is built for shared PHP hosting.

1. Connect GitHub in Hostinger hPanel under Advanced -> Git.
2. Select the production branch, normally `main`.
3. Deploy to `/public_html`.
4. Keep `storage/` and `storage/data/` writable by PHP.
5. Configure `.env` on the host:

```dotenv
APP_NAME="GutConference Online"
APP_URL=https://gutconference.online
ADMIN_USERNAME=admin
ADMIN_EMAIL=admin@gutconference.online
ADMIN_PASSWORD=ChangeThisAdmin123!
```

6. Log in to `/admin` and change admin credentials.
7. Configure Razorpay, SMTP, and Meta WhatsApp Cloud API in Admin -> Integrations.
8. Add cron for queue processing only after notification sending is implemented.

No Node build, Vercel runtime, SQL database, Redis, or queue worker is required for the current CMS.

Local development uses:

```bash
php -S 127.0.0.1:6040 index.php
```
