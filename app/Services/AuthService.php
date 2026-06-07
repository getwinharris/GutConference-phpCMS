<?php
namespace App\Services;

final class AuthService {
    public function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public function requireUser(): void {
        if (!$this->user()) {
            header('Location: /login');
            exit;
        }
    }

    public function requireAdmin(): void {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $user = $this->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        if (($user['role'] ?? '') !== 'admin' && empty($user['is_admin'])) {
            $_SESSION['flash'] = 'Admin access required.';
            header('Location: /');
            exit;
        }
    }

    public function isGoogleConnected(): bool {
        $session = $_SESSION['user'] ?? null;
        if (!empty($session['google_sub'])) {
            return true;
        }
        $sub = (string)($session['sub'] ?? '');
        if ($sub === '') {
            return false;
        }
        $store = new JsonStoreService();
        foreach ($store->read('users') as $user) {
            if (($user['id'] ?? '') === $sub && !empty($user['google_sub'])) {
                return true;
            }
        }
        return false;
    }
}
