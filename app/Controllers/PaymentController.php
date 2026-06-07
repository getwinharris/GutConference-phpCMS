<?php
namespace App\Controllers;

use App\Integrations\Razorpay\RazorpayClient;
use App\Services\{AuthService,EventService,JsonStoreService,NotificationQueueService,PaymentService,PurchaseNotificationService,SecretService};

final class PaymentController extends BaseController {
    public function checkout(string $slug): void {
        (new AuthService())->requireUser();
        $eventService = new EventService();
        $event = $eventService->findBySlug($slug);
        if (!$event || ($event['status'] ?? 'draft') !== 'published') {
            http_response_code(404);
            $this->render('public/404');
            return;
        }

        $mode = $event['payment_mode'] ?? (!empty($event['payment_page_url'] ?? $event['registration_url'] ?? '') ? 'payment_page' : 'razorpay_integration');
        $paymentPageUrl = trim((string)($event['payment_page_url'] ?? $event['registration_url'] ?? ''));
        if ($mode === 'payment_page') {
            if ($paymentPageUrl === '') {
                $this->flash('Payment page is not configured for this event yet.');
                $this->redirect('/events/' . $slug . '#registration');
            }
            $user = (new AuthService())->user() ?? [];
            $store = new JsonStoreService();
            $registration = $this->pendingRegistration($event, $user);
            $store->upsert('registrations', $registration);
            (new NotificationQueueService($store))->queueBooking($registration, $event);
            header('Location: ' . $paymentPageUrl);
            exit;
        }

        $secrets = (new SecretService())->all();
        $keyId = trim((string)($secrets['razorpay_key_id'] ?? ''));
        $keySecret = trim((string)($secrets['razorpay_key_secret'] ?? ''));
        if ($keyId === '' || $keySecret === '') {
            $this->flash('Razorpay integration is not configured yet. Add Razorpay keys in Admin -> Integrations or switch this event to Payment Page mode.');
            $this->redirect('/events/' . $slug . '#registration');
        }

        $user = (new AuthService())->user() ?? [];
        $registration = $this->pendingRegistration($event, $user);
        try {
            $order = (new RazorpayClient($keyId, $keySecret))->createOrder((int)round(((float)($event['price'] ?? 0)) * 100), (string)$registration['id']);
        } catch (\Throwable $e) {
            $this->flash('Razorpay order could not be created. Please contact the conference team.');
            $this->redirect('/events/' . $slug . '#registration');
        }

        $store = new JsonStoreService();
        $registration['razorpay_order_id'] = (string)($order['id'] ?? '');
        $store->upsert('registrations', $registration);
        (new NotificationQueueService($store))->queueBooking($registration, $event);

        $this->render('public/checkout', [
            'event' => $event,
            'registration' => $registration,
            'order' => $order,
            'razorpayKeyId' => $keyId,
        ]);
    }

    public function verify(string $slug): void {
        (new AuthService())->requireUser();
        $registrationId = trim((string)($_POST['registration_id'] ?? ''));
        $orderId = trim((string)($_POST['razorpay_order_id'] ?? ''));
        $paymentId = trim((string)($_POST['razorpay_payment_id'] ?? ''));
        $signature = trim((string)($_POST['razorpay_signature'] ?? ''));
        if ($registrationId === '' || $orderId === '' || $paymentId === '' || $signature === '') {
            $this->redirectToFailed($slug, 'missing', $registrationId, $orderId, $paymentId);
            return;
        }

        $secrets = (new SecretService())->all();
        $secret = trim((string)($secrets['razorpay_key_secret'] ?? ''));
        if ($secret === '' || !(new PaymentService($secret))->verifySignature($orderId, $paymentId, $signature)) {
            $this->redirectToFailed($slug, 'signature', $registrationId, $orderId, $paymentId);
            return;
        }

        $store = new JsonStoreService();
        $registrations = $store->read('registrations');
        $registration = null;
        foreach ($registrations as $item) {
            if (($item['id'] ?? '') === $registrationId && ($item['event_slug'] ?? '') === $slug) {
                $registration = $item;
                break;
            }
        }
        if (!$registration) {
            $this->redirectToFailed($slug, 'not_found', $registrationId, $orderId, $paymentId);
            return;
        }

        $registration['payment_status'] = 'paid';
        $registration['razorpay_payment_id'] = $paymentId;
        $registration['failure_reason'] = null;
        $registration['certificate_status'] = $registration['certificate_status'] ?? 'pending';
        $store->upsert('registrations', $registration);

        $event = (new EventService())->findBySlug($slug) ?? [];
        (new PurchaseNotificationService($store))->queuePurchaseSuccess($registration, $event, (new AuthService())->user() ?? []);

        $this->redirectToSuccess($slug, $registrationId, $orderId, $paymentId);
    }

    public function success(): void {
        (new AuthService())->requireUser();
        $slug = trim((string)($_GET['event'] ?? ''));
        $registrationId = trim((string)($_GET['registration_id'] ?? ''));
        $orderId = trim((string)($_GET['order_id'] ?? ''));
        $paymentId = trim((string)($_GET['payment_id'] ?? ''));

        $eventService = new EventService();
        $event = $slug !== '' ? $eventService->findBySlug($slug) : null;
        if (!$event) {
            http_response_code(404);
            $this->render('public/404');
            return;
        }
        $event['venue'] = $eventService->venue($slug) ?? [];

        $store = new JsonStoreService();
        $registration = $this->findRegistration($store, $slug, $registrationId);

        $this->render('public/payment-result', [
            'outcome' => 'success',
            'event' => $event,
            'registration' => $registration ?: [],
            'orderId' => $orderId,
            'paymentId' => $paymentId,
            'reason' => '',
        ]);
    }

    public function failed(): void {
        (new AuthService())->requireUser();
        $slug = trim((string)($_GET['event'] ?? ''));
        $registrationId = trim((string)($_GET['registration_id'] ?? ''));
        $orderId = trim((string)($_GET['order_id'] ?? ''));
        $paymentId = trim((string)($_GET['payment_id'] ?? ''));
        $reason = trim((string)($_GET['reason'] ?? 'unknown'));

        $eventService = new EventService();
        $event = $slug !== '' ? $eventService->findBySlug($slug) : null;
        if ($event) {
            $event['venue'] = $eventService->venue($slug) ?? [];
        }

        $store = new JsonStoreService();
        $registration = $this->findRegistration($store, $slug, $registrationId);

        $this->render('public/payment-result', [
            'outcome' => 'failed',
            'event' => $event ?: [],
            'registration' => $registration ?: [],
            'orderId' => $orderId,
            'paymentId' => $paymentId,
            'reason' => $reason,
        ]);
    }

    private function redirectToSuccess(string $slug, string $registrationId, string $orderId, string $paymentId): void {
        $query = http_build_query([
            'event' => $slug,
            'registration_id' => $registrationId,
            'order_id' => $orderId,
            'payment_id' => $paymentId,
        ]);
        $this->redirect('/payment/success?' . $query);
    }

    private function redirectToFailed(string $slug, string $reason, string $registrationId, string $orderId, string $paymentId): void {
        $store = new JsonStoreService();
        $registration = $this->findRegistration($store, $slug, $registrationId);
        if ($registration) {
            $registration['payment_status'] = 'failed';
            $registration['failure_reason'] = $reason;
            if ($orderId !== '') $registration['razorpay_order_id'] = $orderId;
            if ($paymentId !== '') $registration['razorpay_payment_id'] = $paymentId;
            $store->upsert('registrations', $registration);
        }
        $query = http_build_query([
            'event' => $slug,
            'registration_id' => $registrationId,
            'order_id' => $orderId,
            'payment_id' => $paymentId,
            'reason' => $reason,
        ]);
        $this->redirect('/payment/failed?' . $query);
    }

    private function findRegistration(JsonStoreService $store, string $slug, string $registrationId): ?array {
        if ($slug === '' || $registrationId === '') return null;
        foreach ($store->read('registrations') as $item) {
            if (($item['id'] ?? '') === $registrationId && ($item['event_slug'] ?? '') === $slug) {
                return $item;
            }
        }
        return null;
    }

    private function pendingRegistration(array $event, array $user): array {
        $email = (string)($user['email'] ?? '');
        $slug = (string)($event['slug'] ?? '');
        $store = new JsonStoreService();
        foreach ($store->read('registrations') as $registration) {
            if (($registration['event_slug'] ?? '') === $slug && ($registration['email'] ?? '') === $email && ($registration['payment_status'] ?? '') === 'pending') {
                return $registration;
            }
        }
        return [
            'id' => uniqid('reg_', true),
            'event_slug' => $slug,
            'purchase_type' => 'event',
            'name' => (string)($user['certificate_name'] ?? $user['name'] ?? ''),
            'email' => $email,
            'phone' => '',
            'profession' => '',
            'payment_status' => 'pending',
            'certificate_status' => 'pending',
            'google_calendar_event_ids' => [],
            'created_at' => time(),
        ];
    }
}
