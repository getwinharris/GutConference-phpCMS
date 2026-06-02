<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow"><?= e($event['eyebrow'] ?? 'Conference') ?></p>
            <h1><?= e($event['headline'] ?? $event['name']) ?></h1>
            <p class="lede"><?= e($event['subheadline'] ?? '') ?></p>
            <p><strong><?= e($event['name'] ?? '') ?></strong> is organized by <?= e($event['organizers'] ?? 'the conference team') ?>.</p>
            <p><?= e($event['description'] ?? '') ?></p>
            <div class="hero-actions">
                <?php if(!empty($event['registration_url'])): ?>
                    <a class="btn btn-primary" href="<?= e($event['registration_url']) ?>" target="_blank" rel="noopener"><?= e($event['cta_label'] ?? 'Register') ?></a>
                <?php else: ?>
                    <a class="btn btn-primary" href="#registration"><?= e($event['cta_label'] ?? 'Register') ?></a>
                <?php endif; ?>
                <a class="btn btn-outline" href="#agenda">View Agenda</a>
            </div>
            <div class="stats">
                <div class="stat"><strong><?= e($event['date_label'] ?? '') ?></strong><span>Date</span></div>
                <div class="stat"><strong><?= e($event['time_label'] ?? '') ?></strong><span>Time</span></div>
                <div class="stat"><strong><?= e($event['mode'] ?? '') ?></strong><span>Mode</span></div>
                <div class="stat"><strong>Rs.<?= e((string)($event['price'] ?? 0)) ?></strong><span>Fee</span></div>
            </div>
            <div class="trust-strip">
                <div class="trust-item">E-certificate included</div>
                <div class="trust-item">International speakers</div>
                <div class="trust-item">Clinical application focus</div>
                <div class="trust-item">Rs.<?= e((string)($event['price'] ?? 0)) ?> registration</div>
                <div class="trust-item"><?= e($event['organizers'] ?? 'Organized event') ?></div>
            </div>
        </div>
        <div class="hero-media"><img src="<?= e($event['logo_url'] ?? '/assets/images/media/gutconference-logo.png') ?>" alt="<?= e($event['name']) ?>"></div>
    </div>
</section>

<nav class="section-nav" aria-label="Event sections">
    <div class="container">
        <a href="#learn">What You Learn</a>
        <a href="#speakers">Speakers</a>
        <a href="#agenda">Agenda</a>
        <a href="#venue">Venue</a>
        <a href="#faq">FAQ</a>
        <a href="#registration">Register</a>
    </div>
</nav>

<?php foreach($sections as $section): ?>
    <?php if(($section['type'] ?? '') === 'faq') continue; ?>
    <section class="section-tight" <?= ($section['type'] ?? '') === 'outcomes' ? 'id="learn"' : '' ?>>
        <div class="container">
            <p class="eyebrow"><?= e(ucwords(str_replace('_',' ', $section['type'] ?? 'Section'))) ?></p>
            <h2><?= e($section['title'] ?? '') ?></h2>
            <?php if(!empty($section['subtitle'])): ?><p class="lede"><?= e($section['subtitle']) ?></p><?php endif; ?>
            <?php if(!empty($section['body'])): ?><p><?= e($section['body']) ?></p><?php endif; ?>
            <?php if(!empty($section['items']) && is_array($section['items'])): ?>
                <div class="grid" style="margin-top:18px">
                    <?php foreach($section['items'] as $item): ?><div class="card"><?= e($item) ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach; ?>

<section class="section" id="speakers">
    <div class="container">
        <p class="eyebrow">International Experts</p>
        <h2>Speaker Lineup</h2>
        <div class="speaker-grid">
            <?php foreach($speakers as $speaker): ?>
                <article class="speaker-card">
                    <div class="country"><?= e($speaker['country'] ?? '') ?></div>
                    <h3><?= e($speaker['name'] ?? '') ?></h3>
                    <p><strong><?= e($speaker['topic'] ?? '') ?></strong></p>
                    <p><?= e($speaker['session_time'] ?? '') ?></p>
                    <p><?= e($speaker['profile'] ?? '') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="agenda">
    <div class="container">
        <p class="eyebrow">Agenda</p>
        <h2>Conference Schedule</h2>
        <div class="timeline">
            <?php foreach($sessions as $session): ?>
                <div class="timeline-row">
                    <div class="time"><?= e($session['time_label'] ?? '') ?></div>
                    <div><strong><?= e($session['title'] ?? '') ?></strong><?php if(!empty($session['speaker_name'])): ?><br><span><?= e($session['speaker_name']) ?></span><?php endif; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-tight" id="venue">
    <div class="container grid-2">
        <div class="card">
            <p class="eyebrow">Venue</p>
            <h2><?= e($venue['name'] ?? 'Online Conference') ?></h2>
            <p><?= e($venue['joining_note'] ?? 'Joining details will be shared after registration.') ?></p>
            <?php if(!empty($venue['map_link'])): ?><a class="btn btn-outline" href="<?= e($venue['map_link']) ?>" target="_blank" rel="noopener">Open Map</a><?php endif; ?>
        </div>
        <div class="card" id="registration">
            <p class="eyebrow">Registration</p>
            <h2>Reserve Your Seat</h2>
            <p>Registration fee: <strong>Rs.<?= e((string)($event['price'] ?? 0)) ?></strong></p>
            <?php if(!empty($event['registration_url'])): ?>
                <a class="btn btn-primary" href="<?= e($event['registration_url']) ?>" target="_blank" rel="noopener"><?= e($event['cta_label'] ?? 'Register Now') ?></a>
            <?php else: ?>
                <p class="lede">Payment link or internal Razorpay checkout can be configured from admin when live payment details are ready. Contact the team for registration support.</p>
                <a class="btn btn-primary" href="/contact?subject=Registration%20for%20<?= e($event['slug']) ?>">Contact to Register</a>
            <?php endif; ?>
            <p style="margin-top:14px;color:var(--muted)">Questions: <?= e($event['contact_email'] ?? '') ?> · <?= e($event['contact_phone'] ?? '') ?></p>
        </div>
    </div>
</section>

<?php $faqs = array_values(array_filter($sections, fn($section) => ($section['type'] ?? '') === 'faq')); ?>
<?php if($faqs): ?>
<section class="section" id="faq">
    <div class="container">
        <p class="eyebrow">FAQ</p>
        <h2><?= e($faqs[0]['title'] ?? 'Frequently Asked Questions') ?></h2>
        <div class="grid-2">
            <?php foreach(($faqs[0]['items'] ?? []) as $item): ?><div class="card"><?= e($item) ?></div><?php endforeach; ?>
        </div>
    </div>
</section>

<div class="sticky-register">
    <div class="container">
        <div><strong><?= e($event['name'] ?? 'Conference') ?></strong><span><?= e($event['date_label'] ?? '') ?> · <?= e($event['time_label'] ?? '') ?> · Rs.<?= e((string)($event['price'] ?? 0)) ?></span></div>
        <a class="btn btn-primary" href="#registration"><?= e($event['cta_label'] ?? 'Register') ?></a>
    </div>
</div>
<?php endif; ?>

<section class="section cta-band">
    <div class="container grid-2" style="align-items:center">
        <div><h2>Ready to attend?</h2><p class="lede">A focused clinical conference page with clear registration beats a generic event listing.</p></div>
        <div style="text-align:right"><a class="btn btn-primary" href="#registration"><?= e($event['cta_label'] ?? 'Register') ?></a></div>
    </div>
</section>
