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
        return array_intersect_key($settings, array_flip([
            'currency',
            'timezone',
            'featured_event_slug',
            'product_owner_name',
            'product_owner_title',
            'product_owner_handle',
            'product_owner_profile_url',
            'product_owner_instagram',
            'product_owner_linkedin',
            'product_owner_youtube',
            'product_owner_facebook',
        ]));
    }

    private function siteContext(): array {
        $events = array_map(function (array $item): array {
            $slug = (string)($item['slug'] ?? '');
            return [
            'name' => $item['name'] ?? '',
            'slug' => $slug,
            'url' => '/events/' . $slug,
            'date_label' => $item['date_label'] ?? '',
            'time_label' => $item['time_label'] ?? '',
            'start_time' => $item['start_time'] ?? '',
            'end_time' => $item['end_time'] ?? '',
            'timezone' => $item['timezone'] ?? '',
            'mode' => $item['mode'] ?? '',
            'price' => $item['price'] ?? null,
            'currency' => $item['currency'] ?? 'INR',
            'payment_mode' => $item['payment_mode'] ?? '',
            'booking_url' => '/events/' . $slug . '#registration',
            'login_url' => '/login',
            'contact_url' => '/contact',
            ];
        }, array_slice($this->publishedEvents(), 0, 10));
        return [
            'pages' => [
                'home' => '/',
                'events' => '/events',
                'contact' => '/contact',
                'login' => '/login',
                'signup' => '/signup',
            ],
            'events' => $events,
            'owner' => $this->ownerContext(),
            'speakers' => $this->speakerContext(),
            'agenda' => $this->agendaContext(),
            'booking_help' => [
                'browse_events' => '/events',
                'contact_team' => '/contact',
                'login_before_payment' => '/login',
                'instruction' => 'For a specific event, open the event page and use the registration section. Logged-out users are sent to login first.',
            ],
            'support_scope' => 'Use this compact CMS context to answer portal, owner, speaker, agenda, event, booking, certificate, and contact questions. Do not expose secrets, admin-only data, or other users.',
        ];
    }

    private function publishedEvents(): array {
        return array_values(array_filter($this->store->read('events'), fn($event) => ($event['status'] ?? 'published') === 'published'));
    }

    private function ownerContext(): array {
        $settings = $this->store->read('settings')[0] ?? [];
        return [
            'name' => $settings['product_owner_name'] ?? 'Dr. Praveen Jacob',
            'title' => $settings['product_owner_title'] ?? 'Integrating Traditional Medicine with Modern Research.',
            'handle' => $settings['product_owner_handle'] ?? '@the.gut.expert',
            'profile_url' => $settings['product_owner_profile_url'] ?? '',
            'instagram' => $settings['product_owner_instagram'] ?? '',
            'linkedin' => $settings['product_owner_linkedin'] ?? '',
            'youtube' => $settings['product_owner_youtube'] ?? '',
            'facebook' => $settings['product_owner_facebook'] ?? '',
            'summary' => 'GutConference presents Dr. Praveen Jacob, gut-health education, clinical consultation pathways, and microbiome-focused events.',
        ];
    }

    private function speakerContext(): array {
        return array_slice(array_map(fn($speaker) => [
            'event_slug' => $speaker['event_slug'] ?? '',
            'name' => $speaker['name'] ?? '',
            'credentials' => $speaker['credentials'] ?? '',
            'country' => $speaker['country'] ?? '',
            'topic' => $speaker['topic'] ?? '',
            'session_time' => $speaker['session_time'] ?? '',
            'profile' => $speaker['profile'] ?? '',
        ], $this->store->read('speakers')), 0, 30);
    }

    private function agendaContext(): array {
        return array_slice(array_map(fn($session) => [
            'event_slug' => $session['event_slug'] ?? '',
            'time_label' => $session['time_label'] ?? '',
            'title' => $session['title'] ?? '',
            'speaker_name' => $session['speaker_name'] ?? '',
            'description' => $session['description'] ?? '',
        ], $this->store->read('sessions')), 0, 30);
    }
}
