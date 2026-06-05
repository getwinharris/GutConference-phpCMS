<?php
namespace App\Services;

final class EmailTemplateService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}

    public function render(string $templateKey, array $payload): array {
        $template = $this->template($templateKey);
        $subject = $this->replace((string)($template['subject'] ?? 'GutConference update'), $payload);
        $body = $this->replace((string)($template['body'] ?? ''), $payload);
        $ctaUrl = (string)($payload['cta_url'] ?? getenv('APP_URL') ?: 'https://gutconference.online');
        $ctaLabel = (string)($payload['cta_label'] ?? 'Open GutConference');
        $title = $this->replace((string)($payload['title'] ?? $subject), $payload);
        return [
            'subject' => $subject,
            'html' => $this->layout($title, $body, $ctaUrl, $ctaLabel),
        ];
    }

    private function template(string $key): array {
        foreach ($this->store->read('notification_templates') as $template) {
            if (($template['key'] ?? '') === $key && ($template['channel'] ?? '') === 'email' && ($template['enabled'] ?? true)) {
                return $template;
            }
        }
        return ['subject' => 'GutConference update', 'body' => '{{message}}'];
    }

    private function replace(string $text, array $payload): string {
        foreach ($payload as $key => $value) {
            if (is_scalar($value)) $text = str_replace('{{' . $key . '}}', (string)$value, $text);
        }
        return $text;
    }

    private function layout(string $title, string $body, string $ctaUrl, string $ctaLabel): string {
        $logo = rtrim((string)(getenv('APP_URL') ?: 'https://gutconference.online'), '/') . '/assets/images/media/gutconference-logo.png';
        $safeBody = nl2br(e($body));
        return '<!doctype html><html><body style="margin:0;background:#f3fbf9;font-family:Plus Jakarta Sans,Arial,sans-serif;color:#10263a;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3fbf9;padding:24px 0;"><tr><td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border:1px solid #d8ece8;border-radius:8px;overflow:hidden;">'
            . '<tr><td style="padding:24px;background:linear-gradient(135deg,#66b63f,#39b9a8,#087fc0);"><img src="' . e($logo) . '" alt="GutConference" style="max-width:220px;height:auto;display:block;"></td></tr>'
            . '<tr><td style="padding:28px;"><h1 style="margin:0 0 14px;color:#073a66;font-size:30px;line-height:1.1;text-transform:uppercase;">' . e($title) . '</h1>'
            . '<div style="font-size:16px;line-height:1.7;color:#5e7487;">' . $safeBody . '</div>'
            . '<p style="margin:24px 0 0;"><a href="' . e($ctaUrl) . '" style="display:inline-block;background:linear-gradient(135deg,#66b63f,#39b9a8,#087fc0);color:#fff;text-decoration:none;font-weight:800;border-radius:8px;padding:13px 18px;">' . e($ctaLabel) . '</a></p>'
            . '</td></tr><tr><td style="padding:16px 28px;border-top:1px solid #d8ece8;color:#5e7487;font-size:12px;">GutConference Online</td></tr>'
            . '</table></td></tr></table></body></html>';
    }
}
