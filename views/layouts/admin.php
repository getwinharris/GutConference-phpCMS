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
.admin-top-actions{display:flex;align-items:center;gap:10px}.admin-avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;object-position:50% 24%;border:1px solid var(--line);box-shadow:0 10px 22px rgba(7,58,102,.12);background:#fff;padding:2px}.support-launcher{border:0;border-radius:999px;width:44px;height:44px;color:var(--blue);background:#fff;border:1px solid transparent;background-image:linear-gradient(#fff,#fff),linear-gradient(135deg,#7b4dff,#dd3df2 45%,#087fc0);background-origin:border-box;background-clip:padding-box,border-box;box-shadow:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center}.gemini-mark{position:relative;display:block;width:22px;height:22px}.gemini-mark:before,.gemini-mark:after{content:"";position:absolute;inset:3px;background:linear-gradient(135deg,#7b4dff,#dd3df2 52%,#087fc0);clip-path:polygon(50% 0,62% 36%,100% 50%,62% 64%,50% 100%,38% 64%,0 50%,38% 36%)}.gemini-mark:after{inset:7px;background:#fff;opacity:.95}.support-widget{position:fixed;inset:0 0 auto auto;z-index:40;pointer-events:none}.support-panel{position:fixed;top:64px;right:0;bottom:0;width:min(430px,calc(100vw - 32px));display:grid;overflow:hidden;background:rgba(255,255,255,.98);border:1px solid var(--line);border-right:0;border-radius:8px 0 0 0;box-shadow:none;opacity:0;pointer-events:none;transform:translateX(100%);transition:opacity .2s ease,transform .2s ease}.support-widget.is-open .support-panel{opacity:1;pointer-events:auto;transform:translateX(0)}.support-chat{min-height:0;display:grid;grid-template-rows:auto 1fr auto;background:#fff}.support-chat__head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 18px;border-bottom:1px solid var(--line)}.support-chat__head strong{display:block;color:#073a66}.support-chat__head span{color:var(--muted);font-size:12px;font-weight:800}.support-chat__head button{border:0;background:#eef6f6;color:#073a66;border-radius:8px;width:36px;height:36px;font-size:24px;cursor:pointer;line-height:1}.support-messages{overflow:auto;padding:18px;display:flex;flex-direction:column;gap:10px}.support-message{max-width:88%;border-radius:8px;padding:10px 12px;color:var(--ink);white-space:pre-wrap}.support-message--agent{align-self:flex-start;background:#eef8ff;border:1px solid #d6eafa}.support-message--user{align-self:flex-end;background:#073a66;color:#fff}.support-actions{display:flex;flex-wrap:wrap;gap:8px;max-width:88%}.support-actions a{display:inline-flex;align-items:center;min-height:34px;border-radius:999px;padding:7px 11px;color:#073a66;background:#fff;border:1px solid var(--line);font-size:12px;font-weight:800}.support-form{border-top:1px solid var(--line);padding:12px;display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end}.support-form textarea{min-height:52px;max-height:120px;resize:vertical;text-transform:none}.support-send{width:44px;height:44px;display:inline-grid;place-items:center;border-radius:50%;border:0;background:linear-gradient(135deg,var(--green),var(--teal),var(--blue));color:#fff;font-size:20px;font-weight:900;cursor:pointer}.support-widget.is-thinking .support-send{background:#073a66;font-size:14px}
.badge{display:inline-flex;border-radius:999px;padding:5px 9px;background:#edf7f5;color:var(--blue);font-size:12px;font-weight:800}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid var(--line);padding:10px;text-align:left;font-size:13px;vertical-align:top}th{color:var(--muted);text-transform:uppercase;font-size:11px}.flash{padding:12px 14px;background:#eaf8f2;border:1px solid #bee8d4;border-radius:8px;margin-bottom:18px}.admin-upload-panel{background:#f6fbfb;border:1px dashed var(--line);border-radius:8px;padding:14px;margin-top:16px}.admin-media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px}.admin-media-tile{border:1px solid var(--line);border-radius:8px;padding:8px;background:#fff}.admin-media-tile img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:6px}.admin-image-preview{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}.admin-image-preview img{width:76px;height:76px;object-fit:cover;border-radius:6px;border:1px solid var(--line)}.admin-table-thumb{width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--line);display:block}.brand-asset-grid,.brand-token-grid,.brand-type-grid,.brand-guideline-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}.brand-asset,.brand-token,.brand-type-grid>div,.brand-guideline-grid>div{border:1px solid var(--line);border-radius:8px;background:#fff;padding:14px}.brand-asset img{height:90px;object-fit:contain;margin-bottom:12px}.brand-asset strong,.brand-token strong,.brand-guideline-grid strong{display:block;color:var(--ink);margin-bottom:4px}.brand-asset code,.brand-token code{color:var(--muted);font-size:12px;overflow-wrap:anywhere}.brand-token span{display:block;height:56px;border-radius:8px;border:1px solid var(--line);margin-bottom:10px}.brand-type-grid span{display:block;color:var(--muted);font-size:11px;font-weight:800;text-transform:uppercase;margin-bottom:8px}.brand-guideline-grid p{color:var(--muted);margin:0}
@media(min-width:841px){body.support-open .admin-shell{margin-right:430px}}
@media(max-width:840px){.admin-shell{grid-template-columns:1fr}.admin-sidebar{position:relative;height:auto}.admin-body{padding:14px}.support-panel{inset:0;width:100vw;height:100vh;border-radius:0}.support-form{grid-template-columns:1fr}}
</style>
<style>
.support-message--working{display:inline-flex;align-items:center;gap:10px;width:fit-content;color:#073a66;font-weight:800}.agent-working-copy{color:var(--muted);font-size:12px;letter-spacing:.02em}.agent-orbit{position:relative;display:inline-grid;place-items:center;width:30px;height:30px;border-radius:50%;background:radial-gradient(circle,#fff 0 38%,rgba(249,115,22,.10) 39% 100%);border:1px solid rgba(249,115,22,.32)}.agent-orbit:before,.agent-orbit:after,.agent-orbit i{content:"";position:absolute;border-radius:50%}.agent-orbit:before{inset:4px;border:2px solid rgba(7,58,102,.16);border-top-color:#f97316;animation:agent-spin 1.1s linear infinite}.agent-orbit:after{width:6px;height:6px;background:var(--teal);box-shadow:0 0 16px rgba(57,185,168,.7);transform:translateY(-10px);animation:agent-dot 1.1s linear infinite}.agent-orbit i{width:8px;height:8px;background:#073a66;opacity:.9}.agent-orbit--button{width:24px;height:24px;background:transparent;border-color:rgba(255,255,255,.48)}.agent-orbit--button i{background:#fff}.agent-orbit--button:before{border-color:rgba(255,255,255,.26);border-top-color:#fff}.agent-orbit--button:after{background:#fb923c;box-shadow:0 0 12px rgba(251,146,60,.8);transform:translateY(-8px)}.support-widget.is-thinking .gemini-mark:before{animation:agent-spin 1.4s linear infinite}@keyframes agent-spin{to{transform:rotate(360deg)}}@keyframes agent-dot{to{transform:rotate(360deg) translateY(-10px)}}@media(prefers-reduced-motion:reduce){.support-widget.is-thinking .gemini-mark:before,.agent-orbit:before,.agent-orbit:after{animation:none}}
</style>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-brand">GutConference Online<small>Admin CMS</small></div>
        <nav class="admin-nav">
            <strong>Main</strong>
            <a href="/admin" class="<?= ($_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '') ?>">Dashboard</a>
            <strong>Event Management</strong>
            <a href="/admin/events" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/events') ? 'active' : '') ?>">Events &amp; Classes</a>
            <a href="/admin/event_sections" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/event_sections') ? 'active' : '') ?>">Page Sections</a>
            <a href="/admin/speakers" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/speakers') ? 'active' : '') ?>">Speakers</a>
            <a href="/admin/sessions" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/sessions') ? 'active' : '') ?>">Agenda</a>
            <a href="/admin/venues" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/venues') ? 'active' : '') ?>">Venues & Maps</a>
            <a href="/admin/registrations" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/registrations') ? 'active' : '') ?>">Registrations</a>
            <strong>Operations</strong>
            <a href="/admin/notification_templates" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/notification_templates') ? 'active' : '') ?>">Notification Templates</a>
            <a href="/admin/notification_queue" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/notification_queue') ? 'active' : '') ?>">Notification Queue</a>
            <a href="/admin/contact-submissions" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/contact-submissions') ? 'active' : '') ?>">Contacts</a>
            <a href="/admin/support-tickets" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/support-tickets') ? 'active' : '') ?>">Support Agent</a>
            <a href="/admin/branding" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/branding') ? 'active' : '') ?>">Branding</a>
            <a href="/admin/publishers" class="<?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/publishers') ? 'active' : '') ?>">Publishers</a>
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
        <div class="admin-top"><h1><?= e($pageTitle ?? 'Dashboard') ?></h1><div class="admin-top-actions"><img class="admin-avatar" src="/assets/images/media/speakers/owner-dr-praveen-jacob-also-speaker.jpeg" alt="Dr. Praveen Jacob profile photo"><a class="btn btn-ghost" href="/" target="_blank">View Site</a><button class="support-launcher" type="button" data-support-open aria-label="Open Gemini support agent"><span class="gemini-mark" aria-hidden="true"></span></button></div></div>
        <div class="admin-body">
            <?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
            <?php require $viewFile; ?>
        </div>
    </main>
</div>
<?php $supportPlaceholder = 'Ask about events, speakers, tickets, certificates, or improvements'; require app_path('views/partials/support-widget.php'); ?>
</body>
</html>
