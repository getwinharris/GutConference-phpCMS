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
$ownerPortrait = '/assets/images/media/speakers/owner-dr-praveen-jacob-also-speaker.jpeg';
$ownerPhone = (string)($event['contact_phone'] ?? '+91 97314 82585');
$ownerPhoneHref = preg_replace('/[^\d+]/', '', $ownerPhone);
$socialLinks = [
    ['label' => 'Instagram', 'icon' => 'instagram', 'url' => $ownerInstagram],
    ['label' => 'LinkedIn', 'icon' => 'linkedin', 'url' => $ownerLinkedin],
    ['label' => 'YouTube', 'icon' => 'youtube', 'url' => $ownerYoutube],
    ['label' => 'Facebook', 'icon' => 'facebook', 'url' => $ownerFacebook],
];
$socialIcon = static function (string $icon): string {
    $attrs = 'width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"';
    return match ($icon) {
        'instagram' => '<svg ' . $attrs . '><rect x="4" y="4" width="16" height="16" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.4" stroke="currentColor" stroke-width="2"/><circle cx="17" cy="7" r="1.1" fill="currentColor"/></svg>',
        'linkedin' => '<svg ' . $attrs . '><path d="M6.4 10.2v7.4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M10.6 17.6v-4.1c0-2.2 1.4-3.5 3.3-3.5 1.8 0 3 1.2 3 3.5v4.1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><circle cx="6.4" cy="6.8" r="1.4" fill="currentColor"/></svg>',
        'youtube' => '<svg ' . $attrs . '><rect x="3.5" y="6.8" width="17" height="10.4" rx="3" stroke="currentColor" stroke-width="2"/><path d="M10.6 9.7v4.6l4.1-2.3-4.1-2.3Z" fill="currentColor"/></svg>',
        'facebook' => '<svg ' . $attrs . '><path d="M14.2 8h2V4.7c-.9-.1-1.8-.2-2.7-.2-2.7 0-4.5 1.6-4.5 4.6v2.6H6v3.7h3v8h3.8v-8h3l.5-3.7h-3.5V9.5c0-1 .3-1.5 1.4-1.5Z" fill="currentColor"/></svg>',
        default => '<svg ' . $attrs . '><circle cx="12" cy="8.5" r="3" stroke="currentColor" stroke-width="2"/><path d="M5.8 19c1-3.2 3.1-5 6.2-5s5.2 1.8 6.2 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.7 5.5h1.8v1.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M20.3 5.7l-3.2 3.2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
    };
};
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
        <div class="hero-copy">
            <p class="eyebrow">Dr. Praveen Jacob</p>
            <h1>Integrative gut-health education, consultation, and clinical events.</h1>
            <p class="lede"><?= e($ownerTitle) ?> Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
            <div class="hero-actions">
                <a class="link-arrow link-arrow-primary hero-primary-cta" href="/events">View Events &amp; Classes <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="hero-media profile-portrait-panel">
            <?php if(is_file(app_path(ltrim($ownerPortrait, '/')))): ?>
                <img class="profile-portrait" src="<?= e($ownerPortrait) ?>" alt="Dr. Praveen Jacob professional portrait">
            <?php else: ?>
                <div class="portrait-pending" aria-label="Dr. Praveen Jacob portrait pending">
                    <strong>Dr. Praveen Jacob</strong>
                    <span>Professional portrait</span>
                </div>
            <?php endif; ?>
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

<section class="section owner-section">
    <div class="container">
        <div class="owner-profile">
            <span class="sr-only">GutConference</span>
            <img class="owner-profile__mark owner-profile__portrait" src="<?= e($ownerPortrait) ?>" alt="Dr. Praveen Jacob profile photo">
            <p class="eyebrow">Clinical Profile</p>
            <h2><?= e($ownerName) ?></h2>
            <p class="lede"><?= e($ownerTitle) ?></p>
            <p class="owner-specialty">Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
            <div class="social-row">
                <?php foreach($socialLinks as $social): ?>
                    <a href="<?= e($social['url']) ?>" target="_blank" rel="noopener" aria-label="<?= e($social['label']) ?>" title="<?= e($social['label']) ?>">
                        <?= $socialIcon((string)$social['icon']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <a class="direct-call-link" href="tel:<?= e($ownerPhoneHref) ?>">Call Directly <span><?= e($ownerPhone) ?></span></a>
        </div>
    </div>
</section>

<section class="section press-section">
    <div class="container">
        <p class="eyebrow">Publishers</p>
        <h2>Published references and clinical profile sources.</h2>
        <p class="lede">Selected references and public profile links help visitors verify Dr. Praveen Jacob's clinical positioning and published coverage.</p>
        <?php if(!empty($publisherLinks)): ?>
            <div class="press-list publisher-list" data-publisher-rotator>
                <?php foreach($publisherLinks as $publisherIndex => $publisher): ?>
                    <?php
                        $publisherUrl = (string)($publisher['url'] ?? '');
                        $publisherHost = $sourceHost($publisherUrl);
                    ?>
                    <article class="press-card publisher-card <?= $publisherIndex === 0 ? 'is-active' : '' ?>" data-publisher-card>
                        <div class="publisher-card__source">
                            <img src="<?= e($sourceLogo($publisher)) ?>" alt="<?= e($publisher['source'] ?? 'Publisher') ?> logo" loading="lazy">
                            <span><?= e($publisher['source'] ?? 'Publisher') ?><?= !empty($publisher['date_label']) ? ' · ' . e($publisher['date_label']) : '' ?></span>
                        </div>
                        <h3><?= e($publisher['title'] ?? '') ?></h3>
                        <?php if(!empty($publisher['summary'])): ?><p><?= e($publisher['summary']) ?></p><?php endif; ?>
                        <a class="link-arrow publisher-link" href="<?= e($publisherUrl) ?>" target="_blank" rel="noopener" aria-label="Read article on <?= e($publisherHost) ?>">
                            <span>Read article by</span>
                            <small><?= e($publisherHost) ?></small>
                            <span aria-hidden="true">→</span>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(() => {
    const rotator = document.querySelector('[data-publisher-rotator]');
    if (!rotator) return;
    const cards = [...rotator.querySelectorAll('[data-publisher-card]')];
    if (cards.length <= 1) return;
    let current = 0;
    const render = () => {
        cards.forEach((card, index) => {
            card.classList.toggle('is-active', index === current);
            card.setAttribute('aria-hidden', index === current ? 'false' : 'true');
        });
    };
    render();
    window.setInterval(() => {
        current = (current + 1) % cards.length;
        render();
    }, 4200);
})();
</script>
