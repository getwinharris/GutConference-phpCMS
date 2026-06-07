<?php
namespace App\Services;

final class ProjectMapService {
    public static function registry(): array {
        $routes = [
            ['method'=>'GET','path'=>'/','name'=>'home','page'=>'public/home','controller'=>'PublicController@home','services'=>['EventService','ResourceService']],
            ['method'=>'GET','path'=>'/events','name'=>'events','page'=>'public/events','controller'=>'PublicController@events','services'=>['EventService']],
            ['method'=>'GET','path'=>'/terms','name'=>'terms','page'=>'public/terms','controller'=>'PublicController@terms','services'=>[]],
            ['method'=>'GET','path'=>'/privacy','name'=>'privacy','page'=>'public/privacy','controller'=>'PublicController@privacy','services'=>[]],
            ['method'=>'GET','path'=>'/dashboard','name'=>'dashboard','page'=>'public/dashboard','controller'=>'PublicController@dashboard','services'=>['AuthService','JsonStoreService','EventService']],
            ['method'=>'GET','path'=>'/events/{slug}','name'=>'events.show','page'=>'public/event','controller'=>'PublicController@event','services'=>['EventService','SecretService']],
            ['method'=>'POST','path'=>'/events/{slug}/checkout','name'=>'events.checkout','page'=>'public/checkout','controller'=>'PaymentController@checkout','services'=>['AuthService','EventService','SecretService','JsonStoreService']],
            ['method'=>'POST','path'=>'/events/{slug}/payment/verify','name'=>'events.payment.verify','page'=>'public/checkout','controller'=>'PaymentController@verify','services'=>['AuthService','EventService','SecretService','JsonStoreService','PaymentService','PurchaseNotificationService']],
            ['method'=>'GET','path'=>'/conference/{slug}','name'=>'conference.show','page'=>'public/event','controller'=>'PublicController@event','services'=>['EventService','SecretService']],
            ['method'=>'GET','path'=>'/contact','name'=>'contact','page'=>'public/contact','controller'=>'PublicController@contact','services'=>[]],
            ['method'=>'POST','path'=>'/contact','name'=>'contact.post','page'=>'public/contact','controller'=>'PublicController@contact','services'=>['ContactService']],
            ['method'=>'POST','path'=>'/support/chat','name'=>'support.chat','page'=>'public/support-widget','controller'=>'SupportController@chat','services'=>['AuthService','SupportAgentService','AgentContextService','JsonStoreService','SecretService']],
            ['method'=>'POST','path'=>'/support/admin/apply','name'=>'support.admin.apply','page'=>'public/support-widget','controller'=>'SupportController@adminApply','services'=>['AuthService','ResourceService','SchemaService','AuditLogService']],
            ['method'=>'GET','path'=>'/login','name'=>'login','page'=>'public/login','controller'=>'PublicController@login','services'=>['AuthService']],
            ['method'=>'GET','path'=>'/signup','name'=>'signup','page'=>'public/signup','controller'=>'PublicController@signup','services'=>['AuthService']],
            ['method'=>'POST','path'=>'/signup','name'=>'signup.post','page'=>'public/signup','controller'=>'AuthController@signupPost','services'=>['JsonStoreService']],
            ['method'=>'GET','path'=>'/forgot-password','name'=>'forgot-password','page'=>'public/forgot-password','controller'=>'AuthController@forgotPassword','services'=>['PasswordResetService']],
            ['method'=>'POST','path'=>'/forgot-password','name'=>'forgot-password.post','page'=>'public/forgot-password','controller'=>'AuthController@forgotPasswordPost','services'=>['PasswordResetService']],
            ['method'=>'GET','path'=>'/reset-password','name'=>'reset-password','page'=>'public/reset-password','controller'=>'AuthController@resetPassword','services'=>['PasswordResetService']],
            ['method'=>'POST','path'=>'/reset-password','name'=>'reset-password.post','page'=>'public/reset-password','controller'=>'AuthController@resetPasswordPost','services'=>['PasswordResetService']],
            ['method'=>'POST','path'=>'/login','name'=>'login.post','page'=>'public/login','controller'=>'AuthController@loginPost','services'=>['JsonStoreService']],
            ['method'=>'GET','path'=>'/auth/google','name'=>'google.oauth','page'=>'public/login','controller'=>'AuthController@googleRedirect','services'=>['SecretService']],
            ['method'=>'GET','path'=>'/auth/google/callback','name'=>'google.oauth.callback','page'=>'public/login','controller'=>'AuthController@googleCallback','services'=>['SecretService','JsonStoreService']],
            ['method'=>'GET','path'=>'/logout','name'=>'logout','page'=>'public/login','controller'=>'AuthController@logout','services'=>['AuthService']],
            ['method'=>'GET','path'=>'/admin','name'=>'admin.dashboard','page'=>'admin/dashboard','controller'=>'AdminController@dashboard','services'=>['ResourceService']],
            ['method'=>'GET','path'=>'/admin/events','name'=>'admin.events','page'=>'admin/resource','controller'=>'AdminController@events','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/events/save','name'=>'admin.events.save','page'=>'admin/resource','controller'=>'AdminController@saveEvent','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/events/delete','name'=>'admin.events.delete','page'=>'admin/resource','controller'=>'AdminController@deleteEvent','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/event_sections','name'=>'admin.event-sections','page'=>'admin/resource','controller'=>'AdminController@eventSections','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/event_sections/save','name'=>'admin.event-sections.save','page'=>'admin/resource','controller'=>'AdminController@saveEventSection','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/event_sections/delete','name'=>'admin.event-sections.delete','page'=>'admin/resource','controller'=>'AdminController@deleteEventSection','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/speakers','name'=>'admin.speakers','page'=>'admin/resource','controller'=>'AdminController@speakers','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/speakers/save','name'=>'admin.speakers.save','page'=>'admin/resource','controller'=>'AdminController@saveSpeaker','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/speakers/delete','name'=>'admin.speakers.delete','page'=>'admin/resource','controller'=>'AdminController@deleteSpeaker','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/sessions','name'=>'admin.sessions','page'=>'admin/resource','controller'=>'AdminController@sessions','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/sessions/save','name'=>'admin.sessions.save','page'=>'admin/resource','controller'=>'AdminController@saveSession','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/sessions/delete','name'=>'admin.sessions.delete','page'=>'admin/resource','controller'=>'AdminController@deleteSession','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/venues','name'=>'admin.venues','page'=>'admin/resource','controller'=>'AdminController@venues','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/venues/save','name'=>'admin.venues.save','page'=>'admin/resource','controller'=>'AdminController@saveVenue','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/venues/delete','name'=>'admin.venues.delete','page'=>'admin/resource','controller'=>'AdminController@deleteVenue','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/publishers','name'=>'admin.publishers','page'=>'admin/resource','controller'=>'AdminController@publishers','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/publishers/save','name'=>'admin.publishers.save','page'=>'admin/resource','controller'=>'AdminController@savePublisher','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/publishers/delete','name'=>'admin.publishers.delete','page'=>'admin/resource','controller'=>'AdminController@deletePublisher','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/registrations','name'=>'admin.registrations','page'=>'admin/list','controller'=>'AdminController@registrations','services'=>['ResourceService']],
            ['method'=>'GET','path'=>'/admin/notification_templates','name'=>'admin.notification-templates','page'=>'admin/resource','controller'=>'AdminController@notificationTemplates','services'=>['ResourceService','SchemaService']],
            ['method'=>'POST','path'=>'/admin/notification_templates/save','name'=>'admin.notification-templates.save','page'=>'admin/resource','controller'=>'AdminController@saveNotificationTemplate','services'=>['ResourceService','AuditLogService']],
            ['method'=>'POST','path'=>'/admin/notification_templates/delete','name'=>'admin.notification-templates.delete','page'=>'admin/resource','controller'=>'AdminController@deleteNotificationTemplate','services'=>['ResourceService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/notification_queue','name'=>'admin.notification-queue','page'=>'admin/list','controller'=>'AdminController@notificationQueue','services'=>['ResourceService']],
            ['method'=>'POST','path'=>'/admin/notification_queue/process','name'=>'admin.notification-queue.process','page'=>'admin/list','controller'=>'AdminController@processNotificationQueue','services'=>['NotificationQueueService']],
            ['method'=>'GET','path'=>'/admin/settings','name'=>'admin.settings','page'=>'admin/settings','controller'=>'AdminController@settings','services'=>['SettingsService']],
            ['method'=>'POST','path'=>'/admin/settings/save','name'=>'admin.settings.save','page'=>'admin/settings','controller'=>'AdminController@saveSettings','services'=>['SettingsService']],
            ['method'=>'POST','path'=>'/admin/settings/admin-credentials','name'=>'admin.settings.admin-credentials','page'=>'admin/settings','controller'=>'AdminController@saveAdminCredentials','services'=>['SecretService']],
            ['method'=>'GET','path'=>'/admin/integrations','name'=>'admin.integrations','page'=>'admin/integrations','controller'=>'AdminController@integrations','services'=>['SecretService']],
            ['method'=>'POST','path'=>'/admin/integrations/save','name'=>'admin.integrations.save','page'=>'admin/integrations','controller'=>'AdminController@saveIntegrations','services'=>['SecretService']],
            ['method'=>'GET','path'=>'/admin/contact-submissions','name'=>'admin.contact-submissions','page'=>'admin/resource','controller'=>'AdminController@contactSubmissions','services'=>['ContactService']],
            ['method'=>'GET','path'=>'/admin/support-tickets','name'=>'admin.support-tickets','page'=>'admin/list','controller'=>'AdminController@supportTickets','services'=>['ResourceService']],
            ['method'=>'GET','path'=>'/admin/branding','name'=>'admin.branding','page'=>'admin/branding','controller'=>'AdminController@branding','services'=>['SettingsService','SecretService']],
            ['method'=>'GET','path'=>'/admin/media','name'=>'admin.media','page'=>'admin/media','controller'=>'AdminController@media','services'=>['MediaService']],
            ['method'=>'POST','path'=>'/admin/media/upload','name'=>'admin.media.upload','page'=>'admin/media','controller'=>'AdminController@uploadMedia','services'=>['MediaService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/audit-log','name'=>'admin.audit','page'=>'admin/list','controller'=>'AdminController@audit','services'=>['AuditLogService']],
            ['method'=>'GET','path'=>'/admin/backups','name'=>'admin.backups','page'=>'admin/list','controller'=>'AdminController@backups','services'=>['JsonStoreService']],
            ['method'=>'GET','path'=>'/admin/environment','name'=>'admin.environment','page'=>'admin/environment','controller'=>'AdminController@environment','services'=>['StoragePermissionService']],
            ['method'=>'POST','path'=>'/admin/environment/save','name'=>'admin.environment.save','page'=>'admin/environment','controller'=>'AdminController@saveEnvironment','services'=>['AuditLogService']],
            ['method'=>'POST','path'=>'/admin/environment/fix-permissions','name'=>'admin.environment.fix-permissions','page'=>'admin/environment','controller'=>'AdminController@fixPermissions','services'=>['StoragePermissionService','AuditLogService']],
            ['method'=>'GET','path'=>'/admin/developer/project-map','name'=>'admin.project-map','page'=>'admin/project-map','controller'=>'AdminController@projectMap','services'=>['ProjectMapService']],
        ];
        foreach ($routes as &$route) {
            if ((str_starts_with($route['path'], '/admin')) && !in_array('AuthService', $route['services'], true)) {
                $route['services'][] = 'AuthService';
            }
        }
        unset($route);
        return [
            'routes'=>$routes,
            'services'=>['AuthService','EventService','SettingsService','ProjectMapService','JsonStoreService','AuditLogService','ResourceService','SecretService','EnvService','ContactService','PasswordResetService','PurchaseNotificationService','NotificationQueueService','EmailTemplateService','PaymentService','MediaService','StoragePermissionService','SchemaService','SupportAgentService','AgentContextService','GeminiModelRouter','UserContextService'],
            'integrations'=>['RazorpayClient','GoogleOAuthClient'],
            'collections'=>['users','events','event_sections','speakers','sessions','venues','publishers','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','support_tickets','media_files'],
        ];
    }

    public static function scan(): array {
        $root = dirname(__DIR__, 2);
        $map = self::registry();
        $controllers = [];
        foreach (glob($root . '/app/Controllers/*.php') ?: [] as $file) {
            $cls = basename($file, '.php');
            $src = (string)file_get_contents($file);
            preg_match_all('/public function ([A-Za-z0-9_]+)/', $src, $m);
            $controllers[$cls] = [
                'file' => 'app/Controllers/' . basename($file),
                'method_count' => count($m[1] ?? []),
                'methods' => $m[1] ?? [],
            ];
        }
        ksort($controllers);

        $services = [];
        foreach (glob($root . '/app/Services/*.php') ?: [] as $file) {
            $cls = basename($file, '.php');
            $src = (string)file_get_contents($file);
            preg_match_all('/public function ([A-Za-z0-9_]+)/', $src, $m);
            $services[$cls] = [
                'file' => 'app/Services/' . basename($file),
                'method_count' => count($m[1] ?? []),
                'methods' => $m[1] ?? [],
            ];
        }
        ksort($services);

        $views = [];
        foreach (glob($root . '/views/**/*.php') ?: [] as $file) {
            $rel = 'views/' . ltrim(str_replace($root . '/views/', '', $file), '/');
            $views[$rel] = ['file' => $rel, 'size' => filesize($file)];
        }
        ksort($views);

        $integrations = [];
        foreach (glob($root . '/integrations/**/*.php') ?: [] as $file) {
            $rel = 'integrations/' . ltrim(str_replace($root . '/integrations/', '', $file), '/');
            $cls = basename($file, '.php');
            $src = (string)file_get_contents($file);
            preg_match_all('/public function ([A-Za-z0-9_]+)/', $src, $m);
            $integrations[$cls] = [
                'file' => $rel,
                'method_count' => count($m[1] ?? []),
                'methods' => $m[1] ?? [],
            ];
        }
        ksort($integrations);

        $schema = [];
        $schemaFile = $root . '/storage/schema/collections.json';
        if (is_file($schemaFile)) {
            $data = json_decode((string)file_get_contents($schemaFile), true) ?: [];
            foreach (($data['collections'] ?? []) as $name => $spec) {
                $schema[$name] = [
                    'file' => $spec['file'] ?? null,
                    'primary_key' => $spec['primary_key'] ?? null,
                    'fields' => array_keys($spec['fields'] ?? []),
                    'field_count' => count($spec['fields'] ?? []),
                    'admin_fields' => $spec['admin_fields'] ?? [],
                    'media_fields' => $spec['media_fields'] ?? [],
                    'agent_context' => $spec['agent_context'] ?? [],
                ];
            }
            ksort($schema);
        }

        $storage = [];
        foreach (glob($root . '/storage/data/*.json') ?: [] as $file) {
            $name = basename($file, '.json');
            $arr = json_decode((string)file_get_contents($file), true);
            $storage[$name] = [
                'file' => 'storage/data/' . basename($file),
                'record_count' => is_array($arr) ? count($arr) : 0,
                'size' => filesize($file),
            ];
        }
        ksort($storage);

        $routesByController = [];
        $routesByView = [];
        $routesByService = [];
        foreach ($map['routes'] as $route) {
            [$cls, $method] = array_pad(explode('@', $route['controller']), 2, null);
            $routesByController[$cls][] = ['method' => $method, 'path' => $route['path']];
            $routesByView[$route['page']][] = $route['path'];
            foreach ($route['services'] as $svc) {
                $routesByService[$svc][] = $route['path'];
            }
        }

        $collections = ['users','events','event_sections','speakers','sessions','venues','publishers','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','support_tickets','media_files','adminsecrets','user-context'];
        $collectionUsage = [];
        foreach (glob($root . '/app/Services/*.php') ?: [] as $file) {
            $cls = basename($file, '.php');
            $src = (string)file_get_contents($file);
            foreach ($collections as $col) {
                if (str_contains($src, "'" . $col . "'") || str_contains($src, '"' . $col . '"') || str_contains($src, $col . '/')) {
                    $collectionUsage[$col][] = $cls;
                }
            }
        }
        foreach ($collectionUsage as $col => $svcs) {
            $collectionUsage[$col] = array_values(array_unique($svcs));
            sort($collectionUsage[$col]);
        }
        ksort($collectionUsage);

        $gaps = [];
        foreach ($controllers as $cls => $info) {
            if (!isset($routesByController[$cls])) {
                $gaps[] = ['type' => 'controller_unwired', 'severity' => 'high', 'detail' => "Controller $cls is declared in app/Controllers/ but not referenced by any route."];
            }
        }
        foreach ($services as $cls => $info) {
            if (!isset($routesByService[$cls])) {
                $gaps[] = ['type' => 'service_unwired', 'severity' => 'high', 'detail' => "Service $cls is declared in app/Services/ but not referenced by any route. It may still be used by another service (see collection_usage)."];
            }
        }
        foreach ($views as $path => $info) {
            $logicalKey = preg_replace('/\.php$/', '', $path);
            $logicalKey = str_replace('views/', '', $logicalKey);
            if (!isset($routesByView[$logicalKey]) && !str_starts_with($path, 'views/layouts/') && !str_starts_with($path, 'views/partials/')) {
                $gaps[] = ['type' => 'view_unwired', 'severity' => 'medium', 'detail' => "View $path is on disk but not bound to any route in the registry."];
            }
        }
        foreach ($integrations as $cls => $info) {
            if (!in_array($cls, $map['integrations'], true)) {
                $gaps[] = ['type' => 'integration_unregistered', 'severity' => 'medium', 'detail' => "Integration $cls exists on disk but is not listed in ProjectMapService::registry() integrations."];
            }
        }
        foreach ($schema as $name => $info) {
            if (!in_array($name, $map['collections'], true)) {
                $gaps[] = ['type' => 'schema_collection_unregistered', 'severity' => 'low', 'detail' => "Schema collection $name is defined in storage/schema/collections.json but not listed in ProjectMapService::registry() collections."];
            }
            if (!isset($storage[$name])) {
                $gaps[] = ['type' => 'schema_collection_no_file', 'severity' => 'low', 'detail' => "Schema collection $name is defined in the schema but has no storage/data/$name.json file on disk."];
            }
        }
        foreach ($storage as $name => $info) {
            if (!isset($schema[$name])) {
                $gaps[] = ['type' => 'storage_file_no_schema', 'severity' => 'medium', 'detail' => "storage/data/$name.json exists on disk but has no schema entry in storage/schema/collections.json."];
            }
            if (empty($collectionUsage[$name])) {
                $gaps[] = ['type' => 'storage_collection_unused', 'severity' => 'low', 'detail' => "Collection $name has no service that reads it (static search). May still be used via a generic path."];
            }
        }
        foreach ($map['collections'] as $name) {
            if (!isset($schema[$name])) {
                $gaps[] = ['type' => 'registry_collection_no_schema', 'severity' => 'high', 'detail' => "ProjectMapService::registry() lists collection $name but it has no schema entry."];
            }
        }
        usort($gaps, fn($a, $b) => [$a['severity'], $a['type']] <=> [$b['severity'], $b['type']]);

        return [
            'generated_at' => date('c'),
            'controllers' => $controllers,
            'services' => $services,
            'views' => $views,
            'integrations' => $integrations,
            'schema_collections' => $schema,
            'storage_collections' => $storage,
            'routes_by_controller' => $routesByController,
            'routes_by_view' => $routesByView,
            'routes_by_service' => $routesByService,
            'collection_usage' => $collectionUsage,
            'gaps' => $gaps,
            'summary' => [
                'routes' => count($map['routes']),
                'controllers' => count($controllers),
                'services' => count($services),
                'views' => count($views),
                'integrations' => count($integrations),
                'schema_collections' => count($schema),
                'storage_collections' => count($storage),
                'gap_count' => count($gaps),
            ],
        ];
    }

    public static function renderSystematicMermaid(): string {
        $map = self::registry();
        $scan = self::scan();
        $mmd = "flowchart LR\n";
        $mmd .= "  classDef route fill:#e3f2fd,stroke:#1976d2,color:#0d47a1\n";
        $mmd .= "  classDef controller fill:#fff3e0,stroke:#f57c00,color:#e65100\n";
        $mmd .= "  classDef service fill:#e8f5e9,stroke:#388e3c,color:#1b5e20\n";
        $mmd .= "  classDef view fill:#f3e5f5,stroke:#7b1fa2,color:#4a148c\n";
        $mmd .= "  classDef integration fill:#fce4ec,stroke:#c2185b,color:#880e4f\n";
        $mmd .= "  classDef schema fill:#e0f7fa,stroke:#00838f,color:#006064\n";
        $mmd .= "  classDef storage fill:#fff8e1,stroke:#ff8f00,color:#e65100\n";
        $mmd .= "  classDef tool fill:#ede7f6,stroke:#5e35b1,color:#311b92\n";
  $mmd .= "  classDef gap fill:#ffebee,stroke:#c62828,color:#b71c1c\n";
  $mmd .= "  %% Do not edit by hand. Regenerate with php tools/generate-project-map.php. The %% Summary line below is the only metadata and keeps the output byte-deterministic for the validator.\n";
  $mmd .= "  %% Summary: " . json_encode($scan['summary']) . "\n\n";

        $bucket = function (string $path): string {
            if (str_starts_with($path, '/admin')) return 'ADMIN';
            if (str_starts_with($path, '/auth') || $path === '/login' || $path === '/signup' || str_starts_with($path, '/forgot') || str_starts_with($path, '/reset') || $path === '/logout') return 'AUTH';
            if (str_contains($path, 'checkout') || str_contains($path, 'payment') || str_starts_with($path, '/payment')) return 'PAYMENT';
            if (str_starts_with($path, '/support')) return 'SUPPORT';
            return 'PUBLIC';
        };
        $routesByBucket = ['PUBLIC' => [], 'AUTH' => [], 'PAYMENT' => [], 'SUPPORT' => [], 'ADMIN' => []];
        foreach ($map['routes'] as $i => $route) {
            $routesByBucket[$bucket($route['path'])][] = [$i, $route];
        }
        foreach ($routesByBucket as $name => $routes) {
            if (empty($routes)) continue;
            $mmd .= "  subgraph B{$name}[\"{$name} routes\"]\n";
            foreach ($routes as [$i, $route]) {
                $routeNode = 'r'.$i;
                $mmd .= "    {$routeNode}[\"" . $route['method'] . ' ' . $route['path'] . "\"]:::route\n";
            }
            $mmd .= "  end\n\n";
        }

        $mmd .= "  subgraph CTRL[\"Controllers (app/Controllers/)\"]\n";
        foreach (array_keys($scan['controllers']) as $cls) {
            $node = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
            $mmd .= "    {$node}[\"$cls\"]:::controller\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph SVC[\"Services (app/Services/)\"]\n";
        foreach (array_keys($scan['services']) as $cls) {
            $node = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
            $mmd .= "    {$node}[\"$cls\"]:::service\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph VIEW[\"Views (views/)\"]\n";
        foreach (array_keys($scan['views']) as $path) {
            $node = 'v_'.md5($path);
            $label = str_replace(['/', '.'], ['_', '_'], $path);
            $mmd .= "    {$node}[\"$label\"]:::view\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph INT[\"Integrations (integrations/)\"]\n";
        foreach (array_keys($scan['integrations']) as $cls) {
            $node = 'i_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
            $mmd .= "    {$node}[\"$cls\"]:::integration\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph SCH[\"Schema collections (storage/schema/collections.json)\"]\n";
        foreach (array_keys($scan['schema_collections']) as $name) {
            $node = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
            $mmd .= "    {$node}[\"$name\"]:::schema\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph DAT[\"Storage data files (storage/data/)\"]\n";
        foreach (array_keys($scan['storage_collections']) as $name) {
            $node = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
            $mmd .= "    {$node}[\"$name.json\"]:::storage\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph TOL[\"Tools (tools/)\"]\n";
        foreach (['generate-project-map.php', 'validate-project-map.php', 'smoke-local.php'] as $tool) {
            $node = 't_'.preg_replace('/[^A-Za-z0-9]/', '_', $tool);
            $mmd .= "    {$node}[\"$tool\"]:::tool\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  subgraph GAP[\"Gaps & missing links\"]\n";
        $gi = 0;
        foreach ($scan['gaps'] as $g) {
            $node = 'g'.$gi++;
            $mmd .= "    {$node}[\"[" . $g['severity'] . "] " . $g['type'] . "\"]:::gap\n";
        }
        $mmd .= "  end\n\n";

        $mmd .= "  %% Edges: route -> controller -> service\n";
        foreach ($map['routes'] as $i => $route) {
            $routeNode = 'r'.$i;
            $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $route['controller']);
            $mmd .= "  {$routeNode} --> {$ctrlNode}\n";
            foreach ($route['services'] as $service) {
                $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $service);
                $mmd .= "  {$ctrlNode} --> {$svcNode}\n";
            }
            $viewKey = $route['page'];
            if (isset($scan['views']['views/' . $viewKey . '.php'])) {
                $viewNode = 'v_'.md5('views/' . $viewKey . '.php');
                $mmd .= "  {$ctrlNode} -.renders.-> {$viewNode}\n";
            }
        }
        $mmd .= "\n  %% Edges: service -> schema collection -> storage file\n";
        foreach ($scan['collection_usage'] as $col => $svcs) {
            $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $svcs[0] ?? '');
            $colNode = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $col);
            $datNode = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $col);
            if (isset($scan['schema_collections'][$col])) {
                $mmd .= "  {$svcNode} --> {$colNode}\n";
            }
            if (isset($scan['storage_collections'][$col])) {
                $mmd .= "  {$colNode} -.file.-> {$datNode}\n";
            }
        }

        if (isset($scan['integrations']['RazorpayClient'])) {
            $mmd .= "\n  %% Edges: payment + google integrations\n";
            $mmd .= "  s_PaymentService --> i_RazorpayClient\n";
        }
        if (isset($scan['integrations']['GoogleOAuthClient'])) {
            $mmd .= "  s_SecretService --> i_GoogleOAuthClient\n";
        }

        $mmd .= "\n  %% Edges: tools regenerate / validate / smoke the map\n";
        $mmd .= "  t_generate_project_map_php -.regenerates.-> TOL\n";
        $mmd .= "  t_validate_project_map_php -.checks.-> TOL\n";
        $mmd .= "  t_smoke_local_php -.smoke.-> TOL\n\n";

        $mmd .= "  %% Dashed edges: missing links (red gaps)\n";
        $gi = 0;
        foreach ($scan['controllers'] as $cls => $info) {
            if (!isset($scan['routes_by_controller'][$cls])) {
                $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
                $gapNode = 'g'.$gi++;
                $mmd .= "  {$ctrlNode} -.missing-route.-> {$gapNode}\n";
            }
        }
        foreach ($scan['services'] as $cls => $info) {
            if (!isset($scan['routes_by_service'][$cls])) {
                $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
                $gapNode = 'g'.$gi++;
                $mmd .= "  {$svcNode} -.no-route.-> {$gapNode}\n";
            }
        }
        foreach ($scan['views'] as $path => $info) {
            $logicalKey = preg_replace('/\.php$/', '', $path);
            $logicalKey = str_replace('views/', '', $logicalKey);
            if (!isset($scan['routes_by_view'][$logicalKey]) && !str_starts_with($path, 'views/layouts/') && !str_starts_with($path, 'views/partials/')) {
                $viewNode = 'v_'.md5($path);
                $gapNode = 'g'.$gi++;
                $mmd .= "  {$viewNode} -.no-route.-> {$gapNode}\n";
            }
        }
        foreach ($scan['storage_collections'] as $name => $info) {
            if (!isset($scan['schema_collections'][$name])) {
                $datNode = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
                $gapNode = 'g'.$gi++;
                $mmd .= "  {$datNode} -.no-schema.-> {$gapNode}\n";
            }
        }
        foreach ($scan['schema_collections'] as $name => $info) {
            if (!isset($scan['storage_collections'][$name])) {
                $colNode = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
                $gapNode = 'g'.$gi++;
                $mmd .= "  {$colNode} -.no-file.-> {$gapNode}\n";
            }
        }

        return $mmd;
    }

    public static function validate(array $map): array {
        $missingRouteMappings = array_values(array_filter($map['routes'], fn($r) => empty($r['controller']) || empty($r['page'])));
        $used = array_unique(array_merge(...array_map(fn($r) => $r['services'], $map['routes'])));
        $requiredCollections = ['users','events','event_sections','speakers','sessions','venues','publishers','registrations','notification_templates','notification_queue','settings','audit_events','contact_submissions','support_tickets','media_files'];
        return [
            'missing_route_mappings'=>$missingRouteMappings,
            'missing_services'=>array_values(array_diff($used, $map['services'])),
            'missing_collections'=>array_values(array_diff($requiredCollections, $map['collections']))
        ];
    }
}
