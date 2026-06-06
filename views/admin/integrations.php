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
        <div style="border:1px solid var(--line);border-radius:8px;background:#f6fbfb;padding:14px;margin:12px 0;color:var(--muted)">
            <strong style="display:block;color:var(--ink);margin-bottom:6px">Fix Google 403 access_denied</strong>
            <p style="margin:0 0 8px">If Google says gutconference.online has not completed verification, the OAuth consent screen is still in Testing or the user is not approved. In Google Cloud Console, add customer/admin emails under OAuth consent screen -> Test users, or publish the app and complete verification before public Google login.</p>
            <p style="margin:0">Authorized redirect URI must exactly match <code>https://gutconference.online/auth/google/callback</code>. Calendar access uses the sensitive <code>calendar.events</code> scope, so public launch may require Google verification.</p>
        </div>
        <div class="admin-form__row">
            <label>Google Client ID<input name="google_client_id" value="<?= e($secrets['google_client_id']??'') ?>" placeholder="xxxxx.apps.googleusercontent.com"></label>
            <label>Google Client Secret<input name="google_client_secret" value="<?= e($secrets['google_client_secret']??'') ?>" placeholder="Paste client secret"></label>
            <label>Google Redirect URI<input name="google_redirect_uri" value="<?= e($secrets['google_redirect_uri']??'') ?>" placeholder="https://gutconference.online/auth/google/callback"></label>
            <label>Calendar Reminder Timezone<input name="google_calendar_timezone" value="<?= e($secrets['google_calendar_timezone']??'Asia/Kolkata') ?>"></label>
        </div>
        <br>
        <h2>Google Site Kit / Search Console</h2>
        <p style="color:var(--muted)">For this PHP CMS, use the same Google tag and verification values that Site Kit manages on WordPress: Analytics/Tag Manager, Search Console verification, and optional Ads tag IDs.</p>
        <div class="admin-form__row">
            <label>Google Tag ID<input name="google_site_tag_id" value="<?= e($secrets['google_site_tag_id']??'') ?>" placeholder="G-XXXXXXXXXX or GT-XXXXXXX"></label>
            <label>Google Tag Manager ID<input name="google_tag_manager_id" value="<?= e($secrets['google_tag_manager_id']??'') ?>" placeholder="GTM-XXXXXXX"></label>
            <label>Search Console Verification<input name="google_search_console_verification" value="<?= e($secrets['google_search_console_verification']??'') ?>" placeholder="google-site-verification token"></label>
            <label>Google Ads / AW ID<input name="google_ads_id" value="<?= e($secrets['google_ads_id']??'') ?>" placeholder="AW-XXXXXXXXX"></label>
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
            <label>Admin Notification Email<input name="admin_notification_email" value="<?= e($secrets['admin_notification_email']??'') ?>"></label>
        </div>
        <br>
        <h2>Google AI / Gemini API</h2>
        <p style="color:var(--muted)">Configure Gemini API for support agent and AI-powered features.</p>
        <div class="admin-form__row">
            <label>API Key<input name="gemini_api_key" value="<?= e($secrets['gemini_api_key']??'') ?>" placeholder="AI..."></label>
            <label>Endpoint Base<input name="gemini_endpoint_base" value="<?= e($secrets['gemini_endpoint_base']??'https://generativelanguage.googleapis.com/v1beta') ?>"></label>
            <label>Model Retries<input type="number" name="gemini_model_retries" value="<?= e($secrets['gemini_model_retries']??'3') ?>"></label>
        </div>
        <div class="admin-form__row">
            <label>Vision/Language Models<textarea name="gemini_vision_language_models" rows="2"><?= e($secrets['gemini_vision_language_models']??'gemini-2.5-flash, gemini-2.5-pro, gemini-2.5-flash-lite') ?></textarea></label>
            <label>Audio Models<textarea name="gemini_audio_models" rows="2"><?= e($secrets['gemini_audio_models']??'gemini-2.5-flash') ?></textarea></label>
            <label>TTS Models<textarea name="gemini_tts_models" rows="2"><?= e($secrets['gemini_tts_models']??'gemini-2.5-flash-preview-tts') ?></textarea></label>
        </div>
        <br>
        <h2>Application Configuration</h2>
        <div class="admin-form__row">
            <label>App URL<input name="app_url" value="<?= e($secrets['app_url']??'https://gutconference.online') ?>" placeholder="https://gutconference.online"></label>
            <label>Admin Username<input name="admin_username" value="<?= e($secrets['admin_username']??'') ?>" autocomplete="username"></label>
            <label>Admin Email<input name="admin_email" value="<?= e($secrets['admin_email']??'') ?>" autocomplete="email"></label>
            <label>Admin Password<input type="password" name="admin_password" value="<?= e($secrets['admin_password']??'') ?>" placeholder="Leave blank to keep current" autocomplete="new-password"></label>
        </div>
        <br><button class="btn btn-primary">Save All Integrations</button>
    </form>
</div>
