<?php
$host = $_SERVER['HTTP_HOST'] ?? 'gutconference.online';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$configuredUrl = rtrim((string) ($_ENV['APP_URL'] ?? 'https://gutconference.online'), '/');
$isLocalHost = str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost');
$baseUrl = $isLocalHost ? 'http://' . $host : $configuredUrl;
$currentUrl = $baseUrl . $path;
$layoutFeaturedEvent = $featuredEvent ?? null;
if (empty($layoutFeaturedEvent)) {
    $layoutFeaturedEvent = (new \App\Services\EventService())->featured();
}
$layoutRegisterPath = !empty($layoutFeaturedEvent['slug']) ? '/events/' . $layoutFeaturedEvent['slug'] : '/events';

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
<link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a href="/" class="brand">
            <img src="/assets/images/media/gutconference-mark.png" alt="GutConference mark">
            <span class="brand-name">
                <small>International Conference On</small>
                <strong>Microbiome</strong>
                <span>Probiotics & Gut Nutrition</span>
            </span>
        </a>
        <nav class="nav-links">
            <a href="/" class="<?= $path === '/' ? 'active' : '' ?>">Home</a>
            <a href="/events" class="<?= str_starts_with($path, '/events') ? 'active' : '' ?>">Events</a>
            <a href="/about" class="<?= $path === '/about' ? 'active' : '' ?>">About</a>
            <a href="/contact" class="<?= $path === '/contact' ? 'active' : '' ?>">Contact</a>
            <?php if(!empty($_SESSION['user'])): ?><a href="/logout">Logout</a><?php endif; ?>
        </nav>
        <?php if(empty($_SESSION['user'])): ?>
            <a class="link-arrow nav-cta" href="/login">Login <span aria-hidden="true">→</span></a>
        <?php else: ?>
            <a class="link-arrow nav-cta" href="<?= e($layoutRegisterPath) ?>">Book <span aria-hidden="true">→</span></a>
        <?php endif; ?>
    </div>
</header>
<?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<main><?php require $viewFile; ?></main>
<footer class="footer">
    <div class="container footer-grid">
        <div><strong>GutConference</strong><p>Dr. Praveen Jacob's clinical profile, gut-health education, consultation pathways, and microbiome-focused events.</p></div>
        <div><strong>Navigate</strong><p><a href="/events">Events</a><br><a href="/about">About</a><br><a href="/contact">Contact</a></p></div>
        <div><strong>Contact</strong><p>gutconference2026@gmail.com<br>+91 97314 82585</p></div>
    </div>
</footer>
</body>
</html>
