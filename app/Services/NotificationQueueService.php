<?php
namespace App\Services;

final class NotificationQueueService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}

    public function queueEmail(string $to, string $templateKey, array $payload, int $availableAt = 0, array $meta = []): ?array {
        $to = trim($to);
        if ($to === '') return null;
        $availableAt = $availableAt ?: time();
        $job = [
            'id' => uniqid('notify_', true),
            'event_slug' => (string)($payload['event_slug'] ?? ''),
            'registration_id' => (string)($payload['registration_id'] ?? ''),
            'channel' => 'email',
            'to' => $to,
            'template_key' => $templateKey,
            'payload' => $payload,
            'status' => 'pending',
            'error' => '',
            'attempts' => 0,
            'available_at' => $availableAt,
            'created_at' => time(),
        ] + $meta;
        $queue = $this->store->read('notification_queue');
        $queue[] = $job;
        $this->store->write('notification_queue', $queue);
        return $job;
    }

    public function queueSignup(array $user): void {
        $admin = $this->adminEmail();
        $this->queueEmail((string)($user['email'] ?? ''), 'signup-welcome', [
            'name' => $user['name'] ?? '',
            'title' => 'Welcome to GutConference',
            'message' => 'Your GutConference account is ready.',
            'cta_url' => $this->url('/dashboard'),
            'cta_label' => 'Open Dashboard',
        ]);
        $this->queueEmail($admin, 'admin-new-signup', [
            'name' => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
            'title' => 'New signup',
            'message' => 'A new user signed up: ' . ($user['name'] ?? '') . ' <' . ($user['email'] ?? '') . '>',
            'cta_url' => $this->url('/admin'),
            'cta_label' => 'Open Admin',
        ]);
    }

    public function queueBooking(array $registration, array $event): void {
        $admin = $this->adminEmail();
        $eventName = (string)($event['name'] ?? $registration['event_slug'] ?? 'GutConference event');
        $payload = [
            'name' => $registration['name'] ?? '',
            'email' => $registration['email'] ?? '',
            'event_name' => $eventName,
            'event_slug' => $registration['event_slug'] ?? '',
            'registration_id' => $registration['id'] ?? '',
            'title' => 'Booking received',
            'message' => 'Your booking request for ' . $eventName . ' has been received. Payment status: ' . ($registration['payment_status'] ?? 'pending') . '.',
            'cta_url' => $this->url('/dashboard'),
            'cta_label' => 'View Dashboard',
        ];
        $this->queueEmail((string)($registration['email'] ?? ''), 'booking-received', $payload);
        $payload['title'] = 'New booking';
        $payload['message'] = 'New booking for ' . $eventName . ': ' . ($registration['name'] ?? '') . ' <' . ($registration['email'] ?? '') . '>';
        $payload['cta_url'] = $this->url('/admin/registrations');
        $payload['cta_label'] = 'View Registrations';
        $this->queueEmail($admin, 'admin-new-booking', $payload);
    }

    public function queueNewEvent(array $event): int {
        if (($event['status'] ?? '') !== 'published') return 0;
        $count = 0;
        foreach ($this->store->read('users') as $user) {
            if (empty($user['newsletter_opt_in']) || empty($user['email'])) continue;
            $this->queueEmail((string)$user['email'], 'new-event', [
                'name' => $user['name'] ?? '',
                'event_name' => $event['name'] ?? '',
                'event_slug' => $event['slug'] ?? '',
                'title' => 'New GutConference event',
                'message' => 'A new event is available: ' . ($event['name'] ?? 'GutConference event') . '.',
                'cta_url' => $this->url('/events/' . ($event['slug'] ?? '')),
                'cta_label' => 'View Event',
            ]);
            $count++;
        }
        return $count;
    }

    public function processDue(int $limit = 20): array {
        $secrets = (new SecretService())->all();
        $mailer = new SmtpMailer($secrets);
        $renderer = new EmailTemplateService($this->store);
        $queue = $this->store->read('notification_queue');
        $sent = 0; $failed = 0; $now = time();
        foreach ($queue as &$job) {
            if ($sent + $failed >= $limit) break;
            if (($job['channel'] ?? '') !== 'email' || ($job['status'] ?? '') !== 'pending' || (int)($job['available_at'] ?? 0) > $now) continue;
            try {
                $rendered = $renderer->render((string)($job['template_key'] ?? ''), $job['payload'] ?? []);
                $mailer->send((string)$job['to'], $rendered['subject'], $rendered['html']);
                $job['status'] = 'sent';
                $job['sent_at'] = time();
                $job['error'] = '';
                $sent++;
            } catch (\Throwable $e) {
                $job['attempts'] = (int)($job['attempts'] ?? 0) + 1;
                $job['status'] = 'failed';
                $job['error'] = $e->getMessage();
                $failed++;
            }
        }
        unset($job);
        $this->store->write('notification_queue', $queue);
        return ['sent' => $sent, 'failed' => $failed];
    }

    private function adminEmail(): string {
        $secrets = (new SecretService())->all();
        return trim((string)($secrets['admin_notification_email'] ?? getenv('ADMIN_EMAIL') ?: $secrets['smtp_from_email'] ?? ''));
    }

    private function url(string $path): string {
        return rtrim((string)(getenv('APP_URL') ?: 'https://gutconference.online'), '/') . $path;
    }
}
