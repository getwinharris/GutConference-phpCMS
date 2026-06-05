<?php
namespace App\Services;

final class UserContextService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}

    public function snapshot(array $user): array {
        $email = strtolower(trim((string)($user['email'] ?? '')));
        $id = (string)($user['sub'] ?? $user['id'] ?? ($email !== '' ? md5($email) : 'guest'));
        $context = [
            'user' => array_intersect_key($user, array_flip(['sub','email','name','certificate_name','role'])),
            'bookings' => $email === '' ? [] : $this->owned('registrations', $email),
            'tickets' => $email === '' ? [] : $this->owned('support_tickets', $email),
            'appointments' => $email === '' ? [] : $this->owned('contact_submissions', $email),
            'conversations' => $this->conversationTail($id),
            'updated_at' => date('c'),
        ];
        if ($email !== '') $this->writeUserFile($id, $context);
        return $context;
    }

    public function appendConversation(array $user, string $message, string $answer): void {
        $email = strtolower(trim((string)($user['email'] ?? '')));
        if ($email === '') return;
        $id = (string)($user['sub'] ?? $user['id'] ?? md5($email));
        $file = $this->file($id);
        $context = is_file($file) ? (json_decode((string)file_get_contents($file), true) ?: []) : [];
        $context['conversations'] = array_slice(array_merge($context['conversations'] ?? [], [[
            'message' => mb_substr($message, 0, 600),
            'answer' => mb_substr($answer, 0, 1000),
            'created_at' => date('c'),
        ]]), -12);
        $context['updated_at'] = date('c');
        $this->writeUserFile($id, $context);
    }

    private function owned(string $collection, string $email): array {
        return array_slice(array_values(array_filter($this->store->read($collection), fn($item) => strtolower((string)($item['email'] ?? '')) === $email)), -20);
    }

    private function conversationTail(string $id): array {
        $file = $this->file($id);
        $json = is_file($file) ? (json_decode((string)file_get_contents($file), true) ?: []) : [];
        return array_slice($json['conversations'] ?? [], -8);
    }

    private function writeUserFile(string $id, array $context): void {
        $file = $this->file($id);
        if (!is_dir(dirname($file))) mkdir(dirname($file), 0775, true);
        file_put_contents($file, json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    private function file(string $id): string {
        return storage_path('user-context/' . preg_replace('/[^a-zA-Z0-9_.-]/', '-', $id) . '.json');
    }
}
