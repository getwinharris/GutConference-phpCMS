<section class="hero">
    <div class="container hero-grid">
        <div>
            <?php $eventTime = $eventService->timeRange($event); ?>
            <?php $slotSummary = $eventService->slotSummary($event, $sessions); ?>
            <p class="eyebrow"><?= e($event['eyebrow'] ?? 'Microbiome Conference') ?></p>
            <h1><?= e($event['headline'] ?? $event['name']) ?></h1>
            <p class="lede"><?= e($event['subheadline'] ?? '') ?></p>
            <p style="margin-bottom: 20px;"><strong><?= e($event['name'] ?? '') ?></strong> is organized by <?= e($event['organizers'] ?? 'the conference team') ?>.</p>
            <p><?= e($event['description'] ?? '') ?></p>
            
            <div class="hero-actions">
                <?php if(empty($_SESSION['user'])): ?>
                    <a class="link-arrow link-arrow-primary" href="/login">Join Now <span aria-hidden="true">→</span></a>
                <?php elseif(!empty($event['registration_url'])): ?>
                    <a class="link-arrow link-arrow-primary" href="<?= e($event['registration_url']) ?>" target="_blank" rel="noopener">Pay with Razorpay <span aria-hidden="true">→</span></a>
                <?php else: ?>
                    <a class="link-arrow link-arrow-primary" href="#registration">Request Payment Link <span aria-hidden="true">→</span></a>
                <?php endif; ?>
                <a class="link-arrow" href="#agenda">View Agenda <span aria-hidden="true">→</span></a>
            </div>
            
            <div class="stats">
                <div class="stat"><strong><?= e($event['date_label'] ?? '') ?></strong><span>Date</span></div>
                <div class="stat"><strong><?= e($eventTime) ?></strong><span>Time</span></div>
                <div class="stat"><strong><?= e($event['mode'] ?? '') ?></strong><span>Mode</span></div>
            </div>
            
            <div class="trust-strip">
                <div class="trust-item">E-certificate included</div>
                <div class="trust-item">International speakers</div>
                <div class="trust-item">Clinical application focus</div>
            </div>
        </div>
        <div class="hero-media">
            <img class="event-thumbnail" src="<?= e($event['thumbnail_url'] ?? $event['hero_image_url'] ?? '/assets/images/media/gutconference-logo.jpg') ?>" alt="<?= e($event['name'] ?? 'Conference thumbnail') ?>">
            <div class="brand-hero">
                <img src="/assets/images/media/gutconference-mark.png" alt="GutConference circular mark">
                <div class="brand-hero-title">
                    <small>International Conference On</small>
                    <strong>Microbiome</strong>
                    <strong>Probiotics</strong>
                    <strong>Gut Nutrition</strong>
                    <span>Bridging Science &amp; Clinical Healing</span>
                </div>
            </div>
        </div>
    </div>
</section>

<nav class="section-nav" aria-label="Event sections">
    <div class="container">
        <a href="#learn">What You Learn</a>
        <a href="#speakers">Speakers</a>
        <a href="#agenda">Agenda</a>
        <a href="#venue">Venue</a>
        <a href="#faq">FAQ</a>
        <a href="#registration">Ticket</a>
    </div>
</nav>

<?php foreach($sections as $section): ?>
    <?php if(($section['type'] ?? '') === 'faq') continue; ?>
    <section class="section-tight" <?= ($section['type'] ?? '') === 'outcomes' ? 'id="learn"' : '' ?>>
        <div class="container">
            <p class="eyebrow"><?= e(ucwords(str_replace('_',' ', $section['type'] ?? 'Section'))) ?></p>
            <h2><?= e($section['title'] ?? '') ?></h2>
            <?php if(!empty($section['subtitle'])): ?><p class="lede"><?= e($section['subtitle']) ?></p><?php endif; ?>
            <?php if(!empty($section['body'])): ?><p style="margin-bottom: 24px; color: var(--muted);"><?= e($section['body']) ?></p><?php endif; ?>
            <?php if(!empty($section['items']) && is_array($section['items'])): ?>
                <div class="grid">
                    <?php foreach($section['items'] as $item): ?>
                        <div class="card">
                            <p style="font-weight: 500; font-size: 15px;"><?= e($item) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach; ?>

<section class="section" id="speakers">
    <div class="container">
        <p class="eyebrow">International Experts</p>
        <h2>Speaker Lineup</h2>
        <div class="speaker-grid" style="margin-top: 30px;">
            <?php foreach($speakers as $speaker): ?>
                <article class="speaker-card">
                    <div class="country"><?= e($speaker['country'] ?? '') ?></div>
                    <div style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; border: 2px solid var(--teal);">
                        <img src="<?= e($speaker['photo_url'] ?? '/assets/images/media/gutconference-logo.png') ?>" alt="<?= e($speaker['name'] ?? 'Speaker') ?>" style="width:100%; height:100%; object-fit:cover; border-radius:0;">
                    </div>
                    <h3><?= e($speaker['name'] ?? '') ?></h3>
                    <p class="topic"><?= e($speaker['topic'] ?? '') ?></p>
                    <p class="time-label">⏰ <?= e($speaker['session_time'] ?? '') ?></p>
                    <p class="profile"><?= e($speaker['profile'] ?? '') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="agenda">
    <div class="container">
        <p class="eyebrow">Agenda</p>
        <h2>Conference Schedule</h2>
        <div class="timeline" style="margin-top: 30px;">
            <?php foreach($sessions as $index => $session): ?>
                <div class="timeline-row">
                    <div class="time"><?= e($session['time_label'] ?? '') ?></div>
                    <div>
                        <strong><?= e($session['title'] ?? '') ?></strong>
                        <?php if(!empty($session['speaker_name'])): ?>
                            <span>🎙️ <?= e($session['speaker_name']) ?></span>
                        <?php endif; ?>
                        <span class="slot-chip">Slot <?= e((string)($index + 1)) ?></span>
                        <?php if(!empty($session['description'])): ?>
                            <p style="font-size: 13px; color: var(--muted); margin-top: 6px;"><?= e($session['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-tight" id="venue">
    <div class="container grid-2">
        <div class="card" style="display:flex; flex-direction:column; justify-content:space-between;">
            <div>
                <p class="eyebrow">Venue</p>
                <h2><?= e($venue['name'] ?? 'Online Conference') ?></h2>
                <p style="color: var(--muted); margin-bottom: 20px;"><?= e($venue['joining_note'] ?? 'Joining details will be shared after registration.') ?></p>
                <?php if(!empty($venue['address'])): ?>
                    <p style="font-size: 14px; color: var(--muted); margin-bottom: 20px;">📍 <?= e($venue['address']) ?></p>
                <?php endif; ?>
            </div>
            <?php if(!empty($venue['map_link'])): ?>
                <div>
                    <a class="btn btn-outline" href="<?= e($venue['map_link']) ?>" target="_blank" rel="noopener">Open Map Location</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card" id="registration">
            <p class="eyebrow">Registration</p>
            <h2>Reserve Your Seat</h2>
            
            <?php if(empty($_SESSION['user'])): ?>
                <div class="pricing-panel single-price">
                    <div>
                        <span>Conference Pass</span>
                        <strong><?= e($event['currency'] ?? 'INR') ?> <?= e((string)($event['price'] ?? 999)) ?></strong>
                        <small>Live online access</small>
                    </div>
                </div>
                <p style="color: var(--muted); font-size: 14px; margin-bottom: 20px;">Login with Google before joining the conference room. Checkout opens the Razorpay payment flow for this event.</p>
                <a class="link-arrow link-arrow-primary" href="/login">Join In this Conference Room <span aria-hidden="true">→</span></a>
            <?php elseif(!empty($event['registration_url'])): ?>
                <a class="link-arrow link-arrow-primary" href="<?= e($event['registration_url']) ?>" target="_blank" rel="noopener">Pay with Razorpay <span aria-hidden="true">→</span></a>
            <?php else: ?>
                <p style="color: var(--muted); font-size: 14px; margin-bottom: 20px;">Secure checkout is available. Standard registration fee covers full session access, live Q&amp;A, and your verified E-certificate.</p>
                <a class="link-arrow link-arrow-primary" href="/contact?subject=Event%20Booking%20for%20<?= e($event['slug']) ?>">Request Payment Link <span aria-hidden="true">→</span></a>
            <?php endif; ?>
            
            <p style="margin-top: 18px; font-size: 12px; color: var(--muted);">Questions: <?= e($event['contact_email'] ?? '') ?> · <?= e($event['contact_phone'] ?? '') ?></p>
        </div>
    </div>
</section>

<?php $faqs = array_values(array_filter($sections, fn($section) => ($section['type'] ?? '') === 'faq')); ?>
<?php if($faqs): ?>
<section class="section" id="faq">
    <div class="container">
        <p class="eyebrow">FAQ</p>
        <h2><?= e($faqs[0]['title'] ?? 'Frequently Asked Questions') ?></h2>
        <div class="faq-grid" style="margin-top: 30px;">
            <?php foreach(($faqs[0]['items'] ?? []) as $item): ?>
                <?php 
                $parts = explode('?', $item, 2);
                $q = trim($parts[0] . '?');
                $a = trim($parts[1] ?? '');
                ?>
                <div class="faq-card">
                    <strong><?= e($q) ?></strong>
                    <?php if($a): ?><p><?= e($a) ?></p><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="sticky-register">
    <div class="container">
        <div>
            <strong><?= e($event['name'] ?? 'Conference') ?></strong>
            <span><?= e($event['date_label'] ?? '') ?> · <?= e($eventTime) ?> · <?= e($slotSummary) ?></span>
        </div>
        <a class="link-arrow link-arrow-primary" href="<?= empty($_SESSION['user']) ? '/login' : '#registration' ?>"><?= empty($_SESSION['user']) ? 'Rs 999/- Join Now' : 'Buy Ticket' ?> <span aria-hidden="true">→</span></a>
    </div>
</div>
<?php endif; ?>

<section class="section cta-band">
    <div class="container grid-2" style="align-items:center">
        <div>
            <h2>Ready to attend?</h2>
            <p class="lede">Get practical, evidence-based training and clinical protocols directly from international experts.</p>
        </div>
        <div style="text-align:right">
            <a class="google-login-button" href="<?= empty($_SESSION['user']) ? '/auth/google?intent=event&redirect=' . rawurlencode('/events/' . ($event['slug'] ?? 'global-gut-summit-2026')) : '#registration' ?>">
                <span class="google-mark" aria-hidden="true">G</span>
                <span><?= empty($_SESSION['user']) ? 'Login with Google' : 'Buy Ticket' ?></span>
            </a>
        </div>
    </div>
</section>
