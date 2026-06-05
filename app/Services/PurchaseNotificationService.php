<?php
namespace App\Services;

final class PurchaseNotificationService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}

    public function queuePurchaseSuccess(array $registration, array $event, array $user = []): array {
        $jobs = [];
        $email = (string)($registration['email'] ?? $user['email'] ?? '');
        $phone = (string)($registration['phone'] ?? '');
        $eventSlug = (string)($event['slug'] ?? $registration['event_slug'] ?? '');
        $registrationId = (string)($registration['id'] ?? '');
        $now = time();

        if ($email !== '') {
            $jobs[] = $this->job($eventSlug, $registrationId, 'email', $email, 'payment-success', $now, [
                'subject' => 'Payment successful',
                'event_name' => $event['name'] ?? '',
            ]);
        }

        if ($phone !== '') {
            $jobs[] = $this->job($eventSlug, $registrationId, 'whatsapp', $phone, 'payment-success-whatsapp', $now, [
                'event_name' => $event['name'] ?? '',
            ]);
        }

        if (!empty($user['google_calendar_enabled']) || !empty($user['google_sub'])) {
            foreach ($this->calendarReminderTimes($event) as $label => $timestamp) {
                $jobs[] = $this->job($eventSlug, $registrationId, 'calendar', $email, 'google-calendar-reminder', $timestamp, [
                    'label' => $label,
                    'event_name' => $event['name'] ?? '',
                    'start_time' => $event['start_time'] ?? '',
                    'timezone' => $event['timezone'] ?? 'Asia/Kolkata',
                ]);
            }
        }

        if (!empty($registration['certificate_status']) && $registration['certificate_status'] === 'pending') {
            $jobs[] = $this->job($eventSlug, $registrationId, 'email', $email, 'certificate-ready', max($now, $this->eventCompletionTime($event)), [
                'event_name' => $event['name'] ?? '',
            ]);
        }

        $queue = $this->store->read('notification_queue');
        foreach ($jobs as $job) {
            $queue[] = $job;
        }
        $this->store->write('notification_queue', $queue);
        return $jobs;
    }

    public function newsletterAudience(): array {
        return array_values(array_filter($this->store->read('users'), fn($user) => !empty($user['newsletter_opt_in']) && !empty($user['email'])));
    }

    private function calendarReminderTimes(array $event): array {
        $dateLabel = trim((string)($event['date_label'] ?? ''));
        $timezone = new \DateTimeZone($this->timezoneName((string)($event['timezone'] ?? 'Asia/Kolkata')));
        $eventDate = \DateTimeImmutable::createFromFormat('j F Y H:i', $dateLabel . ' 09:00', $timezone)
            ?: \DateTimeImmutable::createFromFormat('d F Y H:i', $dateLabel . ' 09:00', $timezone)
            ?: new \DateTimeImmutable('tomorrow 09:00', $timezone);
        return [
            'previous_day_morning' => $eventDate->modify('-1 day')->setTime(9, 0)->getTimestamp(),
            'previous_day_evening' => $eventDate->modify('-1 day')->setTime(18, 0)->getTimestamp(),
            'event_day_morning' => $eventDate->setTime(8, 0)->getTimestamp(),
        ];
    }

    private function eventCompletionTime(array $event): int {
        $dateLabel = trim((string)($event['date_label'] ?? ''));
        $endTime = trim((string)($event['end_time'] ?? '23:59'));
        $timezone = new \DateTimeZone($this->timezoneName((string)($event['timezone'] ?? 'Asia/Kolkata')));
        $eventEnd = \DateTimeImmutable::createFromFormat('j F Y H:i', $dateLabel . ' ' . $endTime, $timezone)
            ?: \DateTimeImmutable::createFromFormat('d F Y H:i', $dateLabel . ' ' . $endTime, $timezone);
        return $eventEnd ? $eventEnd->getTimestamp() : time();
    }

    private function timezoneName(string $timezone): string {
        return strtoupper($timezone) === 'IST' ? 'Asia/Kolkata' : ($timezone !== '' ? $timezone : 'Asia/Kolkata');
    }

    private function job(string $eventSlug, string $registrationId, string $channel, string $to, string $templateKey, int $availableAt, array $payload): array {
        return [
            'id' => uniqid('notify_', true),
            'event_slug' => $eventSlug,
            'registration_id' => $registrationId,
            'channel' => $channel,
            'to' => $to,
            'template_key' => $templateKey,
            'payload' => $payload,
            'status' => 'pending',
            'error' => '',
            'attempts' => 0,
            'available_at' => $availableAt,
            'created_at' => time(),
        ];
    }
}

