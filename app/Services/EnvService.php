<?php
namespace App\Services;

final class EnvService {
    public static function load(?string $path = null, bool $overwrite = false): void {
        // Project env files are intentionally disabled. PHP can still read
        // process-level server environment variables via getenv().
    }

    public function adminCredentials(): array {
        // Read from SecretService first, then server environment variables.
        $secrets = (new SecretService())->all();
        return [
            'username' => trim((string)($secrets['admin_username'] ?? getenv('ADMIN_USERNAME') ?: '')),
            'email' => trim((string)($secrets['admin_email'] ?? getenv('ADMIN_EMAIL') ?: '')),
            'password' => trim((string)($secrets['admin_password'] ?? getenv('ADMIN_PASSWORD') ?: '')),
        ];
    }

    public function saveAdminCredentials(array $data): void {
        $secretService = new SecretService();
        $values = $secretService->all();
        foreach (['admin_username', 'admin_email'] as $postKey) {
            $value = trim((string)($data[$postKey] ?? ''));
            if ($value !== '') $values[$postKey] = $value;
        }
        $password = (string)($data['admin_password'] ?? '');
        if ($password !== '') $values['admin_password'] = $password;
        $secretService->save($values);
    }
}
