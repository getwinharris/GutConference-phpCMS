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

    public function exchangeCode(string $code): array {
        $response = $this->postForm('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $this->settings['google_client_id'],
            'client_secret' => $this->settings['google_client_secret'],
            'redirect_uri' => $this->settings['google_redirect_uri'],
            'grant_type' => 'authorization_code',
        ]);
        if (empty($response['access_token'])) {
            throw new \RuntimeException('Google token response did not include an access token.');
        }
        return $response;
    }

    public function userInfo(string $accessToken): array {
        $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT => 20,
        ]);
        $body = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false) {
            throw new \RuntimeException('Google userinfo request failed: ' . $error);
        }
        $decoded = json_decode($body, true) ?: [];
        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException('Google userinfo request failed with HTTP ' . $status . ': ' . ($decoded['error_description'] ?? $decoded['error'] ?? 'Unknown error'));
        }
        if (empty($decoded['sub']) || empty($decoded['email'])) {
            throw new \RuntimeException('Google userinfo response was missing account identity.');
        }
        return $decoded;
    }

    private function postForm(string $url, array $fields): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 20,
        ]);
        $body = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false) {
            throw new \RuntimeException('Google token exchange failed: ' . $error);
        }
        $decoded = json_decode($body, true) ?: [];
        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException('Google token exchange failed with HTTP ' . $status . ': ' . ($decoded['error_description'] ?? $decoded['error'] ?? 'Unknown error'));
        }
        return $decoded;
    }
}
