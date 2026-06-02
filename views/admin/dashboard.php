<div class="admin-card">
    <h2>Conference Operations</h2>
    <p style="color:var(--muted)">Manage brand-owned conference landing pages, not public event submissions.</p>
    <div class="admin-form__row">
        <div class="admin-card"><strong style="font-size:30px"><?= (int)($eventCount ?? 0) ?></strong><br><span>Events</span></div>
        <div class="admin-card"><strong style="font-size:30px"><?= (int)($speakerCount ?? 0) ?></strong><br><span>Speakers</span></div>
        <div class="admin-card"><strong style="font-size:30px"><?= (int)($registrationCount ?? 0) ?></strong><br><span>Registrations</span></div>
    </div>
</div>
<div class="admin-card">
    <h2>Quick Actions</h2>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn btn-primary" href="/admin/events">Edit Events</a>
        <a class="btn btn-ghost" href="/admin/event_sections">Page Sections</a>
        <a class="btn btn-ghost" href="/admin/speakers">Speakers</a>
        <a class="btn btn-ghost" href="/admin/sessions">Agenda</a>
        <a class="btn btn-ghost" href="/admin/venues">Venues & Maps</a>
        <a class="btn btn-ghost" href="/admin/integrations">Payments & Meta API</a>
    </div>
</div>
