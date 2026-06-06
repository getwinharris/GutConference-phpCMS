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
$required = ['users','events','event_sections','speakers','sessions','venues','publishers','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','support_tickets','media_files'];

foreach ($required as $collection) {
    assertTrue(isset($collections[$collection]), "schema contains {$collection}");
    assertTrue(is_array($store->read($collection)), "{$collection} json collection is readable");
}

assertTrue((new SchemaService())->adminFields('events') !== [], 'events expose admin fields');
assertTrue(in_array('slug', (new SchemaService())->adminFields('events'), true), 'events admin can edit slug');
assertTrue(in_array('thumbnail_url', (new SchemaService())->adminFields('events'), true), 'events admin can edit thumbnail');
assertTrue(in_array('payment_mode', (new SchemaService())->adminFields('events'), true) && in_array('payment_page_url', (new SchemaService())->adminFields('events'), true), 'events admin can edit payment mode and payment page link');
assertTrue(in_array('start_time', (new SchemaService())->adminFields('events'), true) && in_array('remaining_slots', (new SchemaService())->adminFields('events'), true), 'events admin can edit real time and slots');
assertTrue(in_array('items', (new SchemaService())->adminFields('event_sections'), true), 'event sections expose editable items');
assertTrue(in_array('credentials', (new SchemaService())->adminFields('speakers'), true), 'speakers admin can edit credentials');
assertTrue(in_array('logo_url', (new SchemaService())->adminFields('publishers'), true), 'publishers expose source logo field');
assertTrue(isset($collections['users']['fields']['certificate_name']), 'users schema stores certificate name');

$events = new EventService();
$featured = $events->featured();
$settings = (new \App\Services\SettingsService())->public();
assertTrue($featured !== null, 'featured published event exists');
assertTrue(($featured['slug'] ?? '') === 'global-gut-summit-2026', 'featured event slug is seeded');
assertTrue(($featured['name'] ?? '') === 'Gut Health, Probiotics & Prebiotics Conference 2026', 'featured event uses admin-hosted conference name');
assertTrue(($featured['payment_mode'] ?? '') === 'razorpay_integration' && ($featured['payment_page_url'] ?? '') === '', 'featured event uses internal Razorpay integration');
assertTrue($events->timeRange($featured) === '9:30 AM - 4:30 PM IST', 'featured event time comes from event fields');
assertTrue(str_contains($events->slotSummary($featured), '1500') && str_contains($events->shortSlotSummary($featured), '1500'), 'featured event slots come from event fields');
assertTrue(($events->availability($featured)['filled'] ?? 0) === 49 && ($events->availability($featured)['available'] ?? 0) === 1451, 'featured event derives 49 filled and 1451 available seats from admin baseline');
$beforeRegistrations = $store->read('registrations');
$store->write('registrations', array_merge($beforeRegistrations, [['id'=>'test-paid-seat','event_slug'=>'global-gut-summit-2026','email'=>'seat-test@example.com','payment_status'=>'paid']]));
assertTrue(($events->availability($featured)['filled'] ?? 0) === 50 && ($events->availability($featured)['available'] ?? 0) === 1450, 'paid registrations automatically increase filled slots');
$store->write('registrations', $beforeRegistrations);
$speakers = $events->speakers('global-gut-summit-2026');
assertTrue(count($speakers) >= 9, 'seed includes conference speakers');
assertTrue((bool)array_values(array_filter($speakers, fn($speaker) => ($speaker['id'] ?? '') === 'spk-tom-obryan' && str_contains((string)($speaker['credentials'] ?? ''), 'Functional Medicine Expert'))), 'seed includes Dr. Tom O\'Bryan speaker with credentials');
assertTrue((bool)array_values(array_filter($speakers, fn($speaker) => ($speaker['id'] ?? '') === 'spk-tom-obryan' && ($speaker['country'] ?? '') === 'Italy')), 'seed stores Dr. Tom O\'Bryan country');
assertTrue((bool)array_values(array_filter($speakers, fn($speaker) => ($speaker['id'] ?? '') === 'spk-somashekhar-huddar' && ($speaker['name'] ?? '') === 'Dr. Somashekhar Huddar, BAMS')), 'seed replaces Ayurveda placeholder with Dr. Somashekhar Huddar');
assertTrue((bool)array_values(array_filter($events->sessions('global-gut-summit-2026'), fn($session) => str_contains((string)($session['speaker_name'] ?? ''), 'Somashekhar Huddar'))), 'agenda uses Dr. Somashekhar Huddar for Ayurveda slot');
foreach ($speakers as $speaker) {
    assertTrue(!empty($speaker['photo_url']), 'speaker has photo: ' . ($speaker['name'] ?? 'Unknown'));
    assertTrue(is_file(app_path(ltrim((string)$speaker['photo_url'], '/'))), 'speaker photo exists: ' . ($speaker['name'] ?? 'Unknown'));
}
assertTrue(!str_contains(json_encode($events->sections('global-gut-summit-2026')), 'Affordable Rs.299 registration'), 'event sections remove old affordable Rs.299 highlight');
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
assertTrue(!in_array('/about', $routePaths, true), 'about route is removed from public routes');
foreach (['/','/terms','/privacy','/signup','/auth/google','/auth/google/callback','/dashboard','/events','/events/{slug}','/events/{slug}/checkout','/events/{slug}/payment/verify','/support/chat','/support/admin/apply','/admin/events','/admin/event_sections','/admin/speakers','/admin/sessions','/admin/venues','/admin/publishers','/admin/registrations','/admin/notification_templates','/admin/support-tickets','/admin/branding'] as $path) {
    assertTrue(in_array($path, $routePaths, true), "route exists: {$path}");
}

$home = file_get_contents(app_path('views/public/home.php')) ?: '';
$event = file_get_contents(app_path('views/public/event.php')) ?: '';
$admin = file_get_contents(app_path('views/layouts/admin.php')) ?: '';
$appLayout = file_get_contents(app_path('views/layouts/app.php')) ?: '';
$supportWidget = file_get_contents(app_path('views/partials/support-widget.php')) ?: '';
$integrations = file_get_contents(app_path('views/admin/integrations.php')) ?: '';
$branding = file_get_contents(app_path('views/admin/branding.php')) ?: '';
$paymentController = file_get_contents(app_path('app/Controllers/PaymentController.php')) ?: '';
$checkout = file_get_contents(app_path('views/public/checkout.php')) ?: '';
$index = file_get_contents(app_path('index.php')) ?: '';
assertTrue(str_contains($home, 'GutConference'), 'home is rebranded');
assertTrue(is_file(app_path('assets/images/media/speakers/owner-dr-praveen-jacob-also-speaker.jpeg')), 'owner hero portrait asset exists');
assertTrue(str_contains($home, 'Clinical Profile') && str_contains($home, 'Dr. Praveen Jacob'), 'home includes owner section');
assertTrue(!str_contains($home, 'How This Helps') && !str_contains($home, 'Admin-owned growth') && !str_contains($home, 'Current Live Event'), 'home does not expose planning or event-sales sections');
assertTrue(!str_contains($home, "href=\"<?= $event ? '/events/'") && str_contains($home, 'href="/events">View Events &amp; Classes'), 'home events CTA goes to events index');
assertTrue(str_contains($home, 'data-reel-carousel') && str_contains($home, 'data-reel-modal') && substr_count($home, 'instagram.com/reel/') >= 17, 'home includes supplied Instagram reel carousel with popup player');
assertTrue(str_contains($home, 'Publishers') && str_contains($home, '$publisherLinks') && !str_contains($home, 'admin CMS'), 'home renders public-facing publisher references');
assertTrue(str_contains($home, 'LinkedIn') && str_contains($home, 'YouTube') && str_contains($home, 'Clinical Profile'), 'home includes owner social links');
assertTrue(str_contains(file_get_contents(app_path('views/public/signup.php')) ?: '', 'Google OAuth is the required customer signup path'), 'signup documents mandatory Google OAuth customer flow');
assertTrue(str_contains(file_get_contents(app_path('views/public/signup.php')) ?: '', 'certificate_name'), 'signup asks for certificate name');
assertTrue(str_contains(file_get_contents(app_path('views/public/login.php')) ?: '', '/auth/google'), 'login exposes Google OAuth endpoint');
assertTrue(str_contains(file_get_contents(app_path('integrations/google-oauth/GoogleOAuthClient.php')) ?: '', 'https://oauth2.googleapis.com/token') && str_contains(file_get_contents(app_path('integrations/google-oauth/GoogleOAuthClient.php')) ?: '', 'oauth2/v3/userinfo'), 'Google OAuth client exchanges codes and fetches userinfo');
assertTrue(str_contains(file_get_contents(app_path('app/Controllers/AuthController.php')) ?: '', 'exchangeCode($code)') && str_contains(file_get_contents(app_path('app/Controllers/AuthController.php')) ?: '', "upsert('users'"), 'Google OAuth callback creates or updates customer session');
assertTrue(isset($collections['users']['fields']['google_refresh_token']) && ($collections['users']['fields']['google_refresh_token']['type'] ?? '') === 'secret', 'users schema stores Google refresh token as secret');
assertTrue(str_contains($index, "'/signup'") && str_contains($index, "'/auth'"), 'front controller allows signup and auth routes');
assertTrue(str_contains($index, "'/dashboard'"), 'front controller allows customer dashboard route');
assertTrue(str_contains($index, "'/support'"), 'front controller allows support chat route');
assertTrue(str_contains(file_get_contents(app_path('views/public/dashboard.php')) ?: '', 'Event/Course') && str_contains(file_get_contents(app_path('views/public/dashboard.php')) ?: '', 'Certificates') && str_contains(file_get_contents(app_path('views/layouts/app.php')) ?: '', '/dashboard'), 'customer dashboard exposes account menus');
assertTrue(str_contains($appLayout, 'views/partials/support-widget.php') && str_contains($supportWidget, 'data-support-widget') && str_contains($supportWidget, '/support/chat'), 'public layout wires floating support widget');
assertTrue(!str_contains($supportWidget, 'Hi, I can help with speakers') && str_contains($supportWidget, "ask('__intro', false)") && str_contains($supportWidget, "event.key === 'Enter'") && str_contains($supportWidget, 'support-actions') && str_contains($supportWidget, 'AbortController'), 'public support widget uses agent-driven intro, action links, enter send, and stop control');
assertTrue(str_contains($appLayout, 'google-site-verification') && str_contains($appLayout, 'googletagmanager.com/gtag/js'), 'public layout wires Google tag and Search Console verification');
assertTrue(str_contains($integrations, 'google_site_tag_id') && str_contains($integrations, 'google_search_console_verification') && str_contains($integrations, 'admin_notification_email'), 'integrations expose Google Site Kit and SMTP admin notification fields');
$environment = file_get_contents(app_path('views/admin/environment.php')) ?: '';
$gitignore = file_get_contents(app_path('.gitignore')) ?: '';
assertTrue(str_contains($environment, 'Google AI Studio Diagnostics') && str_contains($environment, 'Project env files are disabled') && !str_contains($environment, 'env_raw') && str_contains($gitignore, '.env.*'), 'environment exposes diagnostics and project env files stay disabled');
assertTrue(!str_contains($appLayout, 'support-context'), 'support widget removes the old left context half');
$css = file_get_contents(app_path('assets/css/index.css')) ?: '';
assertTrue(str_contains($css, 'body.support-open main') && str_contains($css, 'body.support-open .sticky-register') && !str_contains($css, 'body.support-open .site-header') && str_contains($css, 'box-shadow: none'), 'desktop support sidebar docks content and sticky ticket bar without shrinking the top nav');
assertTrue(str_contains($branding, 'Brand Identity System') && str_contains($branding, 'Color Tokens') && str_contains($branding, 'Typography'), 'admin branding page exposes brand identity system');
assertTrue(str_contains($event, 'Speaker Lineup') && str_contains($event, 'Reserve Your Seat'), 'event page includes conversion sections');
assertTrue(str_contains($event, 'Join Now') && str_contains($event, 'Pay with Razorpay') && str_contains($event, 'paymentAction'), 'event ticket flow gates payment behind login and supports payment modes');
assertTrue(str_contains($event, 'speaker-initials') && str_contains($event, 'credentials'), 'event page supports speaker credentials and image fallback');
assertTrue(str_contains($paymentController, 'Razorpay integration is not configured yet'), 'internal Razorpay mode fails gracefully when keys are absent');
assertTrue(str_contains($paymentController, "upsert('registrations'") && strpos($paymentController, "upsert('registrations'") < strpos($paymentController, "header('Location: ' . \$paymentPageUrl)"), 'external payment page creates pending registration before redirect');
assertTrue(str_contains($checkout, 'checkout-popup') && str_contains($checkout, '/terms') && str_contains($checkout, '/privacy') && str_contains($checkout, 'checkout.razorpay.com/v1/checkout.js'), 'checkout uses in-app Razorpay popup with policy links');
assertTrue(str_contains($event, 'sticky-register') && str_contains($event, 'section-nav'), 'event page includes sticky registration and section navigation');
assertTrue(str_contains($event, 'event-hero-copy') && !str_contains($event, 'event-thumbnail') && !str_contains($event, '<span>Mode</span>') && str_contains($event, 'timeline-row') && str_contains($event, 'agenda-time') && str_contains($event, 'agenda-copy'), 'event page centers hero content without the old media or mode card and keeps agenda display');
assertTrue(str_contains($event, 'HURRY') && str_contains($event, 'TICKING') && str_contains($event, 'event-slots') && str_contains($event, 'data-countdown-deadline') && !str_contains($event, 'E-certificate included') && !str_contains($event, 'conference-note') && !str_contains($event, 'agenda slots'), 'event page shows countdown urgency and derived availability instead of old trust strip');
assertTrue(str_contains($admin, 'Event Management') && str_contains($admin, 'Events &amp; Classes') && strpos($admin, '/admin/speakers') > strpos($admin, 'Event Management'), 'admin nav groups event resources');
assertTrue(str_contains($admin, '/admin/support-tickets'), 'admin nav exposes support agent tickets');
assertTrue(str_contains($admin, '/admin/branding'), 'admin nav exposes branding system');
assertTrue(!str_contains($admin, 'legacy-marketplace') && !str_contains($home, 'legacy-source-brand'), 'active UI does not expose old domain labels');

$templates = file_get_contents(app_path('storage/data/notification_templates.json')) ?: '';
assertTrue(str_contains($templates, 'payment-success') && str_contains($templates, 'google-calendar-reminder') && str_contains($templates, 'certificate-ready') && str_contains($templates, 'newsletter') && str_contains($templates, 'signup-welcome') && str_contains($templates, 'admin-new-booking') && str_contains($templates, 'new-event'), 'notification templates cover payment, calendar, certificate, signup, booking, and newsletter');
assertTrue(is_file(app_path('integrations/google-oauth/GoogleOAuthClient.php')), 'Google OAuth client integration exists');

$planner = new \App\Services\PurchaseNotificationService(new JsonStoreService());
$jobs = $planner->queuePurchaseSuccess(['id'=>'test-reg','email'=>'buyer@example.com','phone'=>'+919999999999','event_slug'=>'global-gut-summit-2026','certificate_status'=>'pending'], $featured, ['email'=>'buyer@example.com','google_sub'=>'google-user','google_calendar_enabled'=>true]);
assertTrue(count($jobs) >= 6, 'purchase notification planner creates email, WhatsApp, calendar, and certificate jobs');
assertTrue((int)(array_values(array_filter($jobs, fn($job) => ($job['template_key'] ?? '') === 'certificate-ready'))[0]['available_at'] ?? 0) > time(), 'certificate notification is scheduled after event completion');
$queue = $store->read('notification_queue');
$store->write('notification_queue', array_values(array_filter($queue, fn($item) => ($item['registration_id'] ?? '') !== 'test-reg')));

$renderer = new \App\Services\EmailTemplateService($store);
$rendered = $renderer->render('signup-welcome', ['name'=>'Test User','title'=>'Welcome','message'=>'Hello','cta_url'=>'https://gutconference.online/dashboard','cta_label'=>'Dashboard']);
assertTrue(str_contains($rendered['html'], 'gutconference-logo.png') && str_contains($rendered['html'], 'linear-gradient') && str_contains($rendered['html'], 'Dashboard'), 'branded SMTP renderer includes logo, brand styling, and CTA');
$beforeQueue = $store->read('notification_queue');
$notifier = new \App\Services\NotificationQueueService($store);
$notifier->queueSignup(['id'=>'test-user','name'=>'Test User','email'=>'test-user@example.com']);
$notifier->queueBooking(['id'=>'test-booking','name'=>'Test User','email'=>'test-user@example.com','event_slug'=>'global-gut-summit-2026','payment_status'=>'pending'], $featured);
$queued = $store->read('notification_queue');
assertTrue(count(array_filter($queued, fn($job) => ($job['template_key'] ?? '') === 'signup-welcome')) >= 1 && count(array_filter($queued, fn($job) => ($job['template_key'] ?? '') === 'admin-new-booking')) >= 1, 'signup and booking queue user and admin SMTP notifications');
$store->write('notification_queue', $beforeQueue);

$beforeTickets = $store->read('support_tickets');
putenv('GOOGLE_AI_STUDIO_API_KEY=');
$_ENV['GOOGLE_AI_STUDIO_API_KEY'] = '';
$support = new \App\Services\SupportAgentService($store);
$supportReply = $support->reply('Please create a support ticket to improve the speaker agenda layout.', []);
assertTrue(!empty($supportReply['ticket']['id']) && str_contains($supportReply['answer'], 'support ticket'), 'support agent creates improvement tickets');
$adminReply = $support->reply('Create event for a new gut health class', ['name'=>'Admin','role'=>'admin']);
assertTrue(($adminReply['proposal']['collection'] ?? '') === 'events' && ($adminReply['proposal']['apply_endpoint'] ?? '') === '/support/admin/apply', 'admin support agent returns schema-backed apply proposal');
$ownerReply = $support->reply('who owns this portal?', []);
assertTrue(str_contains($ownerReply['answer'], 'Dr. Praveen Jacob'), 'guest support agent answers portal owner from CMS context');
$speakerReply = $support->reply('what is the event about who are all the speakers and agenda time how to book', []);
assertTrue(str_contains($speakerReply['answer'], 'Dr. Siba Dolai') && str_contains($speakerReply['answer'], '09:30') && count($speakerReply['links'] ?? []) >= 3, 'guest support agent answers event, speakers, agenda, and booking links');
$store->write('support_tickets', $beforeTickets);
$supportService = file_get_contents(app_path('app/Services/SupportAgentService.php')) ?: '';
$routerService = file_get_contents(app_path('app/Services/GeminiModelRouter.php')) ?: '';
assertTrue(str_contains($routerService, 'generativelanguage.googleapis.com/v1beta') && str_contains($routerService, ':generateContent?key=') && str_contains($routerService, 'GOOGLE_AI_MODEL_RETRIES') && str_contains($routerService, 'GOOGLE_AI_AUDIO_MODELS') && str_contains($supportService, 'roleRules'), 'support agent uses env-driven Google AI Studio routing with role rules');

$media = file_get_contents(app_path('views/admin/media.php')) ?: '';
assertTrue(str_contains($media, 'value="events"') && str_contains($media, 'value="speakers"') && str_contains($media, 'value="venues"') && str_contains($media, 'value="publishers"'), 'media contexts match conference CMS');
$mediaRecords = $store->read('media_files');
$speakerMedia = array_values(array_filter($mediaRecords, fn($item) => ($item['context'] ?? '') === 'speakers'));
assertTrue(count($speakerMedia) >= 9, 'media library includes seeded speaker images');

$phpFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path('app')));
foreach ($phpFiles as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $cmd = 'php -l ' . escapeshellarg($file->getPathname());
    exec($cmd, $output, $code);
    assertTrue($code === 0, 'syntax ok: ' . str_replace(app_path() . '/', '', $file->getPathname()));
}

echo "All GutConference CMS tests passed.\n";
