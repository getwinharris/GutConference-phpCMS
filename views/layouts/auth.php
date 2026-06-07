<?php
$host = $_SERVER['HTTP_HOST'] ?? 'gutconference.online';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$configuredUrl = rtrim((string) ($_ENV['APP_URL'] ?? 'https://gutconference.online'), '/');
$isLocalHost = str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost');
$baseUrl = $isLocalHost ? 'http://' . $host : $configuredUrl;
$currentUrl = $baseUrl . $path;
$layoutSecrets = (new \App\Services\SecretService())->all();
$googleSiteTagId = trim((string)($layoutSecrets['google_site_tag_id'] ?? ''));
$googleTagManagerId = trim((string)($layoutSecrets['google_tag_manager_id'] ?? ''));
$googleVerification = trim((string)($layoutSecrets['google_search_console_verification'] ?? ''));
$isSignedIn = !empty($_SESSION['user']);

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
<body class="auth-only <?= $isSignedIn ? 'has-user-rail' : 'is-guest' ?>">
<?php if($googleTagManagerId !== ''): ?><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e($googleTagManagerId) ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><?php endif; ?>
<?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<a class="auth-only-brand" href="/" aria-label="GutConference home">
    <img src="/assets/images/media/gutconference-mark.png" alt="GutConference mark">
    <span><strong>GutConference</strong></span>
</a>
<main class="auth-only-main"><?php require $viewFile; ?></main>
</body>
</html>
