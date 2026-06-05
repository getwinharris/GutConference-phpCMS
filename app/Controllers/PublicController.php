<?php
namespace App\Controllers;
use App\Services\{AuthService,ContactService,EventService,JsonStoreService,ResourceService,SecretService,SettingsService};
final class PublicController extends BaseController {
    
    protected function detectApiRequest(): void {
        $this->isApiRequest = strpos($_SERVER['REQUEST_URI'], '/api/') === 0;
    }
    
    public function home(): void {
        $this->detectApiRequest();
        $events = new EventService();
        $this->render('public/home', [
            'featuredEvent' => $events->featured(),
            'events' => $events->published(),
            'eventService' => $events,
            'settings' => (new SettingsService())->public(),
            'publishers' => (new ResourceService('publishers'))->all(),
        ]);
    }
    
    public function events(): void {
        $this->detectApiRequest();
        $events = new EventService();
        $this->render('public/events', ['events' => $events->published(), 'eventService' => $events]);
    }

    public function dashboard(): void {
        $this->detectApiRequest();
        (new AuthService())->requireUser();
        $user = (new AuthService())->user() ?? [];
        $email = (string)($user['email'] ?? '');
        $store = new JsonStoreService();
        $eventService = new EventService();
        $eventsBySlug = [];
        foreach ($eventService->all() as $event) {
            $eventsBySlug[(string)($event['slug'] ?? '')] = $event;
        }
        $registrations = array_values(array_filter($store->read('registrations'), fn($registration) => ($registration['email'] ?? '') === $email));
        foreach ($registrations as &$registration) {
            $event = $eventsBySlug[(string)($registration['event_slug'] ?? '')] ?? [];
            $registration['event'] = $event;
            $registration['certificate_available'] = $this->certificateAvailable($registration, $event);
        }
        unset($registration);
        $this->render('public/dashboard', [
            'pageTitle' => 'Dashboard',
            'user' => $user,
            'registrations' => $registrations,
        ]);
    }

    public function event(string $slug): void {
        $this->detectApiRequest();
        $events = new EventService();
        $event = $events->findBySlug($slug);
        if (!$event || ($event['status'] ?? 'draft') !== 'published') {
            http_response_code(404);
            $this->render('public/404');
            return;
        }
        $this->render('public/event', [
            'event' => $event,
            'sections' => $events->sections($slug),
            'speakers' => $events->speakers($slug),
            'sessions' => $events->sessions($slug),
            'venue' => $events->venue($slug),
            'secrets' => (new SecretService())->all(),
            'eventService' => $events,
        ]);
    }
    
    public function contact(): void {
        $this->detectApiRequest();
        $success = false;
        $subject = $_GET['subject'] ?? '';
        $events = new EventService();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactService = new ContactService();
            $contactService->save([
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'request_type' => $_POST['request_type'] ?? 'consultation',
                'event_slug' => $_POST['event_slug'] ?? '',
                'subject' => $_POST['subject'] ?? '',
                'message' => $_POST['message'] ?? '',
            ]);
            $success = true;
        }
        $this->render('public/contact', ['success' => $success, 'subject' => $subject, 'events' => $events->published()]);
    }
    
    public function login(): void { 
        $this->detectApiRequest();
        $this->render('public/login'); 
    }

    public function signup(): void {
        $this->detectApiRequest();
        $this->render('public/signup');
    }

    private function certificateAvailable(array $registration, array $event): bool {
        $paid = in_array(($registration['payment_status'] ?? ''), ['paid', 'manual'], true);
        if (!$paid || empty($event)) return false;
        $dateLabel = trim((string)($event['date_label'] ?? ''));
        $endTime = trim((string)($event['end_time'] ?? '23:59'));
        $timezone = strtoupper((string)($event['timezone'] ?? '')) === 'IST' ? 'Asia/Kolkata' : ((string)($event['timezone'] ?? 'Asia/Kolkata'));
        $zone = new \DateTimeZone($timezone !== '' ? $timezone : 'Asia/Kolkata');
        $eventEnd = \DateTimeImmutable::createFromFormat('j F Y H:i', $dateLabel . ' ' . $endTime, $zone)
            ?: \DateTimeImmutable::createFromFormat('d F Y H:i', $dateLabel . ' ' . $endTime, $zone);
        return $eventEnd ? time() >= $eventEnd->getTimestamp() : false;
    }
}
