<section class="hero event-hero">
    <div class="container hero-grid event-hero-grid">
        <div class="event-hero-copy">
            <?php $eventTime = $eventService->timeRange($event); ?>
            <?php $slotSummary = $eventService->slotSummary($event, $sessions); ?>
            <?php $availability = $eventService->availability($event); ?>
            <?php $countdownDeadline = $eventService->countdownDeadline($event); ?>
            <?php $eventPrice = trim((string)($event['currency'] ?? 'INR') . ' ' . (string)($event['price'] ?? '')); ?>
            <?php $paymentAction = '/events/' . rawurlencode((string)($event['slug'] ?? '')) . '/checkout'; ?>
            <p class="eyebrow"><?= e($event['eyebrow'] ?? 'Microbiome Conference') ?></p>
            <h1><?= e($event['headline'] ?? $event['name']) ?></h1>
            <p class="lede"><?= e($event['subheadline'] ?? '') ?></p>
            <p class="event-organizer"><strong><?= e($event['name'] ?? '') ?></strong> is organized by <?= e($event['organizers'] ?? 'the conference team') ?>.</p>
            <p><?= e($event['description'] ?? '') ?></p>
            
            <div class="hero-actions">
                <?php if(empty($_SESSION['user'])): ?>
                    <a class="link-arrow link-arrow-primary" href="/login">Join Now <span aria-hidden="true">→</span></a>
                <?php else: ?>
                    <form class="inline-payment-form" method="post" action="<?= e($paymentAction) ?>">
                        <button class="link-arrow link-arrow-primary" type="submit">Pay with Razorpay <span aria-hidden="true">→</span></button>
                    </form>
                <?php endif; ?>
                <a class="link-arrow" href="#agenda">View Agenda <span aria-hidden="true">→</span></a>
            </div>
            
            <div class="stats event-stat-grid">
                <div class="stat"><strong><?= e($event['date_label'] ?? '') ?></strong><span>Date</span></div>
                <div class="stat"><strong><?= e($eventTime) ?></strong><span>Time</span></div>
            </div>
            
            <div class="event-urgency" data-countdown-deadline="<?= e($countdownDeadline ?? '') ?>">
                <div class="event-urgency__copy">
                    <span>Hurry, time is ticking</span>
                    <strong data-countdown-label><?= $countdownDeadline ? 'Registration closes at 12:00 AM on ' . e($event['date_label'] ?? 'event day') : 'Registration is time-sensitive' ?></strong>
                </div>
                <div class="event-countdown" aria-label="Countdown to registration deadline">
                    <div><strong data-countdown-days>--</strong><span>Days</span></div>
                    <div><strong data-countdown-hours>--</strong><span>Hours</span></div>
                    <div><strong data-countdown-minutes>--</strong><span>Minutes</span></div>
                </div>
                <div class="event-slots" aria-label="Event seat availability">
                    <span><?= e((string)$availability['filled']) ?> filled</span>
                    <strong><?= e((string)$availability['available']) ?><small>/<?= e((string)$availability['total']) ?></small></strong>
                    <em>available <?= e(strtolower((string)$availability['label'])) ?></em>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(() => {
    const panel = document.querySelector('[data-countdown-deadline]');
    if (!panel) return;
    const deadlineValue = panel.dataset.countdownDeadline || '';
    const deadline = deadlineValue ? new Date(deadlineValue) : null;
    const days = panel.querySelector('[data-countdown-days]');
    const hours = panel.querySelector('[data-countdown-hours]');
    const minutes = panel.querySelector('[data-countdown-minutes]');
    const label = panel.querySelector('[data-countdown-label]');
    const pad = value => String(Math.max(0, value)).padStart(2, '0');
    const tick = () => {
        if (!deadline || Number.isNaN(deadline.getTime())) return;
        const distance = deadline.getTime() - Date.now();
        if (distance <= 0) {
            days.textContent = '00';
            hours.textContent = '00';
            minutes.textContent = '00';
            label.textContent = 'Registration deadline has started for event day';
            return;
        }
        const totalMinutes = Math.floor(distance / 60000);
        days.textContent = pad(Math.floor(totalMinutes / 1440));
        hours.textContent = pad(Math.floor((totalMinutes % 1440) / 60));
        minutes.textContent = pad(totalMinutes % 60);
    };
    tick();
    window.setInterval(tick, 60000);
})();
</script>

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
    <?php if(($section['type'] ?? '') === 'trust') continue; ?>
    <section class="section-tight" <?= ($section['type'] ?? '') === 'outcomes' ? 'id="learn"' : '' ?>>
        <div class="container">
            <p class="eyebrow"><?= e(ucwords(str_replace('_',' ', $section['type'] ?? 'Section'))) ?></p>
            <h2><?= e($section['title'] ?? '') ?></h2>
            <?php if(!empty($section['subtitle'])): ?><p class="lede"><?= e($section['subtitle']) ?></p><?php endif; ?>
            <?php if(!empty($section['body'])): ?><p class="section-body-copy"><?= e($section['body']) ?></p><?php endif; ?>
            <?php if(!empty($section['items']) && is_array($section['items'])): ?>
                <div class="grid">
                    <?php foreach($section['items'] as $item): ?>
                        <div class="card">
                            <p class="section-item-copy"><?= e($item) ?></p>
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
        <?php
        $countryFlag = static function (string $country): string {
            $key = strtolower(trim($country));
            return match ($key) {
                'india' => '🇮🇳',
                'usa', 'united states', 'united states of america' => '🇺🇸',
                'united kingdom', 'uk' => '🇬🇧',
                'australia' => '🇦🇺',
                'italy' => '🇮🇹',
                default => '🌐',
            };
        };
        ?>
        <div class="speaker-carousel section-grid-offset" data-speaker-carousel aria-label="Scrollable speaker lineup">
            <?php foreach($speakers as $speaker): ?>
                <?php
                $speakerName = (string)($speaker['name'] ?? 'Speaker');
                $speakerCountry = (string)($speaker['country'] ?? '');
                $speakerPhoto = (string)($speaker['photo_url'] ?? '');
                $initials = implode('', array_slice(array_map(fn($part) => strtoupper(substr($part, 0, 1)), array_filter(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', '', $speakerName)) ?: [])), 0, 2));
                $initials = $initials !== '' ? $initials : 'GC';
                ?>
                <article
                    class="speaker-card"
                    data-speaker-card
                    data-name="<?= e($speakerName) ?>"
                    data-country="<?= e($speakerCountry) ?>"
                    data-flag="<?= e($countryFlag($speakerCountry)) ?>"
                    data-photo="<?= e($speakerPhoto) ?>"
                    data-initials="<?= e($initials) ?>"
                    data-credentials="<?= e($speaker['credentials'] ?? '') ?>"
                    data-topic="<?= e($speaker['topic'] ?? '') ?>"
                    data-time="<?= e($speaker['session_time'] ?? '') ?>"
                    data-profile="<?= e($speaker['profile'] ?? '') ?>"
                    tabindex="0"
                    role="button"
                    aria-label="View <?= e($speakerName) ?> speaker details"
                >
                    <div class="country"><span class="country-flag" aria-hidden="true"><?= e($countryFlag($speakerCountry)) ?></span><span><?= e($speakerCountry) ?></span></div>
                    <div class="speaker-avatar">
                        <?php if($speakerPhoto !== ''): ?>
                            <img src="<?= e($speakerPhoto) ?>" alt="<?= e($speakerName) ?>">
                        <?php else: ?>
                            <div class="speaker-initials" aria-label="<?= e($speakerName) ?> photo pending"><?= e($initials) ?></div>
                        <?php endif; ?>
                    </div>
                    <h3><?= e($speakerName) ?></h3>
                    <?php if(!empty($speaker['credentials'])): ?><p class="credentials"><?= e($speaker['credentials']) ?></p><?php endif; ?>
                    <p class="topic"><?= e($speaker['topic'] ?? '') ?></p>
                    <p class="time-label">⏰ <?= e($speaker['session_time'] ?? '') ?></p>
                    <p class="profile"><?= e($speaker['profile'] ?? '') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="speaker-modal" data-speaker-modal aria-hidden="true">
    <button class="speaker-modal__backdrop" type="button" data-speaker-close aria-label="Close speaker details"></button>
    <div class="speaker-modal__panel" role="dialog" aria-modal="true" aria-label="Speaker details">
        <button class="speaker-modal__close" type="button" data-speaker-close aria-label="Close speaker details">×</button>
        <div class="speaker-modal__media" data-speaker-modal-media></div>
        <div class="speaker-modal__body">
            <div class="country" data-speaker-modal-country></div>
            <h2 data-speaker-modal-name></h2>
            <p class="credentials" data-speaker-modal-credentials></p>
            <p class="topic" data-speaker-modal-topic></p>
            <p class="time-label" data-speaker-modal-time></p>
            <p class="profile" data-speaker-modal-profile></p>
        </div>
    </div>
</div>

<script>
(() => {
    const modal = document.querySelector('[data-speaker-modal]');
    if (!modal) return;
    const media = modal.querySelector('[data-speaker-modal-media]');
    const country = modal.querySelector('[data-speaker-modal-country]');
    const fields = {
        name: modal.querySelector('[data-speaker-modal-name]'),
        credentials: modal.querySelector('[data-speaker-modal-credentials]'),
        topic: modal.querySelector('[data-speaker-modal-topic]'),
        time: modal.querySelector('[data-speaker-modal-time]'),
        profile: modal.querySelector('[data-speaker-modal-profile]'),
    };
    const open = card => {
        const photo = card.dataset.photo || '';
        media.replaceChildren();
        if (photo) {
            const image = document.createElement('img');
            image.src = photo;
            image.alt = card.dataset.name || '';
            media.appendChild(image);
        } else {
            const initials = document.createElement('div');
            initials.className = 'speaker-initials';
            initials.textContent = card.dataset.initials || 'GC';
            media.appendChild(initials);
        }
        country.replaceChildren();
        const flag = document.createElement('span');
        flag.className = 'country-flag';
        flag.setAttribute('aria-hidden', 'true');
        flag.textContent = card.dataset.flag || '🌐';
        const countryText = document.createElement('span');
        countryText.textContent = card.dataset.country || '';
        country.append(flag, countryText);
        fields.name.textContent = card.dataset.name || '';
        fields.credentials.textContent = card.dataset.credentials || '';
        fields.topic.textContent = card.dataset.topic || '';
        fields.time.textContent = card.dataset.time ? `⏰ ${card.dataset.time}` : '';
        fields.profile.textContent = card.dataset.profile || '';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('speaker-modal-open');
    };
    const close = () => {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('speaker-modal-open');
    };
    document.querySelectorAll('[data-speaker-card]').forEach(card => {
        card.addEventListener('click', () => open(card));
        card.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                open(card);
            }
        });
    });
    modal.querySelectorAll('[data-speaker-close]').forEach(button => button.addEventListener('click', close));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') close();
    });
})();
</script>

<section class="section" id="agenda">
    <div class="container">
        <p class="eyebrow">Agenda</p>
        <h2>Conference Schedule</h2>
        <div class="timeline section-grid-offset">
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
                            <p class="timeline-description"><?= e($session['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-tight" id="venue">
    <div class="container grid-2">
        <div class="card venue-card">
            <div>
                <p class="eyebrow">Venue</p>
                <h2><?= e($venue['name'] ?? 'Online Conference') ?></h2>
                <p class="section-body-copy"><?= e($venue['joining_note'] ?? 'Joining details will be shared after registration.') ?></p>
                <?php if(!empty($venue['address'])): ?>
                    <p class="venue-address">📍 <?= e($venue['address']) ?></p>
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
                        <strong><?= e($eventPrice) ?></strong>
                        <small>Live online access</small>
                    </div>
                </div>
                <p class="form-helper">Login with Google before joining the conference room. Checkout opens the Razorpay payment flow for this event.</p>
                <a class="link-arrow link-arrow-primary" href="/login">Join In this Conference Room <span aria-hidden="true">→</span></a>
            <?php else: ?>
                <p class="form-helper">Secure checkout is available. Standard registration fee covers full session access, live Q&amp;A, and your verified E-certificate.</p>
                <form class="inline-payment-form" method="post" action="<?= e($paymentAction) ?>">
                    <button class="link-arrow link-arrow-primary" type="submit">Pay with Razorpay <span aria-hidden="true">→</span></button>
                </form>
            <?php endif; ?>
            
            <p class="form-helper registration-contact">Questions: <?= e($event['contact_email'] ?? '') ?> · <?= e($event['contact_phone'] ?? '') ?></p>
        </div>
    </div>
</section>

<?php $faqs = array_values(array_filter($sections, fn($section) => ($section['type'] ?? '') === 'faq')); ?>
<?php if($faqs): ?>
<section class="section" id="faq">
    <div class="container">
        <p class="eyebrow">FAQ</p>
        <h2><?= e($faqs[0]['title'] ?? 'Frequently Asked Questions') ?></h2>
        <div class="faq-grid section-grid-offset">
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
        <a class="link-arrow link-arrow-primary" href="<?= empty($_SESSION['user']) ? '/login' : '#registration' ?>"><?= empty($_SESSION['user']) ? e($eventPrice . ' Join Now') : 'Buy Ticket' ?> <span aria-hidden="true">→</span></a>
    </div>
</div>
<?php endif; ?>

<section class="section cta-band">
    <div class="container grid-2 cta-grid">
        <div>
            <h2>Ready to attend?</h2>
            <p class="lede">Get practical, evidence-based training and clinical protocols directly from international experts.</p>
        </div>
        <div class="cta-actions">
            <a class="google-login-button" href="<?= empty($_SESSION['user']) ? '/auth/google?intent=event&redirect=' . rawurlencode('/events/' . ($event['slug'] ?? 'global-gut-summit-2026')) : '#registration' ?>">
                <span class="google-mark" aria-hidden="true">G</span>
                <span><?= empty($_SESSION['user']) ? 'Login with Google' : 'Buy Ticket' ?></span>
            </a>
        </div>
    </div>
</section>
