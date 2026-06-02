<?php
$host = $_SERVER['HTTP_HOST'] ?? 'gutconference.online';
$path = $_SERVER['REQUEST_URI'] ?? '/';
$configuredUrl = rtrim((string) ($_ENV['APP_URL'] ?? 'https://gutconference.online'), '/');
$isLocalHost = str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost');
$baseUrl = $isLocalHost ? 'http://' . $host : $configuredUrl;
$currentUrl = $baseUrl . $path;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="format-detection" content="telephone=no">
<title>GutConference Online - Microbiome, Probiotics & Gut Nutrition</title>
<meta name="description" content="GutConference Online hosts admin-managed gut health, microbiome, probiotics, and nutrition conferences for clinical and integrative practitioners.">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<link rel="canonical" href="<?= e($currentUrl) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="GutConference Online">
<meta property="og:title" content="GutConference Online">
<meta property="og:description" content="Professional online conferences for microbiome, probiotics, and gut nutrition education.">
<meta property="og:url" content="<?= e($currentUrl) ?>">
<meta property="og:image" content="<?= e($baseUrl) ?>/assets/images/media/gutconference-logo.jpg">
<meta property="og:locale" content="en_IN">
<style>
:root{--ink:#102235;--muted:#5c7081;--line:#d8e9e7;--bg:#f5fbfb;--card:#fff;--green:#62b43f;--teal:#34bda7;--blue:#087fc0;--navy:#073763;--aqua:#e8fbf6;--shadow:0 18px 52px rgba(7,55,99,.13);--r:8px;--max:1200px}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:var(--bg);color:var(--ink);line-height:1.6}
a{color:var(--blue);text-decoration:none}
img{max-width:100%;display:block}
.site-header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.96);border-bottom:1px solid var(--line);backdrop-filter:blur(18px)}
.nav{max-width:var(--max);margin:auto;padding:10px 22px;display:grid;grid-template-columns:auto 1fr auto;gap:24px;align-items:center}
.brand{display:flex;align-items:center;gap:12px;color:var(--ink);font-weight:800}
.brand img{width:64px;height:44px;object-fit:contain}
.brand span{font-size:15px;line-height:1.15}
.nav-links{display:flex;gap:22px;justify-content:center;font-size:14px;font-weight:650}
.nav-links a{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:8px;padding:13px 20px;font-weight:900;line-height:1.2;cursor:pointer}
.btn-primary{background:linear-gradient(135deg,var(--green),var(--teal),var(--blue));color:#fff;box-shadow:0 13px 28px rgba(8,127,192,.22)}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 16px 34px rgba(8,127,192,.3)}
.btn-outline{background:#fff;border:1px solid var(--line);color:var(--ink)}
.container{max-width:var(--max);margin:auto;padding:0 22px}
.section{padding:68px 0}
.section-tight{padding:42px 0}
.hero{position:relative;background:radial-gradient(circle at 78% 20%,rgba(52,189,167,.18),transparent 34%),linear-gradient(120deg,#fff 0%,#edf9f3 50%,#e8f5ff 100%);border-bottom:1px solid var(--line)}
.hero::after{content:"";position:absolute;left:0;right:0;bottom:0;height:5px;background:linear-gradient(90deg,var(--green),var(--teal),var(--blue))}
.hero-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(340px,.95fr);gap:52px;align-items:center;padding:62px 22px 54px}
.eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:.12em;font-weight:900;color:var(--blue);margin:0 0 12px}
h1{font-size:clamp(38px,5.6vw,68px);line-height:1.02;margin:0 0 18px;letter-spacing:0}
h2{font-size:clamp(25px,3vw,38px);line-height:1.12;margin:0 0 12px;letter-spacing:0}
h3{margin:0 0 8px}
.lede{font-size:18px;color:var(--muted);max-width:720px}
.hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin:26px 0}
.stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-top:24px}
.stat{background:rgba(255,255,255,.75);border:1px solid var(--line);border-radius:var(--r);padding:14px}
.stat strong{display:block;color:var(--navy);font-size:18px}.stat span{font-size:12px;color:var(--muted);font-weight:700}
.hero-media{background:#fff;border:1px solid var(--line);border-radius:8px;padding:30px;box-shadow:var(--shadow);position:relative}
.hero-media::before{content:"Official event identity";position:absolute;top:12px;right:12px;border:1px solid var(--line);border-radius:999px;background:#fff;padding:5px 9px;color:var(--muted);font-size:11px;font-weight:900}
.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
.grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
.card{background:var(--card);border:1px solid var(--line);border-radius:8px;padding:22px;box-shadow:0 8px 24px rgba(7,55,99,.06)}
.card:hover{box-shadow:0 14px 34px rgba(7,55,99,.1);border-color:#bfe4df}
.pill{display:inline-flex;border:1px solid var(--line);border-radius:999px;padding:6px 10px;background:#fff;color:var(--muted);font-size:12px;font-weight:800}
.timeline{display:grid;gap:12px}
.timeline-row{display:grid;grid-template-columns:170px 1fr;gap:16px;align-items:start;background:#fff;border:1px solid var(--line);border-radius:8px;padding:16px}
.time{font-weight:900;color:var(--blue)}
.speaker-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
.speaker-card{background:#fff;border:1px solid var(--line);border-radius:8px;padding:18px}
.speaker-card .country{color:var(--teal);font-weight:900;font-size:12px;text-transform:uppercase;letter-spacing:.08em}
.cta-band{background:linear-gradient(135deg,#102235,#073763);color:#fff}.cta-band .lede{color:rgba(255,255,255,.78)}
.trust-strip{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;margin-top:18px}
.trust-item{background:rgba(255,255,255,.78);border:1px solid var(--line);border-radius:8px;padding:12px;font-size:13px;font-weight:900;color:var(--navy)}
.section-nav{position:sticky;top:66px;z-index:35;background:rgba(255,255,255,.94);border-bottom:1px solid var(--line);backdrop-filter:blur(14px)}
.section-nav .container{display:flex;gap:10px;overflow:auto;padding-top:10px;padding-bottom:10px}
.section-nav a{white-space:nowrap;border:1px solid var(--line);border-radius:999px;padding:8px 12px;background:#fff;color:var(--ink);font-size:13px;font-weight:800}
.sticky-register{position:fixed;left:0;right:0;bottom:0;z-index:60;background:rgba(255,255,255,.96);border-top:1px solid var(--line);box-shadow:0 -12px 36px rgba(7,55,99,.11);backdrop-filter:blur(16px)}
.sticky-register .container{display:flex;justify-content:space-between;align-items:center;gap:14px;padding-top:10px;padding-bottom:10px}
.sticky-register strong{display:block}.sticky-register span{color:var(--muted);font-size:13px}
body{padding-bottom:72px}
.footer{background:#061d2b;color:rgba(255,255,255,.7);padding:34px 0;font-size:14px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:24px}
.flash{max-width:var(--max);margin:18px auto;padding:12px 18px;background:#e8f6ff;border:1px solid #bfe6ff;border-radius:8px;color:#06496f}
input,textarea,select{width:100%;border:1px solid var(--line);border-radius:8px;padding:12px;font:inherit;background:#fff}
label{display:grid;gap:6px;font-size:13px;font-weight:800;color:var(--muted)}
@media(max-width:820px){.nav{grid-template-columns:1fr;gap:12px}.brand img{width:58px}.nav-links{justify-content:flex-start;overflow-x:auto;padding-bottom:4px}.nav>.btn{width:100%}.hero-grid,.grid,.grid-2,.footer-grid{grid-template-columns:1fr}.hero-grid{padding-top:36px}.stats{grid-template-columns:repeat(2,1fr)}.trust-strip{grid-template-columns:1fr 1fr}.timeline-row{grid-template-columns:1fr}.hero-actions .btn{width:100%}.section-nav{top:0}.sticky-register .container{align-items:stretch;flex-direction:column}.sticky-register .btn{width:100%}body{padding-bottom:132px}}
</style>
</head>
<body>
<header class="site-header">
    <div class="nav">
        <a href="/" class="brand"><img src="/assets/images/media/gutconference-logo.png" alt="GutConference logo"><span>GutConference<br>Online</span></a>
        <nav class="nav-links">
            <a href="/">Home</a>
            <a href="/events">Events</a>
            <a href="/about">About</a>
            <a href="/contact">Contact</a>
            <?php if(!empty($_SESSION['user'])): ?><a href="/logout">Logout</a><?php else: ?><a href="/login">Admin Login</a><?php endif; ?>
        </nav>
        <a class="btn btn-primary" href="/events/global-gut-summit-2026">Register</a>
    </div>
</header>
<?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<main><?php require $viewFile; ?></main>
<footer class="footer">
    <div class="container footer-grid">
        <div><strong>GutConference Online</strong><p>Admin-managed clinical conference pages for gut health, microbiome, probiotics, and nutrition education.</p></div>
        <div><strong>Navigate</strong><p><a href="/events">Events</a><br><a href="/contact">Contact</a><br><a href="/admin">Admin</a></p></div>
        <div><strong>Contact</strong><p>gutconference2026@gmail.com<br>+91 97314 82585</p></div>
    </div>
</footer>
</body>
</html>
