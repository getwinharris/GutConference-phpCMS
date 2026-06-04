<?php $event = $featuredEvent ?? null; ?>
<?php
$ownerName = $settings['product_owner_name'] ?? 'Dr. Praveen Jacob';
$ownerTitle = $settings['product_owner_title'] ?? 'Integrating Traditional Medicine with Modern Research.';
$ownerHandle = $settings['product_owner_handle'] ?? '@the.gut.expert';
$ownerInstagram = $settings['product_owner_instagram'] ?? 'https://www.instagram.com/the.gut.expert/?hl=en';
$ownerLinkedin = $settings['product_owner_linkedin'] ?? 'https://www.linkedin.com/in/dr-praveen-jacob-61b350341/';
$ownerYoutube = $settings['product_owner_youtube'] ?? 'https://www.youtube.com/channel/UC0UAYYxQETPP6KJcCTHgP7w';
$ownerFacebook = $settings['product_owner_facebook'] ?? 'https://www.facebook.com/people/Dr-Praveen-Jacob/100063556307522/';
$ownerProfileUrl = $settings['product_owner_profile_url'] ?? 'https://nisargahospital.in/doctors/dr-praveen-jacob/';
$ownerPortrait = '/assets/images/media/dr-praveen-jacob-portrait.jpg';
$socialLinks = [
    ['label' => 'Instagram', 'url' => $ownerInstagram],
    ['label' => 'LinkedIn', 'url' => $ownerLinkedin],
    ['label' => 'YouTube', 'url' => $ownerYoutube],
    ['label' => 'Facebook', 'url' => $ownerFacebook],
    ['label' => 'Clinical Profile', 'url' => $ownerProfileUrl],
];
$reels = [
    'https://www.instagram.com/reel/DM-IqIwy8by/',
    'https://www.instagram.com/reel/DI3hTnFy3ne/',
    'https://www.instagram.com/reel/DIs6wcfyTnd/',
    'https://www.instagram.com/reel/DIk7R4LpfoY/',
    'https://www.instagram.com/reel/DIQWo3YpmLe/',
    'https://www.instagram.com/reel/DGLDmECyTq1/',
    'https://www.instagram.com/reel/DF9UKJXSBAT/',
    'https://www.instagram.com/reel/DFw72dKSpKU/',
    'https://www.instagram.com/reel/DFo4mx-ywG0/',
    'https://www.instagram.com/reel/DFhQoOBSxrS/',
    'https://www.instagram.com/reel/DFExbBsS7qs/',
    'https://www.instagram.com/reel/DDZd4haokar/',
    'https://www.instagram.com/reel/DDW45NwNc47/',
    'https://www.instagram.com/reel/DDMlFiXsSK_/',
    'https://www.instagram.com/reel/DDHcw4PKxOc/',
    'https://www.instagram.com/reel/DDcCnonowwb/',
    'https://www.instagram.com/reel/DJQjxnUyjOC/',
];
$publisherLinks = array_values(array_filter($publishers ?? [], fn($publisher) => (bool)($publisher['enabled'] ?? true)));
usort($publisherLinks, fn($a, $b) => (float)($a['sort_order'] ?? 0) <=> (float)($b['sort_order'] ?? 0));
$sourceHost = static function (string $url): string {
    $host = parse_url($url, PHP_URL_HOST) ?: $url;
    return preg_replace('/^www\./', '', $host) ?: $url;
};
$sourceLogo = static function (array $publisher) use ($sourceHost): string {
    if (!empty($publisher['logo_url'])) {
        return (string)$publisher['logo_url'];
    }
    $url = (string)($publisher['url'] ?? '');
    return 'https://www.google.com/s2/favicons?domain_url=' . rawurlencode($url ?: $sourceHost((string)($publisher['source'] ?? ''))) . '&sz=64';
};
?>
<section class="hero">
    <div class="container hero-grid hero-grid-profile">
        <div class="hero-media profile-portrait-panel">
            <?php if(is_file(app_path('assets/images/media/dr-praveen-jacob-portrait.jpg'))): ?>
                <img class="profile-portrait" src="<?= e($ownerPortrait) ?>" alt="Dr. Praveen Jacob professional portrait">
            <?php else: ?>
                <div class="portrait-pending" aria-label="Dr. Praveen Jacob portrait pending">
                    <strong>Dr. Praveen Jacob</strong>
                    <span>Professional portrait</span>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <p class="eyebrow">Dr. Praveen Jacob</p>
            <h1>Integrative gut-health education, consultation, and clinical events.</h1>
            <p class="lede"><?= e($ownerTitle) ?> Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
        </div>
        <div class="hero-actions hero-actions-center">
            <a class="link-arrow link-arrow-primary" href="/contact?subject=Online%20Consultation">Book Online Consultation <span aria-hidden="true">→</span></a>
            <a class="link-arrow" href="/events">View Events &amp; Classes <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

<section class="section owner-section">
    <div class="container owner-grid">
        <div>
            <p class="eyebrow">Clinical Profile</p>
            <h2><?= e($ownerName) ?></h2>
            <p class="lede"><?= e($ownerTitle) ?></p>
            <p class="owner-specialty">Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
            <div class="social-row">
                <?php foreach($socialLinks as $social): ?>
                    <a href="<?= e($social['url']) ?>" target="_blank" rel="noopener"><?= e($social['label']) ?></a>
                <?php endforeach; ?>
            </div>
            <a class="link-arrow" href="/contact">Book Online Consultation <span aria-hidden="true">→</span></a>
        </div>
        <div class="owner-card">
            <img src="/assets/images/media/gutconference-mark.png" alt="GutConference mark">
            <div>
                <strong><?= e($ownerName) ?></strong>
                <span><?= e($ownerHandle) ?></span>
                <p>Integrative gut health education connecting traditional medicine, clinical experience, and modern microbiome research.</p>
            </div>
        </div>
    </div>
</section>

<section class="section reels-section">
    <div class="container reels-heading">
        <p class="eyebrow">Gut Expert Insights</p>
        <h2>Shorts from <span class="social-handle"><?= e(strtolower($ownerHandle)) ?></span></h2>
        <p class="lede">Swipe through selected clinical reels, open one in the center, and watch it without leaving the page.</p>
    </div>
    <div class="reel-showcase" data-reel-carousel>
        <button class="reel-nav reel-nav-prev" type="button" data-reel-prev aria-label="Previous reel">‹</button>
        <div class="reel-stage" aria-label="Instagram reels from The Gut Expert">
            <?php foreach($reels as $index => $reelUrl): ?>
                <?php
                    $reelPath = trim((string)(parse_url($reelUrl, PHP_URL_PATH) ?? ''), '/');
                    $reelParts = array_values(array_filter(explode('/', $reelPath)));
                    $reelCode = $reelParts[1] ?? $reelParts[0] ?? '';
                    $embedUrl = $reelCode !== '' ? 'https://www.instagram.com/reel/' . $reelCode . '/embed' : $reelUrl;
                    $localThumbnail = $reelCode !== '' ? '/assets/images/reels/' . $reelCode . '.jpg' : '';
                    $thumbnailUrl = $localThumbnail !== '' && is_file(app_path(ltrim($localThumbnail, '/')))
                        ? $localThumbnail
                        : ($reelCode !== '' ? 'https://www.instagram.com/p/' . $reelCode . '/media/?size=l' : '');
                ?>
                <button
                    class="reel-card"
                    type="button"
                    data-reel-card
                    data-reel-index="<?= e((string)$index) ?>"
                    data-reel-url="<?= e($reelUrl) ?>"
                    data-reel-embed="<?= e($embedUrl) ?>"
                    aria-label="Play Instagram reel <?= e((string)($index + 1)) ?>"
                >
                    <?php if($thumbnailUrl !== ''): ?>
                        <img class="reel-thumb" src="<?= e($thumbnailUrl) ?>" alt="" loading="lazy">
                    <?php endif; ?>
                    <span class="reel-phone-top"></span>
                    <span class="reel-play" aria-hidden="true">▶</span>
                </button>
            <?php endforeach; ?>
        </div>
        <button class="reel-nav reel-nav-next" type="button" data-reel-next aria-label="Next reel">›</button>
        <div class="reel-dots" aria-label="Choose reel">
            <?php foreach($reels as $index => $reelUrl): ?>
                <button type="button" data-reel-dot="<?= e((string)$index) ?>" aria-label="Show reel <?= e((string)($index + 1)) ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="reel-modal" data-reel-modal aria-hidden="true">
    <button class="reel-modal__backdrop" type="button" data-reel-close aria-label="Close reel player"></button>
    <div class="reel-modal__panel" role="dialog" aria-modal="true" aria-label="Instagram reel player">
        <button class="reel-modal__close" type="button" data-reel-close aria-label="Close reel player">×</button>
        <div class="reel-modal__frame" data-reel-frame></div>
        <a class="reel-modal__source" href="#" target="_blank" rel="noopener" data-reel-source aria-hidden="true" tabindex="-1">Open reel on Instagram <span aria-hidden="true">→</span></a>
    </div>
</div>

<script>
(() => {
    const carousel = document.querySelector('[data-reel-carousel]');
    if (!carousel) return;

    const cards = [...carousel.querySelectorAll('[data-reel-card]')];
    const dots = [...carousel.querySelectorAll('[data-reel-dot]')];
    const modal = document.querySelector('[data-reel-modal]');
    const frame = modal?.querySelector('[data-reel-frame]');
    const source = modal?.querySelector('[data-reel-source]');
    let current = 0;
    let timer = null;

    const normalize = index => (index + cards.length) % cards.length;
    const render = () => {
        cards.forEach((card, index) => {
            const offset = ((index - current + cards.length + Math.floor(cards.length / 2)) % cards.length) - Math.floor(cards.length / 2);
            card.dataset.reelOffset = Math.max(-4, Math.min(4, offset)).toString();
            card.classList.toggle('is-active', index === current);
            card.setAttribute('aria-hidden', Math.abs(offset) > 3 ? 'true' : 'false');
        });
        dots.forEach((dot, index) => dot.classList.toggle('is-active', index === current));
    };

    const go = index => {
        current = normalize(index);
        render();
    };
    const stopLoop = () => {
        if (timer) window.clearInterval(timer);
        timer = null;
    };
    const startLoop = () => {
        stopLoop();
        timer = window.setInterval(() => go(current + 1), 3200);
    };

    carousel.querySelector('[data-reel-prev]')?.addEventListener('click', () => { go(current - 1); startLoop(); });
    carousel.querySelector('[data-reel-next]')?.addEventListener('click', () => { go(current + 1); startLoop(); });
    dots.forEach((dot, index) => dot.addEventListener('click', () => { go(index); startLoop(); }));

    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            if (!modal || !frame || !source) return;
            go(index);
            stopLoop();
            const embedUrl = card.dataset.reelEmbed || card.dataset.reelUrl || '';
            const reelUrl = card.dataset.reelUrl || embedUrl;
            frame.innerHTML = `<iframe src="${embedUrl}" title="Instagram reel" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>`;
            source.href = reelUrl;
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('reel-modal-open');
        });
    });

    const close = () => {
        if (!modal || !frame) return;
        modal.setAttribute('aria-hidden', 'true');
        frame.innerHTML = '';
        document.body.classList.remove('reel-modal-open');
        startLoop();
    };
    modal?.querySelectorAll('[data-reel-close]').forEach(button => button.addEventListener('click', close));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') go(current - 1);
        if (event.key === 'ArrowRight') go(current + 1);
    });

    render();
    startLoop();
})();
</script>

<section class="section press-section">
    <div class="container">
        <p class="eyebrow">Publishers</p>
        <h2>Published references and clinical profile sources.</h2>
        <p class="lede">Selected references and public profile links help visitors verify Dr. Praveen Jacob's clinical positioning and published coverage.</p>
        <?php if(!empty($publisherLinks)): ?>
            <div class="press-list publisher-list">
                <?php foreach($publisherLinks as $publisher): ?>
                    <?php
                        $publisherUrl = (string)($publisher['url'] ?? '');
                        $publisherHost = $sourceHost($publisherUrl);
                    ?>
                    <article class="press-card publisher-card">
                        <div class="publisher-card__source">
                            <img src="<?= e($sourceLogo($publisher)) ?>" alt="<?= e($publisher['source'] ?? 'Publisher') ?> logo" loading="lazy">
                            <span><?= e($publisher['source'] ?? 'Publisher') ?><?= !empty($publisher['date_label']) ? ' · ' . e($publisher['date_label']) : '' ?></span>
                        </div>
                        <h3><?= e($publisher['title'] ?? '') ?></h3>
                        <?php if(!empty($publisher['summary'])): ?><p><?= e($publisher['summary']) ?></p><?php endif; ?>
                        <a class="link-arrow publisher-link" href="<?= e($publisherUrl) ?>" target="_blank" rel="noopener">
                            <span>Open link</span>
                            <small><?= e($publisherHost) ?></small>
                            <span aria-hidden="true">→</span>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
