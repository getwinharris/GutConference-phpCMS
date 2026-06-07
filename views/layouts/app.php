<?php
$host = $_SERVER['HTTP_HOST'] ?? 'gutconference.online';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$configuredUrl = rtrim((string) ($_ENV['APP_URL'] ?? 'https://gutconference.online'), '/');
$isLocalHost = str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost');
$baseUrl = $isLocalHost ? 'http://' . $host : $configuredUrl;
$currentUrl = $baseUrl . $path;
$layoutFeaturedEvent = $featuredEvent ?? null;
$layoutEventService = new \App\Services\EventService();
if (empty($layoutFeaturedEvent)) {
    $layoutFeaturedEvent = $layoutEventService->featured();
}
$layoutEvents = $layoutEventService->published();
$layoutRegisterPath = !empty($layoutFeaturedEvent['slug']) ? '/events/' . $layoutFeaturedEvent['slug'] : '/events';
$layoutContactEmail = (string)($layoutFeaturedEvent['contact_email'] ?? 'gutconference2026@gmail.com');
$layoutContactPhone = (string)($layoutFeaturedEvent['contact_phone'] ?? '+91 97314 82585');
$layoutContactPhoneHref = preg_replace('/[^\d+]/', '', $layoutContactPhone);
$layoutPathOnly = strtok($path, '?') ?: $path;
$isContactPage = $layoutPathOnly === '/contact';
$showLayoutEventSlider = !str_starts_with($path, '/events');
$layoutSecrets = (new \App\Services\SecretService())->all();
$googleSiteTagId = trim((string)($layoutSecrets['google_site_tag_id'] ?? ''));
$googleTagManagerId = trim((string)($layoutSecrets['google_tag_manager_id'] ?? ''));
$googleVerification = trim((string)($layoutSecrets['google_search_console_verification'] ?? ''));
$loggedInUser = $_SESSION['user'] ?? null;
$isSignedIn = !empty($loggedInUser);
$hideSiteChrome = $hideSiteChrome ?? false;
$bodyClass = isset($bodyClass) ? (string)$bodyClass : '';

// Dynamic SEO
if (!empty($event)) {
    $title = 'GutConference - ' . e($event['name']);
    $desc = e($event['subheadline'] ?? $event['description'] ?? 'Professional online conferences for microbiome, probiotics, and gut nutrition education.');
} else {
    $title = !empty($pageTitle) ? e($pageTitle) . ' - GutConference' : 'GutConference - Microbiome, Probiotics & Gut Nutrition';
    $desc = 'GutConference presents Dr. Praveen Jacob, gut-health education, clinical consultation, and microbiome-focused events.';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="format-detection" content="telephone=no">
<title><?= $title ?></title>
<meta name="description" content="<?= $desc ?>">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<link rel="canonical" href="<?= e($currentUrl) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="GutConference">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $desc ?>">
<meta property="og:url" content="<?= e($currentUrl) ?>">
<meta property="og:image" content="<?= e($baseUrl) ?>/assets/images/media/gutconference-logo.png">
<meta property="og:locale" content="en_IN">
<?php if($googleVerification !== ''): ?><meta name="google-site-verification" content="<?= e($googleVerification) ?>"><?php endif; ?>
<?php if($googleSiteTagId !== ''): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($googleSiteTagId) ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?= e($googleSiteTagId) ?>');
</script>
<?php endif; ?>
<?php if($googleTagManagerId !== ''): ?>
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?= e($googleTagManagerId) ?>');
</script>
<?php endif; ?>
<link rel="stylesheet" href="/assets/css/index.css?v=<?= e((string)@filemtime(app_path('assets/css/index.css'))) ?>">
</head>
<body class="<?= trim(($isSignedIn ? 'has-user-rail' : 'is-guest') . ' ' . $bodyClass) ?>">
<?php if($googleTagManagerId !== ''): ?><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e($googleTagManagerId) ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><?php endif; ?>
<header class="site-header">
    <div class="container nav">
        <a href="/" class="brand">
            <img src="/assets/images/media/gutconference-mark.png" alt="GutConference mark">
            <span class="brand-name">
                <strong>GutConference</strong>
                <span>Expert Gut &amp; Health Advice</span>
            </span>
        </a>
        <nav class="nav-links">
            <a href="/" class="<?= $path === '/' ? 'active' : '' ?>">Home</a>
            <a href="/events" class="<?= str_starts_with($path, '/events') ? 'active' : '' ?>">Events</a>
            <?php if(!empty($_SESSION['user'])): ?><a href="/dashboard" class="<?= $path === '/dashboard' ? 'active' : '' ?>">Dashboard</a><?php endif; ?>
            <a href="/contact" class="<?= $path === '/contact' ? 'active' : '' ?>">Contact</a>
            <?php if(!empty($_SESSION['user'])): ?><a href="/logout">Logout</a><?php endif; ?>
        </nav>
        <div class="nav-actions">
        <?php if(empty($loggedInUser)): ?>
            <a class="link-arrow nav-cta" href="/login">Login <span aria-hidden="true">→</span></a>
        <?php else: ?>
            <a class="link-arrow nav-cta" href="/dashboard">My Panel <span aria-hidden="true">→</span></a>
        <?php endif; ?>
            <button class="support-launcher" type="button" data-support-open aria-label="Open Gemini support agent">
                <span class="gemini-mark" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</header>
<?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<?php if($isSignedIn): ?>
<aside class="user-rail" aria-label="User dashboard navigation">
    <div class="user-rail__profile">
        <strong><?= e($loggedInUser['certificate_name'] ?? $loggedInUser['name'] ?? 'My Panel') ?></strong>
        <span><?= e($loggedInUser['email'] ?? 'Signed in') ?></span>
    </div>
    <nav>
        <a href="/dashboard" class="<?= $path === '/dashboard' ? 'active' : '' ?>">Event/Course</a>
        <a href="/dashboard#certificates">Certificates</a>
        <a href="/events">Events &amp; Classes</a>
        <a href="/contact">Support Ticket</a>
        <a href="/logout">Logout</a>
    </nav>
</aside>
<?php endif; ?>
<main><?php require $viewFile; ?></main>
<?php if(!$hideSiteChrome && $showLayoutEventSlider && !empty($layoutEvents)): ?>
<?php $layoutEventCount = count($layoutEvents); ?>
<section class="section site-event-slider" aria-labelledby="site-event-slider-title">
    <div class="container">
        <div class="site-section-heading">
            <div>
                <p class="eyebrow">Events &amp; Classes</p>
                <h2 id="site-event-slider-title">Explore upcoming GutConference programs.</h2>
            </div>
            <a class="link-arrow" href="/events">View all <span aria-hidden="true">→</span></a>
        </div>
        <div
            class="event-card-scroll <?= $layoutEventCount > 1 ? 'event-card-scroll--fade' : 'event-card-scroll--single' ?>"
            aria-label="<?= $layoutEventCount > 1 ? 'Featured event carousel' : 'Featured event card' ?>"
            data-event-carousel
            data-event-count="<?= e((string)$layoutEventCount) ?>"
        >
            <?php foreach($layoutEvents as $layoutIndex => $layoutEvent): ?>
                <?php
                    $layoutSlug = (string)($layoutEvent['slug'] ?? '');
                    $layoutThumb = $layoutEvent['thumbnail_url'] ?? $layoutEvent['hero_image_url'] ?? $layoutEvent['logo_url'] ?? '/assets/images/media/gutconference-logo.jpg';
                    $layoutTime = $layoutEventService->timeRange($layoutEvent);
                    $layoutSessions = $layoutEventService->sessions($layoutSlug);
                    $layoutSlotSummary = $layoutEventService->shortSlotSummary($layoutEvent, $layoutSessions);
                ?>
                <article class="event-card site-event-card <?= $layoutIndex === 0 ? 'is-active' : '' ?>" data-event-slide>
                    <a class="event-card__media" href="/events/<?= e($layoutSlug) ?>" aria-label="<?= e($layoutEvent['name'] ?? 'Event') ?>">
                        <img src="<?= e($layoutThumb) ?>" alt="<?= e($layoutEvent['name'] ?? 'Conference thumbnail') ?>" loading="lazy">
                    </a>
                    <div class="event-card__body">
                        <div class="event-card__meta">
                            <span><?= e($layoutEvent['mode'] ?? 'Conference') ?></span>
                            <span><?= count($layoutSessions) ?> agenda slots</span>
                        </div>
                        <h2><?= e($layoutEvent['name'] ?? 'GutConference event') ?></h2>
                        <p><?= e($layoutEvent['subheadline'] ?? $layoutEvent['description'] ?? '') ?></p>
                        <div class="event-card__facts">
                            <span><strong><?= e($layoutEvent['date_label'] ?? '') ?></strong>Date</span>
                            <span><strong><?= e($layoutTime) ?></strong>Time</span>
                            <span><strong><?= e($layoutSlotSummary) ?></strong>Slots</span>
                        </div>
                        <a class="link-arrow link-arrow-primary" href="/events/<?= e($layoutSlug) ?>"><?= e($layoutEvent['cta_label'] ?? 'View Event') ?> <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if($layoutEventCount > 1): ?>
            <div class="event-card-dots" aria-label="Choose featured event">
                <?php foreach($layoutEvents as $layoutIndex => $layoutEvent): ?>
                    <button class="<?= $layoutIndex === 0 ? 'is-active' : '' ?>" type="button" data-event-dot="<?= e((string)$layoutIndex) ?>" aria-label="Show event <?= e((string)($layoutIndex + 1)) ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php if($layoutEventCount > 1): ?>
<script>
(() => {
    const carousel = document.querySelector('[data-event-carousel]');
    if (!carousel || Number(carousel.dataset.eventCount || 0) < 2) return;

    const slides = [...carousel.querySelectorAll('[data-event-slide]')];
    const dots = [...document.querySelectorAll('[data-event-dot]')];
    let current = 0;
    let timer = null;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const render = () => {
        slides.forEach((slide, index) => {
            const active = index === current;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        });
        dots.forEach((dot, index) => dot.classList.toggle('is-active', index === current));
    };
    const go = index => {
        current = (index + slides.length) % slides.length;
        render();
    };
    const start = () => {
        if (reduceMotion) return;
        window.clearInterval(timer);
        timer = window.setInterval(() => go(current + 1), 5200);
    };

    dots.forEach((dot, index) => dot.addEventListener('click', () => {
        go(index);
        start();
    }));
    carousel.addEventListener('mouseenter', () => window.clearInterval(timer));
    carousel.addEventListener('mouseleave', start);
    render();
    start();
})();
</script>
<?php endif; ?>
<?php endif; ?>
<?php if(!$hideSiteChrome): ?>
<section class="section site-contact-cta" aria-labelledby="site-contact-cta-title">
    <div class="container grid-2 cta-grid">
        <div>
            <p class="eyebrow">Need Guidance?</p>
            <h2 id="site-contact-cta-title">Ask about Course, Events, Certificates, or Support.</h2>
            <p class="lede">Send your enquiry through the contact form and the GutConference team will route it to the right support path.</p>
        </div>
        <div class="cta-actions">
            <?php if($isContactPage): ?>
                <div class="site-contact-actions site-contact-actions--details" aria-label="Direct contact details">
                    <a class="contact-direct-card" href="mailto:<?= e($layoutContactEmail) ?>">
                        <span>Email</span>
                        <strong><?= e($layoutContactEmail) ?></strong>
                    </a>
                    <a class="contact-direct-card" href="tel:<?= e($layoutContactPhoneHref) ?>">
                        <span>Phone</span>
                        <strong><?= e($layoutContactPhone) ?></strong>
                    </a>
                </div>
            <?php else: ?>
                <div class="site-contact-actions" aria-label="Contact form action">
                    <a class="contact-form-button" href="/contact">
                        <span>Open Contact Form</span>
                        <strong>Send Enquiry</strong>
                        <small>Course, events, certificates, or support</small>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="container footer-grid">
        <div>
            <strong>gutconference.online</strong>
            <p>Dr. Praveen Jacob's clinical profile, gut-health education, consultation pathways, event booking, online class booking, and microbiome-focused events.</p>
            <p class="footer-links"><a href="/events">Event &amp; Course</a><br><a href="/contact">Contact</a></p>
        </div>
        <div><strong>Legal</strong><p><a href="/terms">Terms</a><br><a href="/privacy">Privacy</a></p></div>
        <div><strong>Contact</strong><p>gutconference2026@gmail.com<br>+91 97314 82585</p></div>
    </div>
</footer>
<?php endif; ?>
<?php
if(!$hideSiteChrome):
$supportPlaceholder = 'Ask about events, joined courses, certificates, or tell us what to improve';
$supportGoogleConnected = (new \App\Services\AuthService())->isGoogleConnected();
require app_path('views/partials/support-widget.php');
endif;
?>
<script>
(() => {
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!canHover || reduceMotion) return;

    const bubble = document.createElement('span');
    bubble.className = 'cursor-bubble';
    bubble.setAttribute('aria-hidden', 'true');
    document.body.appendChild(bubble);

    let x = -100;
    let y = -100;
    let displayX = x;
    let displayY = y;
    let visible = false;
    let bursting = false;

    const draw = () => {
        displayX += (x - displayX) * 0.24;
        displayY += (y - displayY) * 0.24;
        if (!bursting) {
            bubble.style.setProperty('--bubble-x', `${displayX - 10}px`);
            bubble.style.setProperty('--bubble-y', `${displayY - 10}px`);
            bubble.style.transform = `translate3d(${displayX - 10}px, ${displayY - 10}px, 0) scale(1)`;
        }
        window.requestAnimationFrame(draw);
    };

    const makePop = (originX, originY) => {
        const count = 8;
        for (let index = 0; index < count; index++) {
            const particle = document.createElement('span');
            const angle = (Math.PI * 2 * index) / count;
            const distance = 22 + Math.random() * 18;
            particle.className = 'cursor-bubble__pop';
            particle.setAttribute('aria-hidden', 'true');
            particle.style.setProperty('--pop-x', `${originX - 5}px`);
            particle.style.setProperty('--pop-y', `${originY - 5}px`);
            particle.style.setProperty('--pop-dx', `${Math.cos(angle) * distance}px`);
            particle.style.setProperty('--pop-dy', `${Math.sin(angle) * distance}px`);
            document.body.appendChild(particle);
            window.setTimeout(() => particle.remove(), 650);
        }
    };

    window.addEventListener('pointermove', event => {
        x = event.clientX;
        y = event.clientY;
        if (!visible) {
            visible = true;
            bubble.classList.add('is-visible');
        }
    }, { passive: true });

    window.addEventListener('pointerleave', () => {
        visible = false;
        bubble.classList.remove('is-visible');
    }, { passive: true });

    window.addEventListener('pointerdown', event => {
        if (bursting) return;
        bursting = true;
        x = event.clientX;
        y = event.clientY;
        displayX = x;
        displayY = y;
        bubble.style.setProperty('--bubble-x', `${x - 10}px`);
        bubble.style.setProperty('--bubble-y', `${y - 10}px`);
        bubble.classList.add('is-bursting');
        makePop(x, y);
        window.setTimeout(() => {
            bubble.classList.remove('is-bursting');
            bursting = false;
            if (visible) bubble.classList.add('is-visible');
        }, 390);
    }, { passive: true });

    draw();
})();
</script>
</body>
</html>
