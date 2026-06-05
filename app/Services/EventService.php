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

    public function timeRange(array $event): string {
        $start = trim((string)($event['start_time'] ?? ''));
        $end = trim((string)($event['end_time'] ?? ''));
        $timezone = trim((string)($event['timezone'] ?? ''));
        if ($start !== '' && $end !== '') {
            return $this->formatTime($start) . ' - ' . $this->formatTime($end) . ($timezone !== '' ? ' ' . $timezone : '');
        }
        return (string)($event['time_label'] ?? '');
    }

    public function slotSummary(array $event, array $sessions = []): string {
        $label = trim((string)($event['slot_label'] ?? ''));
        $availability = $this->availability($event);
        if ($availability['total'] > 0) {
            return $availability['available'] . ' of ' . $availability['total'] . ' ' . ($label !== '' ? strtolower($label) : 'slots') . ' available';
        }
        if ($label !== '') {
            return $label;
        }
        $count = count($sessions);
        return $count > 0 ? $count . ' agenda slot' . ($count === 1 ? '' : 's') : 'Schedule managed by admin';
    }

    public function shortSlotSummary(array $event, array $sessions = []): string {
        $label = trim((string)($event['slot_label'] ?? ''));
        $availability = $this->availability($event);
        if ($availability['total'] > 0) {
            return $availability['available'] . '/' . $availability['total'] . ' ' . ($label !== '' ? strtolower($label) : 'slots');
        }
        $count = count($sessions);
        return $count > 0 ? $count . ' agenda slots' : 'Admin managed';
    }

    public function availability(array $event): array {
        $total = max(0, (int)($event['total_slots'] ?? 0));
        $remaining = array_key_exists('remaining_slots', $event) ? max(0, (int)($event['remaining_slots'] ?? 0)) : $total;
        $adminFilled = $total > 0 ? max(0, $total - min($remaining, $total)) : 0;
        $paidFilled = $this->paidRegistrationCount((string)($event['slug'] ?? ''));
        $filled = $adminFilled + $paidFilled;
        if ($total > 0) {
            $filled = min($filled, $total);
        }
        $available = $total > 0 ? max(0, $total - $filled) : 0;
        return [
            'total' => $total,
            'filled' => $filled,
            'available' => $available,
            'paid_registrations' => $paidFilled,
            'admin_filled' => $adminFilled,
            'label' => trim((string)($event['slot_label'] ?? 'slots')) ?: 'slots',
        ];
    }

    public function countdownDeadline(array $event): ?string {
        $dateLabel = trim((string)($event['date_label'] ?? ''));
        if ($dateLabel === '') return null;
        $timezone = $this->phpTimezone((string)($event['timezone'] ?? 'Asia/Kolkata'));
        $zone = new \DateTimeZone($timezone);
        $deadline = \DateTimeImmutable::createFromFormat('j F Y H:i', $dateLabel . ' 00:00', $zone)
            ?: \DateTimeImmutable::createFromFormat('d F Y H:i', $dateLabel . ' 00:00', $zone);
        return $deadline ? $deadline->format(\DateTimeInterface::ATOM) : null;
    }

    private function paidRegistrationCount(string $slug): int {
        if ($slug === '') return 0;
        $paidStatuses = ['paid', 'manual'];
        return count(array_filter($this->store->read('registrations'), fn($registration) => ($registration['event_slug'] ?? '') === $slug && in_array(($registration['payment_status'] ?? ''), $paidStatuses, true)));
    }

    private function phpTimezone(string $timezone): string {
        $timezone = trim($timezone);
        if (strtoupper($timezone) === 'IST') return 'Asia/Kolkata';
        return $timezone !== '' ? $timezone : 'Asia/Kolkata';
    }

    private function formatTime(string $time): string {
        $timestamp = strtotime($time);
        return $timestamp ? date('g:i A', $timestamp) : $time;
    }

    private function forEvent(string $collection, string $slug): array {
        $records = array_values(array_filter($this->store->read($collection), fn($record) => ($record['event_slug'] ?? '') === $slug && (($record['enabled'] ?? true) !== false)));
        usort($records, fn($a, $b) => ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)));
        return $records;
    }
}
