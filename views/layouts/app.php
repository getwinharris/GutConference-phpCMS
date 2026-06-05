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
$showLayoutEventSlider = !str_starts_with($path, '/events');
$layoutSecrets = (new \App\Services\SecretService())->all();
$googleSiteTagId = trim((string)($layoutSecrets['google_site_tag_id'] ?? ''));
$googleTagManagerId = trim((string)($layoutSecrets['google_tag_manager_id'] ?? ''));
$googleVerification = trim((string)($layoutSecrets['google_search_console_verification'] ?? ''));
$loggedInUser = $_SESSION['user'] ?? null;
$isSignedIn = !empty($loggedInUser);

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
<link rel="stylesheet" href="/assets/css/index.css">
</head>
<body class="<?= $isSignedIn ? 'has-user-rail' : 'is-guest' ?>">
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
<?php if($showLayoutEventSlider && !empty($layoutEvents)): ?>
<section class="section site-event-slider" aria-labelledby="site-event-slider-title">
    <div class="container">
        <div class="site-section-heading">
            <div>
                <p class="eyebrow">Events &amp; Classes</p>
                <h2 id="site-event-slider-title">Explore upcoming GutConference programs.</h2>
            </div>
            <a class="link-arrow" href="/events">View all <span aria-hidden="true">→</span></a>
        </div>
        <div class="event-card-scroll" aria-label="Scrollable event cards">
            <?php foreach($layoutEvents as $layoutEvent): ?>
                <?php
                    $layoutSlug = (string)($layoutEvent['slug'] ?? '');
                    $layoutThumb = $layoutEvent['thumbnail_url'] ?? $layoutEvent['hero_image_url'] ?? $layoutEvent['logo_url'] ?? '/assets/images/media/gutconference-logo.jpg';
                    $layoutTime = $layoutEventService->timeRange($layoutEvent);
                    $layoutSessions = $layoutEventService->sessions($layoutSlug);
                    $layoutSlotSummary = $layoutEventService->shortSlotSummary($layoutEvent, $layoutSessions);
                ?>
                <article class="event-card site-event-card">
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
    </div>
</section>
<?php endif; ?>
<section class="section site-contact-cta" aria-labelledby="site-contact-cta-title">
    <div class="container grid-2 cta-grid">
        <div>
            <p class="eyebrow">Need Guidance?</p>
            <h2 id="site-contact-cta-title">Ask about consultations, events, certificates, or support.</h2>
            <p class="lede">Send your enquiry through the contact form and the GutConference team will route it to the right support path.</p>
        </div>
        <div class="cta-actions">
            <div class="site-contact-actions" aria-label="Contact and account actions">
                <a class="contact-chip" href="tel:<?= e($layoutContactPhoneHref) ?>">
                    <span>Call</span>
                    <strong><?= e($layoutContactPhone) ?></strong>
                </a>
                <a class="contact-chip" href="mailto:<?= e($layoutContactEmail) ?>">
                    <span>Email</span>
                    <strong><?= e($layoutContactEmail) ?></strong>
                </a>
                <div class="contact-auth-actions">
                    <a class="google-login-button" href="/auth/google"><span class="google-mark" aria-hidden="true">G</span><span>Google Login</span></a>
                    <a class="google-login-button" href="/signup"><span class="google-mark" aria-hidden="true">G</span><span>Google Signup</span></a>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="container footer-grid">
        <div><strong>GutConference</strong><p>Dr. Praveen Jacob's clinical profile, gut-health education, consultation pathways, and microbiome-focused events.</p></div>
        <div><strong>Navigate</strong><p><a href="/events">Events</a><br><a href="/contact">Contact</a></p></div>
        <div><strong>Contact</strong><p>gutconference2026@gmail.com<br>+91 97314 82585</p></div>
    </div>
</footer>
<div class="support-widget" data-support-widget>
    <div class="support-panel" data-support-panel aria-hidden="true">
        <div class="support-chat">
            <div class="support-chat__head">
                <div><strong>Gemini Support Agent</strong><span>Grounded in GutConference CMS data</span></div>
                <button type="button" data-support-close aria-label="Minimize support">-</button>
            </div>
            <div class="support-messages" data-support-messages></div>
            <form class="support-form" data-support-form>
                <textarea name="message" rows="2" required placeholder="Ask about events, joined courses, certificates, or tell us what to improve"></textarea>
                <button class="support-send" type="submit" data-support-send aria-label="Send message"><span aria-hidden="true">↑</span></button>
            </form>
        </div>
    </div>
</div>
<script>
(() => {
    const widget = document.querySelector('[data-support-widget]');
    if (!widget) return;
    const panel = widget.querySelector('[data-support-panel]');
    const messages = widget.querySelector('[data-support-messages]');
    const form = widget.querySelector('[data-support-form]');
    const sendButton = widget.querySelector('[data-support-send]');
    let started = false;
    let controller = null;
    const open = () => {
        widget.classList.add('is-open');
        document.body.classList.add('support-open');
        panel?.setAttribute('aria-hidden', 'false');
        if (!started) {
            started = true;
            ask('__intro', false);
        }
    };
    const close = () => { widget.classList.remove('is-open'); document.body.classList.remove('support-open'); panel?.setAttribute('aria-hidden', 'true'); };
    const add = (text, type, links = []) => {
        const item = document.createElement('p');
        item.className = 'support-message support-message--' + type;
        item.textContent = text;
        messages.appendChild(item);
        if (links.length) {
            const actions = document.createElement('div');
            actions.className = 'support-actions';
            links.forEach(link => {
                if (!link.url || !link.label) return;
                const action = document.createElement('a');
                action.href = link.url;
                action.textContent = link.label;
                actions.appendChild(action);
            });
            messages.appendChild(actions);
        }
        messages.scrollTop = messages.scrollHeight;
    };
    const setThinking = active => {
        widget.classList.toggle('is-thinking', active);
        if (sendButton) {
            sendButton.setAttribute('aria-label', active ? 'Stop response' : 'Send message');
            sendButton.innerHTML = active ? '<span aria-hidden="true">■</span>' : '<span aria-hidden="true">↑</span>';
        }
    };
    const ask = async (message, echo = true) => {
        const input = form.elements.message;
        if (echo) add(message, 'user');
        controller = new AbortController();
        setThinking(true);
        try {
            const body = new URLSearchParams({ message });
            const response = await fetch('/support/chat', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body, signal: controller.signal });
            const data = await response.json();
            add(data.answer || 'Support is available. Please try again.', 'agent', data.links || []);
        } catch (error) {
            if (error.name !== 'AbortError') add('Support chat could not connect. Please use the Contact page or try again.', 'agent', [{ label: 'Contact Team', url: '/contact' }]);
        } finally {
            controller = null;
            setThinking(false);
            if (input) input.focus();
        }
    };
    document.querySelectorAll('[data-support-open]').forEach(button => {
        button.addEventListener('click', () => widget.classList.contains('is-open') ? close() : open());
    });
    widget.querySelector('[data-support-close]')?.addEventListener('click', close);
    form?.elements.message?.addEventListener('keydown', event => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });
    sendButton?.addEventListener('click', event => {
        if (controller) {
            event.preventDefault();
            controller.abort();
            controller = null;
            setThinking(false);
        }
    });
    form?.addEventListener('submit', async event => {
        event.preventDefault();
        const input = form.elements.message;
        const message = input.value.trim();
        if (!message) return;
        input.value = '';
        ask(message);
    });
})();
</script>
</body>
</html>
