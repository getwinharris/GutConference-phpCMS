<section class="section">
    <div class="container">
        <p class="eyebrow">Events</p>
        <h1>Published Conferences</h1>
        <p class="lede">Every page here is created and controlled by the admin CMS.</p>
        <div class="grid" style="margin-top:28px">
            <?php foreach($events as $event): ?>
                <article class="card">
                    <span class="pill"><?= e($event['mode'] ?? 'Conference') ?></span>
                    <h3 style="margin-top:16px"><?= e($event['name']) ?></h3>
                    <p><?= e($event['subheadline'] ?? $event['description'] ?? '') ?></p>
                    <p><strong><?= e($event['date_label'] ?? '') ?></strong><br><?= e($event['time_label'] ?? '') ?></p>
                    <a class="btn btn-primary" href="/events/<?= e($event['slug']) ?>"><?= e($event['cta_label'] ?? 'View Event') ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
