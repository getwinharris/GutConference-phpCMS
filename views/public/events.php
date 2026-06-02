<section class="section events-index">
    <div class="container">
        <p class="eyebrow">GutConference Events</p>
        <div class="events-heading">
            <div>
                <h1>Clinical Conferences Managed by GutConference</h1>
                <p class="lede">Browse admin-created conferences with verified event details, real agenda timing, session slots, speaker information, and registration links.</p>
            </div>
            <a class="link-arrow" href="/contact">Conference support <span aria-hidden="true">→</span></a>
        </div>

        <div class="event-card-grid">
            <?php foreach($events as $event): ?>
                <?php
                    $slug = (string)($event['slug'] ?? '');
                    $thumb = $event['thumbnail_url'] ?? $event['hero_image_url'] ?? $event['logo_url'] ?? '/assets/images/media/gutconference-logo.jpg';
                    $time = $eventService->timeRange($event);
                    $sessions = $eventService->sessions($slug);
                    $slotSummary = $eventService->shortSlotSummary($event, $sessions);
                ?>
                <article class="event-card">
                    <a class="event-card__media" href="/events/<?= e($slug) ?>" aria-label="<?= e($event['name'] ?? 'Event') ?>">
                        <img src="<?= e($thumb) ?>" alt="<?= e($event['name'] ?? 'Conference thumbnail') ?>">
                    </a>
                    <div class="event-card__body">
                        <div class="event-card__meta">
                            <span><?= e($event['mode'] ?? 'Conference') ?></span>
                            <span><?= count($sessions) ?> agenda slots</span>
                        </div>
                        <h2><?= e($event['name']) ?></h2>
                        <p><?= e($event['subheadline'] ?? $event['description'] ?? '') ?></p>
                        <div class="event-card__facts">
                            <span><strong><?= e($event['date_label'] ?? '') ?></strong>Date</span>
                            <span><strong><?= e($time) ?></strong>Time</span>
                            <span><strong><?= e($slotSummary) ?></strong>Slots</span>
                        </div>
                        <a class="link-arrow link-arrow-primary" href="/events/<?= e($slug) ?>"><?= e($event['cta_label'] ?? 'View Event') ?> <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
