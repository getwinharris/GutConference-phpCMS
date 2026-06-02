<?php
namespace App\Controllers;
use App\Services\{AuditLogService,AuthService,EnvService,MediaService,ResourceService,SchemaService,SecretService,SettingsService,StoragePermissionService};
final class AdminController extends BaseController {
    protected string $layout = 'admin';
    public function __construct() {
        (new AuthService())->requireAdmin();
    }

    public function dashboard(): void{
        $eventCount = count((new ResourceService('events'))->all());
        $registrationCount = count((new ResourceService('registrations'))->all());
        $speakerCount = count((new ResourceService('speakers'))->all());
        $this->render('admin/dashboard', ['pageTitle' => 'Dashboard', 'eventCount' => $eventCount, 'registrationCount' => $registrationCount, 'speakerCount' => $speakerCount]);
    }
    public function events(): void{$this->resource('Events','events',$this->schemaFields('events',[]));}
    public function saveEvent(): void{$this->save('events');}
    public function deleteEvent(): void{$this->delete('events');}
    public function eventSections(): void{$this->resource('Event Sections','event_sections',$this->schemaFields('event_sections',[]));}
    public function saveEventSection(): void{$this->save('event_sections');}
    public function deleteEventSection(): void{$this->delete('event_sections');}
    public function speakers(): void{$this->resource('Speakers','speakers',$this->schemaFields('speakers',[]));}
    public function saveSpeaker(): void{$this->save('speakers');}
    public function deleteSpeaker(): void{$this->delete('speakers');}
    public function sessions(): void{$this->resource('Agenda Sessions','sessions',$this->schemaFields('sessions',[]));}
    public function saveSession(): void{$this->save('sessions');}
    public function deleteSession(): void{$this->delete('sessions');}
    public function venues(): void{$this->resource('Venues & Maps','venues',$this->schemaFields('venues',[]));}
    public function saveVenue(): void{$this->save('venues');}
    public function deleteVenue(): void{$this->delete('venues');}
    public function registrations(): void{$this->list('Registrations','registrations');}
    public function notificationTemplates(): void{$this->resource('Notification Templates','notification_templates',$this->schemaFields('notification_templates',[]));}
    public function saveNotificationTemplate(): void{$this->save('notification_templates');}
    public function deleteNotificationTemplate(): void{$this->delete('notification_templates');}
    public function notificationQueue(): void{$this->list('Notification Queue','notification_queue');}
    public function settings(): void{$this->render('admin/settings',['pageTitle' => 'Settings', 'title' => 'Site Settings', 'settings'=>(new SettingsService())->public(), 'adminCredentials'=>(new EnvService())->adminCredentials()]);}
    public function saveSettings(): void{(new SettingsService())->savePublic(['currency'=>$_POST['currency'] ?? 'INR','timezone'=>$_POST['timezone'] ?? 'Asia/Kolkata','featured_event_slug'=>$_POST['featured_event_slug'] ?? 'global-gut-summit-2026']); $this->flash('Settings saved.'); $this->redirect('/admin/settings');}
    public function saveAdminCredentials(): void{(new EnvService())->saveAdminCredentials($_POST); $this->flash('Admin credentials saved to .env.'); $this->redirect('/admin/settings');}
    public function integrations(): void{$this->render('admin/integrations',['pageTitle' => 'Integrations', 'secrets'=>(new SecretService())->all()]);}
    public function saveIntegrations(): void{(new SecretService())->save($_POST); $this->flash('Integration settings saved.'); $this->redirect('/admin/integrations');}
    public function backups(): void{$this->list('Backups','settings');}
    public function audit(): void{$this->list('Audit Log','audit_events');}
    public function contactSubmissions(): void{$this->resource('Contact Submissions','contact_submissions',['name','email','phone','subject','message','status']);}
    public function supportTickets(): void{$this->list('Support Tickets','support_tickets');}
    public function media(): void{$this->render('admin/media',['pageTitle'=>'Media Library','items'=>(new MediaService())->all()]);}
    public function uploadMedia(): void{$uploaded=(new MediaService())->upload($_FILES['media_files'] ?? [], $_POST['context'] ?? 'shared'); (new AuditLogService())->record('upload','media','',['count'=>count($uploaded),'context'=>$_POST['context'] ?? 'shared']); $this->flash(count($uploaded).' media file'.(count($uploaded) === 1 ? '' : 's').' uploaded.'); $this->redirect('/admin/media');}
    public function environment(): void{$this->render('admin/environment',['pageTitle'=>'Environment','envRaw'=>(new EnvService())->raw(),'permissions'=>(new StoragePermissionService())->status()]);}
    public function saveEnvironment(): void{(new EnvService())->saveRaw((string)($_POST['env_raw'] ?? '')); (new AuditLogService())->record('save','environment','.env',['keys'=>array_keys(EnvService::readFile(app_path('.env')))]); $this->flash('Environment saved.'); $this->redirect('/admin/environment');}
    public function fixPermissions(): void{(new StoragePermissionService())->fix(); (new AuditLogService())->record('fix','permissions','storage'); $this->flash('Storage permissions checked and updated where PHP is allowed.'); $this->redirect('/admin/environment');}
    public function projectMap(): void{$this->render('admin/project-map',['pageTitle' => 'Project Map', 'map'=>\App\Services\ProjectMapService::registry(),'validation'=>\App\Services\ProjectMapService::validate(\App\Services\ProjectMapService::registry())]);}
    private function list(string $title, ?string $collection = null): void{$this->render('admin/list',['pageTitle' => $title, 'title' => $title, 'collection' => $collection, 'items'=>$collection ? (new ResourceService($collection))->all() : []]);}
    private function resource(string $title,string $collection,array $fields): void{$this->render('admin/resource',['pageTitle' => $title, 'title' => $title, 'collection' => $collection, 'fields' => $fields, 'items'=>(new ResourceService($collection))->all(), 'mediaFiles'=>$this->mediaFor($collection)]);}
    private function save(string $collection): void{
        $data=$this->cleanPost();
        if(isset($data['items']))$data['items']=$this->splitList((string)$data['items']);
        foreach (['enabled','featured'] as $flag) {
            if (isset($data[$flag])) $data[$flag] = in_array((string)$data[$flag], ['1','true','yes','on'], true);
        }
        $uploaded=$this->uploadedMedia($collection);
        if ($collection === 'events' && $uploaded && empty($data['logo_url'])) $data['logo_url']=$uploaded[0]['path'];
        if ($collection === 'speakers' && $uploaded && empty($data['photo_url'])) $data['photo_url']=$uploaded[0]['path'];
        if ($collection === 'venues' && $uploaded && empty($data['image_url'])) $data['image_url']=$uploaded[0]['path'];
        $record=(new ResourceService($collection))->save($data);
        (new AuditLogService())->record('save',$collection,(string)($record['id'] ?? ''),['fields'=>array_keys($data),'uploaded_media'=>count($uploaded)]);
        $this->flash('Saved.');
        $this->redirect('/admin/'.$collection);
    }
    private function delete(string $collection): void{
        $id=(string)($_POST['id']??'');
        (new ResourceService($collection))->delete($id);
        (new AuditLogService())->record('delete',$collection,$id);
        $this->flash('Deleted.');
        $this->redirect('/admin/'.$collection);
    }
    private function cleanPost(): array {
        return array_filter($_POST, fn($v) => $v !== '' && $v !== null);
    }
    private function mergeExistingRecord(string $collection, array $data): array {
        $id=(string)($data['id'] ?? '');
        if ($id === '') return $data;
        foreach ((new ResourceService($collection))->all() as $item) {
            if ((string)($item['id'] ?? '') === $id) return array_merge($item, $data);
        }
        return $data;
    }
    private function splitList(string $value): array {
        return array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $value) ?: [])));
    }
    private function uploadedMedia(string $collection): array { return (new MediaService())->upload($_FILES['media_files'] ?? [], $this->mediaContext($collection)); }
    private function mediaFor(string $collection): array { return in_array($collection, ['events','speakers','venues'], true) ? (new MediaService())->all($this->mediaContext($collection)) : []; }
    private function mediaContext(string $collection): string { return match($collection){'events'=>'events','speakers'=>'speakers','venues'=>'venues',default=>'shared'}; }
    private function schemaFields(string $collection, array $fallback): array { return (new SchemaService())->adminFields($collection, $fallback); }
}
