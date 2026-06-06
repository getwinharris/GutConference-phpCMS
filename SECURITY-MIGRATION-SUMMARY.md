# Security Migration & UI Fixes Summary

## Overview
This document summarizes the security improvements and UI fixes completed in this session.

## Changes Made

### 1. Support Widget Z-Index Fix (Mobile)
**Issue**: On mobile devices, the sticky registration button (z-index: 150) was covering the support chat input when the support widget was open (z-index: 130).

**Solution**: Added mobile-specific CSS rule to lower the sticky-register z-index when support is open.

**File Modified**:
- `assets/css/index.css` (line ~3048)

**Change**:
```css
/* Inside @media (max-width: 640px) breakpoint */
body.support-open .sticky-register {
    z-index: 120;
}
```

**Result**: On mobile, when support widget is open, the sticky registration button now sits behind the support panel (z-index 130), ensuring the chat input is accessible.

---

### 2. Admin Credentials Migration from .env to SecretService

**Issue**: Sensitive credentials (admin username, email, password, APP_URL, and Gemini API keys) were previously stored in plaintext `.env` files, which created security and Git conflict risk.

**Solution**: Migrated services to read from encrypted `SecretService` first, with legacy server environment-variable fallback only for deployments that already provide process-level values outside the repo. Project `.env` files must not be used or restored.

#### Files Modified:

##### 1. `app/Services/EnvService.php`
- Updated `adminCredentials()` method to read from `SecretService` first, then fallback to server environment variables
- Ensures backward compatibility with existing server-level environment configuration outside the repo
- Admin credentials now stored encrypted in `storage/data/adminsecrets.json`

**Change**:
```php
public function adminCredentials(): array {
    // Read from SecretService first (preferred), fallback to server environment variables for backward compatibility
    $secrets = (new SecretService())->all();
    return [
        'username' => trim((string)($secrets['admin_username'] ?? getenv('ADMIN_USERNAME') ?: '')),
        'email' => trim((string)($secrets['admin_email'] ?? getenv('ADMIN_EMAIL') ?: '')),
        'password' => trim((string)($secrets['admin_password'] ?? getenv('ADMIN_PASSWORD') ?: '')),
    ];
}
```

##### 2. `app/Services/GeminiModelRouter.php`
- Updated `answer()`, `diagnostics()`, and `models()` methods
- Now reads Google AI API keys, endpoint, retries, and model lists from SecretService
- Fallback to legacy server environment variables maintained

**Changes**:
- `google_ai_api_key` (SecretService) → `GOOGLE_AI_STUDIO_API_KEY` server environment variable
- `google_ai_endpoint` (SecretService) → `GOOGLE_AI_ENDPOINT_BASE` server environment variable
- `google_ai_retries` (SecretService) → `GOOGLE_AI_MODEL_RETRIES` server environment variable
- `google_ai_vision_language_models` (SecretService) → `GOOGLE_AI_VISION_LANGUAGE_MODELS` server environment variable
- `google_ai_audio_models` (SecretService) → `GOOGLE_AI_AUDIO_MODELS` server environment variable
- `google_ai_tts_models` (SecretService) → `GOOGLE_AI_TTS_MODELS` server environment variable

##### 3. `app/Services/NotificationQueueService.php`
- Updated `url()` private method
- Reads `app_url` from SecretService first, then `APP_URL` from server environment variables

**Change**:
```php
private function url(string $path): string {
    $secrets = (new SecretService())->all();
    return rtrim((string)($secrets['app_url'] ?? getenv('APP_URL') ?: 'https://gutconference.online'), '/') . $path;
}
```

##### 4. `app/Services/EmailTemplateService.php`
- Updated `render()` and `layout()` methods
- Reads `app_url` from SecretService for email logo URLs and default CTA URLs
- Passes secrets array to layout method for consistent URL generation

**Changes**:
```php
public function render(string $templateKey, array $payload): array {
    $secrets = (new SecretService())->all();
    // ... uses $secrets['app_url'] for default CTA URL
    return [
        'subject' => $subject,
        'html' => $this->layout($title, $body, $ctaUrl, $ctaLabel, $secrets),
    ];
}

private function layout(string $title, string $body, string $ctaUrl, string $ctaLabel, array $secrets = []): string {
    $logo = rtrim((string)($secrets['app_url'] ?? getenv('APP_URL') ?: 'https://gutconference.online'), '/') . '/assets/images/media/gutconference-logo.png';
    // ...
}
```

---

## Migration Path

### For Administrators:

1. **Admin UI Already Updated**: The admin integrations page (`/admin/integrations`) now includes fields for:
   - App URL
   - Admin Username
   - Admin Email
   - Admin Password
   - Google AI API Key
   - Google AI Endpoint
   - Google AI Retries
   - Model Lists (vision/language, audio, TTS)

2. **Save Credentials**: Navigate to `/admin/integrations` and enter credentials. They will be encrypted and stored in `storage/data/adminsecrets.json`.

3. **Backward Compatibility**: The system can read server environment variables if SecretService values are not set. Project `.env` files are not allowed.

4. **Remove env files**: Remove any project `.env`, `.env.example`, or `.env.*` files and keep credentials in encrypted admin integrations.

---

## Security Benefits

1. **Encryption**: Admin credentials and API keys are now encrypted using AES-256-CBC
2. **Key Management**: Encryption key stored in `storage/runtime-key.php` (excluded from git)
3. **Separation of Concerns**: Sensitive credentials no longer in version-controlled `.env` files or tracked env templates
4. **Audit Trail**: Changes to integrations are logged through the audit system
5. **Single Source of Truth**: Admin UI provides centralized credential management

---

## Validation

Created `tests/validate-secret-migration.php` to verify:
- ✓ EnvService reads admin credentials with fallback
- ✓ GeminiModelRouter reads API keys with fallback
- ✓ NotificationQueueService generates URLs with fallback
- ✓ EmailTemplateService renders templates with fallback

**All tests passed.**

---

## Backward Compatibility

All services implement graceful fallback:
1. Try reading from SecretService first
2. If not found, fall back to server environment variables
3. If still not found, use hardcoded defaults (where applicable)

This ensures:
- No breaking changes for existing deployments
- Smooth migration path
- Development environments use admin integrations or server-level environment variables outside the repo

---

## Files Changed

### Modified:
- `assets/css/index.css` (z-index fix)
- `app/Services/EnvService.php` (admin credentials)
- `app/Services/GeminiModelRouter.php` (Gemini API keys)
- `app/Services/NotificationQueueService.php` (APP_URL)
- `app/Services/EmailTemplateService.php` (APP_URL)

### Created:
- `tests/validate-secret-migration.php` (validation script)
- `SECURITY-MIGRATION-SUMMARY.md` (this file)

### Previously Modified (in earlier session):
- `views/admin/integrations.php` (UI fields added)
- `views/admin/settings.php` (redirect to integrations)

---

## Next Steps for Production

1. Deploy to staging/production
2. Navigate to `/admin/integrations`
3. Enter all credentials through the UI
4. Test authentication and API integrations
5. Once confirmed working, remove any project `.env` files
6. Keep any remaining environment-specific values in server configuration outside the repo; do not create or commit `.env`, `.env.example`, or `.env.*`

---

## Notes

- The SecretService already existed and was being used for Razorpay, SMTP, and OAuth credentials
- This migration extends SecretService usage to admin credentials and Gemini API configuration
- The UI was already updated in a previous session
- This session completed the backend service integration
- The z-index fix is a separate UI improvement unrelated to security migration
