<?php
namespace App\Services;

final class AgentContextService {
    public function __construct(private JsonStoreService $store = new JsonStoreService(), private SchemaService $schema = new SchemaService()) {}

    public function forUserEmail(string $email): array {
        $email = strtolower(trim($email));
        return [
            'user' => $email === '' ? null : $this->firstOwned('users', 'email', $email),
            'registrations' => $email === '' ? [] : $this->owned('registrations', 'email', $email),
            'settings' => $this->publicSettings(),
            'site' => $this->siteContext(),
        ];
    }

    private function owned(string $collection, string $field, string $email): array {
        $records = array_values(array_filter($this->store->read($collection), fn($item) => strtolower((string)($item[$field] ?? '')) === $email));
        $fields = $this->schema->agentContextFields($collection);
        return $fields ? array_map(fn($item) => array_intersect_key($item, array_flip($fields)), $records) : $records;
    }

    private function firstOwned(string $collection, string $field, string $email): ?array {
        $records = $this->owned($collection, $field, $email);
        return $records[0] ?? null;
    }

    private function publicSettings(): array {
        $settings = $this->store->read('settings')[0] ?? [];
        return array_intersect_key($settings, array_flip(['currency', 'timezone', 'featured_event_slug']));
    }

    private function siteContext(): array {
        $events = array_map(fn($item) => [
            'name' => $item['name'] ?? '',
            'slug' => $item['slug'] ?? '',
            'url' => '/events/' . ($item['slug'] ?? ''),
            'date_label' => $item['date_label'] ?? '',
            'mode' => $item['mode'] ?? '',
            'price' => $item['price'] ?? null,
        ], array_slice($this->store->read('events'), 0, 10));
        return [
            'pages' => [
                'home' => '/',
                'events' => '/events',
                'contact' => '/contact',
            ],
            'events' => $events,
            'support_scope' => 'Answer only from this JSON context and public site links. Do not expose admin data, secrets, or other users.',
        ];
    }
}
