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
$required = ['users','events','event_sections','speakers','sessions','venues','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','media_files'];

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

$map = ProjectMapService::registry();
$validation = ProjectMapService::validate($map);
assertTrue($validation['missing_route_mappings'] === [], 'project map has route mappings');
assertTrue($validation['missing_services'] === [], 'project map services are registered');
assertTrue($validation['missing_collections'] === [], 'project map collections are registered');

$routePaths = array_column($map['routes'], 'path');
foreach (['/','/events','/events/{slug}','/admin/events','/admin/event_sections','/admin/speakers','/admin/sessions','/admin/venues','/admin/registrations','/admin/notification_templates'] as $path) {
    assertTrue(in_array($path, $routePaths, true), "route exists: {$path}");
}

$home = file_get_contents(app_path('views/public/home.php')) ?: '';
$event = file_get_contents(app_path('views/public/event.php')) ?: '';
$admin = file_get_contents(app_path('views/layouts/admin.php')) ?: '';
assertTrue(str_contains($home, 'GutConference'), 'home is rebranded');
assertTrue(str_contains($home, 'data-carousel') && str_contains($home, 'Product Owner'), 'home includes owner and insights carousel sections');
assertTrue(str_contains($event, 'Speaker Lineup') && str_contains($event, 'Reserve Your Seat'), 'event page includes conversion sections');
assertTrue(str_contains($event, 'sticky-register') && str_contains($event, 'section-nav'), 'event page includes sticky registration and section navigation');
assertTrue(str_contains($event, 'event-thumbnail') && str_contains($event, 'slot-chip'), 'event page includes thumbnail and agenda slot display');
assertTrue(str_contains($event, 'E-certificate included') && str_contains($featured['organizers'] ?? '', 'Alpha Naturals'), 'event page uses official brief content');
assertTrue(str_contains($admin, '/admin/events') && str_contains($admin, '/admin/venues'), 'admin nav exposes conference resources');
assertTrue(!str_contains($admin, 'legacy-marketplace') && !str_contains($home, 'legacy-source-brand'), 'active UI does not expose old domain labels');

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
