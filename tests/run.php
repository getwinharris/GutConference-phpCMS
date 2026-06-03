<?php
require __DIR__ . '/../app/bootstrap.php';

use App\Services\EventService;
use App\Services\JsonStoreService;
use App\Services\ProjectMapService;
use App\Services\SchemaService;

function assertTrue(bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL {$message}\n");
        exit(1);
    }
    echo "PASS {$message}\n";
}

$store = new JsonStoreService();
$schema = json_decode(file_get_contents(app_path('storage/schema/collections.json')) ?: '{}', true);
$collections = $schema['collections'] ?? [];
$required = ['users','events','event_sections','speakers','sessions','venues','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','support_tickets','media_files'];

foreach ($required as $collection) {
    assertTrue(isset($collections[$collection]), "schema contains {$collection}");
    assertTrue(is_array($store->read($collection)), "{$collection} json collection is readable");
}

assertTrue((new SchemaService())->adminFields('events') !== [], 'events expose admin fields');
assertTrue(in_array('slug', (new SchemaService())->adminFields('events'), true), 'events admin can edit slug');
assertTrue(in_array('thumbnail_url', (new SchemaService())->adminFields('events'), true), 'events admin can edit thumbnail');
assertTrue(in_array('start_time', (new SchemaService())->adminFields('events'), true) && in_array('remaining_slots', (new SchemaService())->adminFields('events'), true), 'events admin can edit real time and slots');
assertTrue(in_array('items', (new SchemaService())->adminFields('event_sections'), true), 'event sections expose editable items');

$events = new EventService();
$featured = $events->featured();
$settings = (new \App\Services\SettingsService())->public();
assertTrue($featured !== null, 'featured published event exists');
assertTrue(($featured['slug'] ?? '') === 'global-gut-summit-2026', 'featured event slug is seeded');
assertTrue(($featured['name'] ?? '') === 'International Conference On Microbiome, Probiotics & Gut Nutrition', 'featured event uses admin-hosted conference name');
assertTrue($events->timeRange($featured) === '9:30 AM - 4:30 PM IST', 'featured event time comes from event fields');
assertTrue(str_contains($events->slotSummary($featured), '500') && str_contains($events->shortSlotSummary($featured), '500'), 'featured event slots come from event fields');
assertTrue(count($events->speakers('global-gut-summit-2026')) >= 8, 'seed includes conference speakers');
assertTrue(count($events->sessions('global-gut-summit-2026')) >= 8, 'seed includes agenda sessions');
assertTrue($events->venue('global-gut-summit-2026') !== null, 'seed includes venue or online room');
assertTrue(($settings['product_owner_handle'] ?? '') === '@the.gut.expert', 'settings include product owner social handle');
assertTrue(!empty($settings['product_owner_linkedin']) && !empty($settings['product_owner_profile_url']), 'settings include professional and social profile links');

$map = ProjectMapService::registry();
$validation = ProjectMapService::validate($map);
assertTrue($validation['missing_route_mappings'] === [], 'project map has route mappings');
assertTrue($validation['missing_services'] === [], 'project map services are registered');
assertTrue($validation['missing_collections'] === [], 'project map collections are registered');

$routePaths = array_column($map['routes'], 'path');
foreach (['/','/signup','/auth/google','/auth/google/callback','/events','/events/{slug}','/admin/events','/admin/event_sections','/admin/speakers','/admin/sessions','/admin/venues','/admin/registrations','/admin/notification_templates','/admin/support-tickets'] as $path) {
    assertTrue(in_array($path, $routePaths, true), "route exists: {$path}");
}

$home = file_get_contents(app_path('views/public/home.php')) ?: '';
$event = file_get_contents(app_path('views/public/event.php')) ?: '';
$admin = file_get_contents(app_path('views/layouts/admin.php')) ?: '';
$index = file_get_contents(app_path('index.php')) ?: '';
assertTrue(str_contains($home, 'GutConference'), 'home is rebranded');
assertTrue(str_contains($home, 'Product Owner') && str_contains($home, 'Dr. Praveen Jacob'), 'home includes owner section');
assertTrue(str_contains($home, 'reel-marquee') && substr_count($home, 'instagram.com/reel/') >= 17, 'home includes supplied Instagram reel loop');
assertTrue(str_contains($home, 'Press &amp; References') && str_contains($home, 'First India') && !str_contains($home, 'lifeatnature.com'), 'home includes clean press/blog references');
assertTrue(str_contains($home, 'LinkedIn') && str_contains($home, 'YouTube') && str_contains($home, 'Clinical Profile'), 'home includes owner social links');
assertTrue(str_contains(file_get_contents(app_path('views/public/signup.php')) ?: '', 'Google OAuth is the required customer signup path'), 'signup documents mandatory Google OAuth customer flow');
assertTrue(str_contains(file_get_contents(app_path('views/public/login.php')) ?: '', '/auth/google'), 'login exposes Google OAuth endpoint');
assertTrue(str_contains($index, "'/signup'") && str_contains($index, "'/auth'"), 'front controller allows signup and auth routes');
assertTrue(str_contains($event, 'Speaker Lineup') && str_contains($event, 'Reserve Your Seat'), 'event page includes conversion sections');
assertTrue(str_contains($event, 'Login to Buy Ticket') && str_contains($event, 'Pay with Razorpay'), 'event ticket flow gates payment behind login and direct Razorpay');
assertTrue(str_contains($event, 'sticky-register') && str_contains($event, 'section-nav'), 'event page includes sticky registration and section navigation');
assertTrue(str_contains($event, 'event-thumbnail') && str_contains($event, 'slot-chip'), 'event page includes thumbnail and agenda slot display');
assertTrue(str_contains($event, 'E-certificate included') && str_contains($featured['organizers'] ?? '', 'Alpha Naturals'), 'event page uses official brief content');
assertTrue(str_contains($admin, '/admin/events') && str_contains($admin, '/admin/venues'), 'admin nav exposes conference resources');
assertTrue(str_contains($admin, '/admin/support-tickets'), 'admin nav exposes support agent tickets');
assertTrue(!str_contains($admin, 'legacy-marketplace') && !str_contains($home, 'legacy-source-brand'), 'active UI does not expose old domain labels');

$templates = file_get_contents(app_path('storage/data/notification_templates.json')) ?: '';
assertTrue(str_contains($templates, 'payment-success') && str_contains($templates, 'google-calendar-reminder') && str_contains($templates, 'certificate-ready') && str_contains($templates, 'newsletter'), 'notification templates cover payment, calendar, certificate, and newsletter');
assertTrue(is_file(app_path('integrations/google-oauth/GoogleOAuthClient.php')), 'Google OAuth client integration exists');

$planner = new \App\Services\PurchaseNotificationService(new JsonStoreService());
$jobs = $planner->queuePurchaseSuccess(['id'=>'test-reg','email'=>'buyer@example.com','phone'=>'+919999999999','event_slug'=>'global-gut-summit-2026','certificate_status'=>'pending'], $featured, ['email'=>'buyer@example.com','google_sub'=>'google-user','google_calendar_enabled'=>true]);
assertTrue(count($jobs) >= 6, 'purchase notification planner creates email, WhatsApp, calendar, and certificate jobs');
$queue = $store->read('notification_queue');
$store->write('notification_queue', array_values(array_filter($queue, fn($item) => ($item['registration_id'] ?? '') !== 'test-reg')));

$media = file_get_contents(app_path('views/admin/media.php')) ?: '';
assertTrue(str_contains($media, 'value="events"') && str_contains($media, 'value="speakers"') && str_contains($media, 'value="venues"'), 'media contexts match conference CMS');

$phpFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path('app')));
foreach ($phpFiles as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $cmd = 'php -l ' . escapeshellarg($file->getPathname());
    exec($cmd, $output, $code);
    assertTrue($code === 0, 'syntax ok: ' . str_replace(app_path() . '/', '', $file->getPathname()));
}

echo "All GutConference CMS tests passed.\n";
