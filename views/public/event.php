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
            <div class="event-organizer-logos" aria-label="Organized by Alpha Natural and Salus Nutri">
                <span>Organized by</span>
                <div class="event-organizer-logos__row">
                    <div class="collab-logo collab-logo--alpha" aria-label="Alpha Natural">
                        <img src="/assets/images/media/brands/alpha-natural-logo.png" alt="Alpha Natural logo">
                    </div>
                    <div class="collab-logo collab-logo--salus" aria-label="Salus Nutri">
                        <img src="/assets/images/media/brands/salus-nutri-logo.svg" alt="Salus Nutri logo">
                    </div>
                </div>
            </div>

            <div class="event-urgency" data-countdown-deadline="<?= e($countdownDeadline ?? '') ?>">
                <div class="event-countdown" aria-label="Countdown to registration deadline">
                    <div><strong data-countdown-days>--</strong><span>Days</span></div>
                    <div><strong data-countdown-hours>--</strong><span>Hours</span></div>
                    <div><strong data-countdown-minutes>--</strong><span>Minutes</span></div>
                    <div><strong data-countdown-seconds>--</strong><span>Seconds</span></div>
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
    const initCountdowns = () => {
        const panels = [...document.querySelectorAll('[data-countdown-deadline]')];
        if (!panels.length) return;
        const pad = value => String(Math.max(0, value)).padStart(2, '0');
        const prepareFlipNode = node => {
            if (!node || !node.closest('.sticky-register__timer')) return null;
            let valueNode = node.querySelector('.flip-value');
            let flapNode = node.querySelector('.flip-flap');
            if (!valueNode || !flapNode) {
                const current = node.textContent.trim() || '--';
                node.replaceChildren();
                valueNode = document.createElement('span');
                valueNode.className = 'flip-value';
                valueNode.textContent = current;
                flapNode = document.createElement('span');
                flapNode.className = 'flip-flap';
                flapNode.setAttribute('aria-hidden', 'true');
                flapNode.dataset.flipValue = current;
                node.append(valueNode, flapNode);
                node.dataset.countdownValue = current;
            }
            return { valueNode, flapNode };
        };
        const setCountdownValue = (node, value) => {
            if (!node) return;
            const flipParts = prepareFlipNode(node);
            const current = flipParts ? (node.dataset.countdownValue || flipParts.valueNode.textContent) : node.textContent;
            if (current === value) return;
            const initialized = node.dataset.countdownReady === 'true';
            node.dataset.countdownReady = 'true';
            node.dataset.countdownValue = value;
            node.setAttribute('aria-label', value);
            if (!flipParts) {
                node.textContent = value;
                return;
            }
            flipParts.flapNode.dataset.flipValue = current;
            flipParts.valueNode.textContent = value;
            node.classList.remove('is-flipping');
            void node.offsetWidth;
            if (initialized) node.classList.add('is-flipping');
        };
        const updatePanel = (panel, deadline) => {
            const days = panel.querySelector('[data-countdown-days]');
            const hours = panel.querySelector('[data-countdown-hours]');
            const minutes = panel.querySelector('[data-countdown-minutes]');
            const seconds = panel.querySelector('[data-countdown-seconds]');
            const label = panel.querySelector('[data-countdown-label]');
            if (!days || !hours || !minutes) return;
            if (!deadline || Number.isNaN(deadline.getTime())) return;
            const distance = deadline.getTime() - Date.now();
            panel.classList.toggle('is-live', distance > 0);
            panel.classList.toggle('is-urgent', distance > 0 && distance <= 86400000);
            if (distance <= 0) {
                setCountdownValue(days, '00');
                setCountdownValue(hours, '00');
                setCountdownValue(minutes, '00');
                setCountdownValue(seconds, '00');
                if (label) label.textContent = 'Registration deadline has started for event day';
                panel.classList.add('is-expired');
                return;
            }
            panel.classList.remove('is-expired');
            const totalSeconds = Math.floor(distance / 1000);
            setCountdownValue(days, pad(Math.floor(totalSeconds / 86400)));
            setCountdownValue(hours, pad(Math.floor((totalSeconds % 86400) / 3600)));
            setCountdownValue(minutes, pad(Math.floor((totalSeconds % 3600) / 60)));
            setCountdownValue(seconds, pad(totalSeconds % 60));
            if (label) label.textContent = distance <= 86400000 ? 'Final registration window' : 'Registration closes soon';
        };
        const tick = () => {
            panels.forEach(panel => {
                const deadlineValue = panel.dataset.countdownDeadline || '';
                updatePanel(panel, deadlineValue ? new Date(deadlineValue) : null);
            });
        };
        tick();
        window.setInterval(tick, 1000);
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCountdowns, { once: true });
    } else {
        initCountdowns();
    }
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

<?php
$outcomesSection = null;
$audienceSection = null;
foreach($sections as $section) {
    if (($section['type'] ?? '') === 'outcomes') $outcomesSection = $section;
    if (($section['type'] ?? '') === 'audience') $audienceSection = $section;
}
?>
<?php if($outcomesSection || $audienceSection): ?>
<section class="section-tight" id="learn">
    <div class="container">
        <p class="eyebrow">What You Learn</p>
        <article class="combined-learn-card">
            <?php if($outcomesSection): ?>
                <div class="combined-learn-card__panel">
                    <span>Outcomes</span>
                    <h2><?= e($outcomesSection['title'] ?? 'What You Will Learn') ?></h2>
                    <?php if(!empty($outcomesSection['subtitle'])): ?><p><?= e($outcomesSection['subtitle']) ?></p><?php endif; ?>
                    <?php if(!empty($outcomesSection['body'])): ?><p><?= e($outcomesSection['body']) ?></p><?php endif; ?>
                    <?php if(!empty($outcomesSection['items']) && is_array($outcomesSection['items'])): ?>
                        <ul>
                            <?php foreach($outcomesSection['items'] as $item): ?>
                                <li><?= e($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if($audienceSection): ?>
                <div class="combined-learn-card__panel">
                    <span>Audience</span>
                    <h2><?= e($audienceSection['title'] ?? 'Designed For') ?></h2>
                    <?php if(!empty($audienceSection['subtitle'])): ?><p><?= e($audienceSection['subtitle']) ?></p><?php endif; ?>
                    <?php if(!empty($audienceSection['body'])): ?><p><?= e($audienceSection['body']) ?></p><?php endif; ?>
                    <?php if(!empty($audienceSection['items']) && is_array($audienceSection['items'])): ?>
                        <ul>
                            <?php foreach($audienceSection['items'] as $item): ?>
                                <li><?= e($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </article>
    </div>
</section>
<?php endif; ?>

<?php foreach($sections as $section): ?>
    <?php if(($section['type'] ?? '') === 'faq') continue; ?>
    <?php if(($section['type'] ?? '') === 'trust') continue; ?>
    <?php if(in_array(($section['type'] ?? ''), ['outcomes', 'audience'], true)) continue; ?>
    <section class="section-tight">
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
        <div class="speaker-showcase section-grid-offset" data-speaker-carousel>
            <button class="speaker-nav speaker-nav-prev" type="button" data-speaker-prev aria-label="Previous speaker">‹</button>
            <div class="speaker-carousel" aria-label="Speaker lineup carousel" data-speaker-track>
            <?php foreach($speakers as $speakerIndex => $speaker): ?>
                <?php
                $speakerName = (string)($speaker['name'] ?? 'Speaker');
                $speakerCountry = (string)($speaker['country'] ?? '');
                $speakerPhoto = (string)($speaker['photo_url'] ?? '');
                $initials = implode('', array_slice(array_map(fn($part) => strtoupper(substr($part, 0, 1)), array_filter(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', '', $speakerName)) ?: [])), 0, 2));
                $initials = $initials !== '' ? $initials : 'GC';
                ?>
                <article
                    class="speaker-card <?= $speakerIndex === 0 ? 'is-active' : '' ?>"
                    data-speaker-card
                    data-speaker-index="<?= e((string)$speakerIndex) ?>"
                    data-name="<?= e($speakerName) ?>"
                    data-country="<?= e($speakerCountry) ?>"
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
                    <div class="country"><span><?= e($speakerCountry) ?></span></div>
                    <div class="speaker-avatar">
                        <?php if($speakerPhoto !== ''): ?>
                            <img src="<?= e($speakerPhoto) ?>" alt="<?= e($speakerName) ?>">
                        <?php else: ?>
                            <div class="speaker-initials" aria-label="<?= e($speakerName) ?> photo pending"><?= e($initials) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="speaker-card__body">
                        <h3><?= e($speakerName) ?></h3>
                        <?php if(!empty($speaker['credentials'])): ?><p class="credentials"><?= e($speaker['credentials']) ?></p><?php endif; ?>
                        <p class="topic"><?= e($speaker['topic'] ?? '') ?></p>
                        <p class="time-label"><?= e($speaker['session_time'] ?? '') ?></p>
                        <p class="profile"><?= e($speaker['profile'] ?? '') ?></p>
                        <span class="speaker-card__cue">View profile</span>
                    </div>
                </article>
            <?php endforeach; ?>
            </div>
            <button class="speaker-nav speaker-nav-next" type="button" data-speaker-next aria-label="Next speaker">›</button>
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
    const carousel = document.querySelector('[data-speaker-carousel]');
    const speakerTrack = carousel?.querySelector('[data-speaker-track]');
    const originalCards = speakerTrack ? [...speakerTrack.querySelectorAll('[data-speaker-card]')] : [];
    const originalCount = originalCards.length;
    if (speakerTrack && originalCount > 1) {
        originalCards.forEach((card, index) => {
            card.dataset.speakerLogicalIndex = String(index);
            const afterClone = card.cloneNode(true);
            afterClone.dataset.speakerClone = 'after';
            afterClone.classList.remove('is-active');
            afterClone.setAttribute('aria-hidden', 'true');
            speakerTrack.appendChild(afterClone);
        });
        originalCards.slice().reverse().forEach(card => {
            const beforeClone = card.cloneNode(true);
            beforeClone.dataset.speakerClone = 'before';
            beforeClone.classList.remove('is-active');
            beforeClone.setAttribute('aria-hidden', 'true');
            speakerTrack.insertBefore(beforeClone, speakerTrack.firstChild);
        });
    }
    const cards = speakerTrack ? [...speakerTrack.querySelectorAll('[data-speaker-card]')] : originalCards;
    let currentSpeaker = 0;
    let currentDisplayIndex = originalCount > 1 ? originalCount : 0;
    let speakerTimer = null;
    const media = modal.querySelector('[data-speaker-modal-media]');
    const country = modal.querySelector('[data-speaker-modal-country]');
    const fields = {
        name: modal.querySelector('[data-speaker-modal-name]'),
        credentials: modal.querySelector('[data-speaker-modal-credentials]'),
        topic: modal.querySelector('[data-speaker-modal-topic]'),
        time: modal.querySelector('[data-speaker-modal-time]'),
        profile: modal.querySelector('[data-speaker-modal-profile]'),
    };
    const normalize = index => (index + originalCount) % originalCount;
    const stopAuto = () => {
        window.clearTimeout(speakerTimer);
        speakerTimer = null;
    };
    const centerCard = (card, behavior = 'smooth') => {
        if (!speakerTrack || !card) return;
        const left = card.offsetLeft - ((speakerTrack.clientWidth - card.clientWidth) / 2);
        speakerTrack.scrollTo({ left: Math.max(0, left), behavior });
    };
    const syncLoopPosition = () => {
        if (originalCount < 2) return;
        if (currentDisplayIndex >= originalCount * 2) {
            currentDisplayIndex = originalCount;
            centerCard(cards[currentDisplayIndex], 'auto');
        } else if (currentDisplayIndex < originalCount) {
            currentDisplayIndex = originalCount + originalCount - 1;
            centerCard(cards[currentDisplayIndex], 'auto');
        }
    };
    const render = (behavior = 'smooth') => {
        cards.forEach((card, index) => {
            const logicalIndex = Number(card.dataset.speakerLogicalIndex || card.dataset.speakerIndex || 0);
            const isCurrentDisplay = index === currentDisplayIndex;
            card.classList.toggle('is-active', isCurrentDisplay);
            card.setAttribute('aria-hidden', card.dataset.speakerClone ? 'true' : 'false');
            card.dataset.speakerLogicalIndex = String(logicalIndex);
        });
        centerCard(cards[currentDisplayIndex], behavior);
        if (behavior !== 'auto') window.setTimeout(syncLoopPosition, 680);
    };
    const go = index => {
        const step = index - currentSpeaker;
        currentDisplayIndex += step;
        currentSpeaker = normalize(index);
        render();
    };
    const startAuto = () => {
        if (originalCount < 2 || modal.getAttribute('aria-hidden') === 'false') return;
        stopAuto();
        speakerTimer = window.setTimeout(() => {
            go(currentSpeaker + 1);
            startAuto();
        }, 3200);
    };
    const restartAuto = () => {
        startAuto();
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
        const countryText = document.createElement('span');
        countryText.textContent = card.dataset.country || '';
        country.append(countryText);
        fields.name.textContent = card.dataset.name || '';
        fields.credentials.textContent = card.dataset.credentials || '';
        fields.topic.textContent = card.dataset.topic || '';
        fields.time.textContent = card.dataset.time || '';
        fields.profile.textContent = card.dataset.profile || '';
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('speaker-modal-open');
        stopAuto();
    };
    const close = () => {
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('speaker-modal-open');
        startAuto();
    };
    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            const logicalIndex = Number(card.dataset.speakerLogicalIndex || card.dataset.speakerIndex || 0);
            if (logicalIndex !== currentSpeaker || index !== currentDisplayIndex) {
                currentDisplayIndex = index;
                currentSpeaker = normalize(logicalIndex);
                render();
                restartAuto();
                return;
            }
            open(card);
        });
        card.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                const logicalIndex = Number(card.dataset.speakerLogicalIndex || card.dataset.speakerIndex || 0);
                currentDisplayIndex = index;
                currentSpeaker = normalize(logicalIndex);
                render();
                open(card);
            }
        });
    });
    carousel?.querySelector('[data-speaker-prev]')?.addEventListener('click', () => { go(currentSpeaker - 1); restartAuto(); });
    carousel?.querySelector('[data-speaker-next]')?.addEventListener('click', () => { go(currentSpeaker + 1); restartAuto(); });
    modal.querySelectorAll('[data-speaker-close]').forEach(button => button.addEventListener('click', close));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') close();
        if (modal.getAttribute('aria-hidden') === 'true' && event.key === 'ArrowLeft') { go(currentSpeaker - 1); restartAuto(); }
        if (modal.getAttribute('aria-hidden') === 'true' && event.key === 'ArrowRight') { go(currentSpeaker + 1); restartAuto(); }
    });
    render('auto');
    startAuto();
})();
</script>

<section class="section" id="agenda">
    <div class="container">
        <p class="eyebrow">Agenda</p>
        <h2>Conference Schedule</h2>
        <?php
        $speakerByName = [];
        foreach ($speakers as $speaker) {
            if (!empty($speaker['name'])) $speakerByName[strtolower(trim((string)$speaker['name']))] = $speaker;
        }
        $agendaFallbackImage = (string)($event['logo_url'] ?? '/assets/images/media/gutconference-mark.png');
        ?>
        <div class="timeline section-grid-offset">
            <?php foreach($sessions as $index => $session): ?>
                <?php
                $timeParts = array_map('trim', explode('–', (string)($session['time_label'] ?? ''), 2));
                if (count($timeParts) === 1) $timeParts = array_map('trim', explode('-', (string)($session['time_label'] ?? ''), 2));
                $startTime = $timeParts[0] ?? '';
                $endTime = $timeParts[1] ?? '';
                $sessionSpeaker = $speakerByName[strtolower(trim((string)($session['speaker_name'] ?? '')))] ?? null;
                $sessionSpeakerPhoto = (string)($sessionSpeaker['photo_url'] ?? '');

                // Topic-related background images
                $sessionImage = $agendaFallbackImage;
                $topic = strtolower((string)($session['title'] ?? ''));
                if (str_contains($topic, 'microbiome') && str_contains($topic, 'health and disease')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1576086213369-97a306d36557?w=800';
                } elseif (str_contains($topic, 'meta-gut') || str_contains($topic, 'meta-phenotype')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800';
                } elseif (str_contains($topic, 'thought') || str_contains($topic, 'emotion') || str_contains($topic, 'beliefs shape')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1617791160505-6f00504e3519?w=800';
                } elseif (str_contains($topic, 'autoimmunity') || str_contains($topic, 'functional medicine')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=800';
                } elseif (str_contains($topic, 'prebiotics') || str_contains($topic, 'prebiotics and their role')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?w=800';
                } elseif (str_contains($topic, 'ayurvedic') || str_contains($topic, 'agni') || str_contains($topic, 'grahani')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800';
                } elseif (str_contains($topic, 'lunch') || str_contains($topic, 'break')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800';
                } elseif (str_contains($topic, 'ozone')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1530026405186-ed1f139313f8?w=800';
                } elseif (str_contains($topic, 'gut-brain') || str_contains($topic, 'brain connection')) {
                    $sessionImage = 'https://www.bariatricfusion.com/cdn/shop/articles/the-gut-brain-connection-6134583.png';
                } elseif (str_contains($topic, 'hashimoto') || str_contains($topic, 'stress')) {
                    $sessionImage = 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800';
                }

                $speakerInitials = '';
                if ($sessionSpeaker) {
                    $nameParts = array_filter(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', '', (string)($sessionSpeaker['name'] ?? ''))) ?: []);
                    $speakerInitials = implode('', array_slice(array_map(fn($part) => strtoupper(substr($part, 0, 1)), $nameParts), 0, 2));
                    $speakerInitials = $speakerInitials !== '' ? $speakerInitials : 'GC';
                }
                ?>
                <div class="timeline-row" style="background-image: linear-gradient(rgba(255,255,255,0.90), rgba(255,255,255,0.88)), url('<?= e($sessionImage) ?>'); background-size: cover; background-position: center;">
                    <div class="agenda-time" aria-label="<?= e($session['time_label'] ?? '') ?>">
                        <strong><?= e($startTime) ?></strong>
                        <?php if($endTime !== ''): ?>
                            <span aria-hidden="true">↓</span>
                            <strong><?= e($endTime) ?></strong>
                        <?php endif; ?>
                    </div>
                    <div class="agenda-speaker-circle">
                        <?php if($sessionSpeaker && $sessionSpeakerPhoto !== ''): ?>
                            <img src="<?= e($sessionSpeakerPhoto) ?>" alt="<?= e($sessionSpeaker['name'] ?? '') ?>" loading="lazy">
                        <?php elseif($sessionSpeaker): ?>
                            <div class="speaker-initials"><?= e($speakerInitials) ?></div>
                        <?php elseif(str_contains(strtolower((string)($session['title'] ?? '')), 'lunch') || str_contains(strtolower((string)($session['title'] ?? '')), 'break')): ?>
                            <div class="welcome-icon">🍽️</div>
                        <?php else: ?>
                            <div class="welcome-icon">🎤</div>
                        <?php endif; ?>
                    </div>
                    <div class="agenda-copy">
                        <strong><?= e($session['title'] ?? '') ?></strong>
                        <?php if(!empty($session['speaker_name'])): ?>
                            <span><?= e($session['speaker_name']) ?></span>
                        <?php endif; ?>
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

<section class="section certificate-preview-section" aria-labelledby="certificate-preview-title">
    <div class="container" style="max-width: 900px;">
        <div class="certificate-preview-copy" style="text-align: center; margin-bottom: 48px;">
            <p class="eyebrow" style="justify-content: center;">Sample Certificate</p>
            <h2 id="certificate-preview-title">Participants receive a verified E-certificate.</h2>
            <p class="lede" style="margin-left: auto; margin-right: auto;">Use this sample to preview the participation certificate format for the conference. Final certificates are issued after the event completion workflow.</p>
        </div>
        <article class="certificate-sample" aria-label="Sample certificate of participation" style="aspect-ratio: 1.414 / 1; width: 100%; max-width: 100%; margin: 0 auto;">
            <div class="certificate-sample__corner certificate-sample__corner--tl"></div>
            <div class="certificate-sample__corner certificate-sample__corner--tr"></div>
            <div class="certificate-sample__corner certificate-sample__corner--bl"></div>
            <div class="certificate-sample__corner certificate-sample__corner--br"></div>
            <div class="certificate-sample__accent certificate-sample__accent--top"></div>
            <div class="certificate-sample__accent certificate-sample__accent--bottom"></div>
            <div class="certificate-sample__seal"></div>
            <div class="certificate-sample__body">
                <div class="certificate-sample__header">
                    <div class="certificate-sample__logo-container">
                        <img src="/assets/images/media/gutconference-mark.png" alt="" loading="lazy">
                        <div class="certificate-sample__logo-ring"></div>
                    </div>
                    <div class="certificate-sample__event-info">
                        <span class="certificate-sample__edition">3rd International Conference On</span>
                        <strong class="certificate-sample__event-title">Gut Health, Probiotics &amp; Prebiotics</strong>
                        <em class="certificate-sample__tagline">Bridging Science &amp; Clinical Healing</em>
                    </div>
                </div>
                <div class="certificate-sample__title">
                    <div class="certificate-sample__title-ornament certificate-sample__title-ornament--left"></div>
                    <div class="certificate-sample__title-text">
                        <h3>Certificate</h3>
                        <span>of participation</span>
                    </div>
                    <div class="certificate-sample__title-ornament certificate-sample__title-ornament--right"></div>
                </div>
                <p class="certificate-sample__certify">This is to certify that</p>
                <div class="certificate-sample__name-container">
                    <div class="certificate-sample__name-line" aria-hidden="true"></div>
                    <div class="certificate-sample__name-decoration"></div>
                </div>
                <p class="certificate-sample__details">
                    has successfully participated as a <strong>Healthcare Professional Delegate</strong> in the
                    <strong><?= e($event['name'] ?? 'GutConference event') ?></strong>
                    held on <strong><?= e($event['date_label'] ?? '') ?></strong>.
                </p>
                <div class="certificate-sample__meta">
                    <div class="certificate-sample__meta-item">
                        <span class="certificate-sample__meta-label">Delegate Category</span>
                        <strong class="certificate-sample__meta-value">Healthcare Professional</strong>
                    </div>
                    <div class="certificate-sample__meta-item">
                        <span class="certificate-sample__meta-label">Duration</span>
                        <strong class="certificate-sample__meta-value"><?= e($eventTime) ?> Hours</strong>
                    </div>
                    <div class="certificate-sample__meta-item">
                        <span class="certificate-sample__meta-label">Certificate ID</span>
                        <strong class="certificate-sample__meta-value">GGS2026-______</strong>
                    </div>
                </div>
                <div class="certificate-sample__footer">
                    <div class="certificate-sample__signature-line">
                        <div class="certificate-sample__signature">
                            <div class="certificate-sample__signature-mark"></div>
                            <span>Authorized Signature</span>
                        </div>
                    </div>
                    <div class="certificate-sample__organizer">
                        <span>Organized by</span>
                        <strong><?= e($event['organizers'] ?? 'GutConference') ?></strong>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

<div class="sticky-register" data-countdown-deadline="<?= e($countdownDeadline ?? '') ?>">
    <div class="container">
        <div class="sticky-register__countdown" aria-label="Registration countdown">
            <div class="sticky-register__message">
                <span>HURRY, IT’S <b>TICKING</b></span>
                <strong data-countdown-label></strong>
            </div>
            <div class="sticky-register__timer">
                <div><strong data-countdown-days>--</strong><span>Days</span></div>
                <div><strong data-countdown-hours>--</strong><span>Hours</span></div>
                <div><strong data-countdown-minutes>--</strong><span>Minutes</span></div>
                <div><strong data-countdown-seconds>--</strong><span>Seconds</span></div>
            </div>
        </div>
        <a class="link-arrow link-arrow-primary" href="<?= empty($_SESSION['user']) ? '/login' : '#registration' ?>"><?= empty($_SESSION['user']) ? e($eventPrice . ' Join Now') : 'Buy Ticket' ?> <span aria-hidden="true">→</span></a>
    </div>
</div>
<?php endif; ?>
