<?php
namespace App\Services;

final class EventService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}

    public function all(): array {
        $events = $this->store->read('events');
        usort($events, fn($a, $b) => strcmp((string)($b['featured'] ?? ''), (string)($a['featured'] ?? '')) ?: strcmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
        return $events;
    }

    public function published(): array {
        return array_values(array_filter($this->all(), fn($event) => ($event['status'] ?? 'draft') === 'published'));
    }

    public function featured(): ?array {
        $settings = (new SettingsService())->public();
        $slug = $settings['featured_event_slug'] ?? '';
        if ($slug !== '') {
            $event = $this->findBySlug($slug);
            if ($event && ($event['status'] ?? 'draft') === 'published') {
                return $event;
            }
        }
        foreach ($this->published() as $event) {
            if (!empty($event['featured'])) return $event;
        }
        return $this->published()[0] ?? null;
    }

    public function findBySlug(string $slug): ?array {
        foreach ($this->store->read('events') as $event) {
            if (($event['slug'] ?? '') === $slug) return $event;
        }
        return null;
    }

    public function sections(string $slug): array {
        return $this->forEvent('event_sections', $slug);
    }

    public function speakers(string $slug): array {
        return $this->forEvent('speakers', $slug);
    }

    public function sessions(string $slug): array {
        return $this->forEvent('sessions', $slug);
    }

    public function venue(string $slug): ?array {
        return $this->forEvent('venues', $slug)[0] ?? null;
    }

    private function forEvent(string $collection, string $slug): array {
        $records = array_values(array_filter($this->store->read($collection), fn($record) => ($record['event_slug'] ?? '') === $slug && (($record['enabled'] ?? true) !== false)));
        usort($records, fn($a, $b) => ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)));
        return $records;
    }
}
