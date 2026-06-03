<?php
namespace App\Integrations\GoogleOAuth;

final class GoogleOAuthClient {
    public function __construct(private array $settings) {}

    public function configured(): bool {
        return !empty($this->settings['google_client_id'])
            && !empty($this->settings['google_client_secret'])
            && !empty($this->settings['google_redirect_uri']);
    }

    public function authUrl(string $state): string {
        $params = [
            'client_id' => $this->settings['google_client_id'],
            'redirect_uri' => $this->settings['google_redirect_uri'],
            'response_type' => 'code',
            'scope' => implode(' ', [
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/calendar.events',
            ]),
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ];
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }
}
