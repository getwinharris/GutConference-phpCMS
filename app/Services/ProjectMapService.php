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
