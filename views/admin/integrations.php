<div class="admin-card">
    <h2>Payments & Messaging Integrations</h2>
    <p style="color:var(--muted)">Configure live keys here after the customer completes Razorpay and Meta WhatsApp Business setup. Secrets are stored outside normal public content.</p>
</div>
<div class="admin-card">
    <form method="post" action="/admin/integrations/save" class="admin-form">
        <h2>Razorpay Registration Payments</h2>
        <p style="color:var(--muted)">Use these keys when internal payment verification is enabled. For the reduced first version, each event can also use an external Razorpay payment button/link from the event record.</p>
        <div class="admin-form__row">
            <label>Razorpay Key ID<input name="razorpay_key_id" value="<?= e($secrets['razorpay_key_id']??'') ?>" placeholder="rzp_live_xxxx"></label>
            <label>Razorpay Key Secret<input name="razorpay_key_secret" value="<?= e($secrets['razorpay_key_secret']??'') ?>" placeholder="Paste live key secret"></label>
        </div>
        <br>
        <h2>Google OAuth & Calendar</h2>
        <p style="color:var(--muted)">Customer signup should use Google OAuth so calendar reminders can be created without asking for a second consent later. Manual legacy users must connect Google before paying for an event or class.</p>
        <div class="admin-form__row">
            <label>Google Client ID<input name="google_client_id" value="<?= e($secrets['google_client_id']??'') ?>" placeholder="xxxxx.apps.googleusercontent.com"></label>
            <label>Google Client Secret<input name="google_client_secret" value="<?= e($secrets['google_client_secret']??'') ?>" placeholder="Paste client secret"></label>
            <label>Google Redirect URI<input name="google_redirect_uri" value="<?= e($secrets['google_redirect_uri']??'') ?>" placeholder="https://gutconference.online/auth/google/callback"></label>
            <label>Calendar Reminder Timezone<input name="google_calendar_timezone" value="<?= e($secrets['google_calendar_timezone']??'Asia/Kolkata') ?>"></label>
        </div>
        <br>
        <h2>Official Meta WhatsApp Cloud API</h2>
        <p style="color:var(--muted)">Use approved WhatsApp templates for booking, payment, and conference reminders. Template wording is managed in Notification Templates.</p>
        <div class="admin-form__row">
            <label>Meta Access Token<input name="meta_whatsapp_access_token" value="<?= e($secrets['meta_whatsapp_access_token']??'') ?>" placeholder="EAAG..."></label>
            <label>Phone Number ID<input name="meta_whatsapp_phone_number_id" value="<?= e($secrets['meta_whatsapp_phone_number_id']??'') ?>" placeholder="1234567890"></label>
            <label>Business Account ID<input name="meta_whatsapp_business_account_id" value="<?= e($secrets['meta_whatsapp_business_account_id']??'') ?>" placeholder="1234567890"></label>
            <label>Webhook Verify Token<input name="meta_whatsapp_webhook_verify_token" value="<?= e($secrets['meta_whatsapp_webhook_verify_token']??'') ?>" placeholder="custom verify token"></label>
        </div>
        <br>
        <h2>SMTP Email</h2>
        <div class="admin-form__row">
            <label>SMTP Host<input name="smtp_host" value="<?= e($secrets['smtp_host']??'') ?>"></label>
            <label>SMTP Port<input name="smtp_port" value="<?= e($secrets['smtp_port']??'587') ?>"></label>
            <label>SMTP Username<input name="smtp_username" value="<?= e($secrets['smtp_username']??'') ?>"></label>
            <label>SMTP Password<input name="smtp_password" value="<?= e($secrets['smtp_password']??'') ?>"></label>
            <label>From Email<input name="smtp_from_email" value="<?= e($secrets['smtp_from_email']??'') ?>"></label>
            <label>From Name<input name="smtp_from_name" value="<?= e($secrets['smtp_from_name']??'GutConference Online') ?>"></label>
        </div>
        <br><button class="btn btn-primary">Save Integrations</button>
    </form>
</div>
