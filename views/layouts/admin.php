<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin - GutConference Online</title>
<style>
:root{--ink:#142331;--muted:#607180;--line:#d9e7e7;--bg:#f5faf9;--card:#fff;--green:#5fac3f;--teal:#35b7a5;--blue:#087fc0;--danger:#c0392b;--r:8px}
*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:var(--bg);color:var(--ink)}
a{color:inherit;text-decoration:none}.admin-shell{display:grid;grid-template-columns:250px 1fr;min-height:100vh}
.admin-sidebar{background:#061d2b;color:rgba(255,255,255,.66);padding:20px 0;position:sticky;top:0;height:100vh;overflow:auto}
.admin-brand{padding:0 20px 18px;border-bottom:1px solid rgba(255,255,255,.08);color:#fff;font-weight:900}.admin-brand small{display:block;color:rgba(255,255,255,.45);font-size:12px;margin-top:4px}
.admin-nav{display:grid;padding:12px 0}.admin-nav strong{padding:16px 20px 7px;font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.35)}
.admin-nav a{padding:10px 20px;border-left:3px solid transparent;font-size:14px}.admin-nav a:hover,.admin-nav a.active{background:rgba(255,255,255,.07);border-left-color:var(--teal);color:#fff}
.admin-main{min-width:0}.admin-top{height:64px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 24px;position:sticky;top:0;z-index:5}
.admin-body{padding:24px}.admin-card{background:#fff;border:1px solid var(--line);border-radius:8px;padding:20px;margin-bottom:20px;box-shadow:0 8px 24px rgba(7,55,99,.05)}
.admin-form__row{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}label{display:grid;gap:6px;color:var(--muted);font-size:12px;font-weight:800;text-transform:uppercase}
input,textarea,select{width:100%;border:1px solid var(--line);border-radius:8px;padding:10px;font:inherit;background:#fff;color:var(--ink)}textarea{min-height:96px}
.btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:8px;padding:10px 14px;font-weight:800;cursor:pointer}.btn-primary{background:linear-gradient(135deg,var(--green),var(--teal),var(--blue));color:#fff}.btn-ghost{background:#eef6f6;color:var(--ink)}.btn-danger{background:#fdeceb;color:var(--danger)}
.badge{display:inline-flex;border-radius:999px;padding:5px 9px;background:#edf7f5;color:var(--blue);font-size:12px;font-weight:800}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid var(--line);padding:10px;text-align:left;font-size:13px;vertical-align:top}th{color:var(--muted);text-transform:uppercase;font-size:11px}.flash{padding:12px 14px;background:#eaf8f2;border:1px solid #bee8d4;border-radius:8px;margin-bottom:18px}.admin-upload-panel{background:#f6fbfb;border:1px dashed var(--line);border-radius:8px;padding:14px;margin-top:16px}.admin-media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px}.admin-media-tile{border:1px solid var(--line);border-radius:8px;padding:8px;background:#fff}.admin-media-tile img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:6px}.admin-image-preview{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}.admin-image-preview img{width:76px;height:76px;object-fit:cover;border-radius:6px;border:1px solid var(--line)}
@media(max-width:840px){.admin-shell{grid-template-columns:1fr}.admin-sidebar{position:relative;height:auto}.admin-body{padding:14px}}
</style>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-brand">GutConference Online<small>Admin CMS</small></div>
        <nav class="admin-nav">
            <strong>Main</strong>
            <a href="/admin" class="<?= ($_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '') ?>">Dashboard</a>
            <strong>Conference CMS</strong>
            <a href="/admin/events" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/events') ? 'active' : '') ?>">Events</a>
            <a href="/admin/event_sections" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/event_sections') ? 'active' : '') ?>">Page Sections</a>
            <a href="/admin/speakers" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/speakers') ? 'active' : '') ?>">Speakers</a>
            <a href="/admin/sessions" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/sessions') ? 'active' : '') ?>">Agenda</a>
            <a href="/admin/venues" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/venues') ? 'active' : '') ?>">Venues & Maps</a>
            <a href="/admin/registrations" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/registrations') ? 'active' : '') ?>">Registrations</a>
            <strong>Operations</strong>
            <a href="/admin/notification_templates" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/notification_templates') ? 'active' : '') ?>">Notification Templates</a>
            <a href="/admin/notification_queue" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/notification_queue') ? 'active' : '') ?>">Notification Queue</a>
            <a href="/admin/contact-submissions" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/contact-submissions') ? 'active' : '') ?>">Contacts</a>
            <a href="/admin/media" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/media') ? 'active' : '') ?>">Media</a>
            <a href="/admin/integrations" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/integrations') ? 'active' : '') ?>">Integrations</a>
            <a href="/admin/settings" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/settings') ? 'active' : '') ?>">Settings</a>
            <a href="/admin/environment" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/environment') ? 'active' : '') ?>">Environment</a>
            <a href="/admin/audit-log" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/audit-log') ? 'active' : '') ?>">Audit Log</a>
            <a href="/admin/developer/project-map" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/developer/project-map') ? 'active' : '') ?>">Project Map</a>
            <strong>Account</strong>
            <a href="/" target="_blank">View Site</a>
            <a href="/logout">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-top"><h1><?= e($pageTitle ?? 'Dashboard') ?></h1><a class="btn btn-ghost" href="/" target="_blank">View Site</a></div>
        <div class="admin-body">
            <?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
            <?php require $viewFile; ?>
        </div>
    </main>
</div>
</body>
</html>
