<?php
namespace App\Controllers;
use App\Services\{ContactService,EventService,SecretService};
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
        ]);
    }
    
    public function about(): void { 
        $this->detectApiRequest();
        $this->render('public/about'); 
    }
    
    public function events(): void {
        $this->detectApiRequest();
        $this->render('public/events', ['events' => (new EventService())->published()]);
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
        ]);
    }
    
    public function contact(): void {
        $this->detectApiRequest();
        $success = false;
        $subject = $_GET['subject'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactService = new ContactService();
            $contactService->save([
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'subject' => $_POST['subject'] ?? '',
                'message' => $_POST['message'] ?? '',
            ]);
            $success = true;
        }
        $this->render('public/contact', ['success' => $success, 'subject' => $subject]);
    }
    
    public function login(): void { 
        $this->detectApiRequest();
        $this->render('public/login'); 
    }
}
