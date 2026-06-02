<?php $event = $featuredEvent ?? null; ?>
<?php
$ownerName = $settings['product_owner_name'] ?? 'Dr. Praveen Jacob';
$ownerTitle = $settings['product_owner_title'] ?? 'Integrating Traditional Medicine with Modern Research.';
$ownerHandle = $settings['product_owner_handle'] ?? '@the.gut.expert';
$ownerInstagram = $settings['product_owner_instagram'] ?? 'https://www.instagram.com/the.gut.expert';
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
$pressLinks = [
    [
        'source' => 'Indian Businessline',
        'date' => 'July 31, 2024',
        'title' => 'International Gut Health Expert Brings 25 Plus years of expertise to the community life at nature',
        'url' => 'https://indianbusinessline.com/index.php/2024/07/31/international-gut-health-expert-brings-25-plus-years-of-expertise-to-the-community-life-at-nature/',
        'summary' => 'Profile coverage on gut health, prebiotics, herbal remedies, and holistic clinical practice.',
    ],
    [
        'source' => 'Life at Nature',
        'date' => 'Reference',
        'title' => 'Natural medicine and community health reference',
        'url' => 'https://www.lifeatnature.com',
        'summary' => 'External reference mentioned by the press article for natural medicine community context.',
    ],
];
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">GutConference</p>
            <h1><?= e($event['headline'] ?? 'Microbiome, Probiotics & Gut Nutrition') ?></h1>
            <p class="lede"><?= e($event['description'] ?? 'Professional online conferences for clinical gut-health education.') ?></p>
            <div class="hero-actions">
                <a class="link-arrow link-arrow-primary" href="<?= $event ? '/events/' . e($event['slug']) : '/events' ?>"><?= e($event['cta_label'] ?? 'View Current Conference') ?> <span aria-hidden="true">→</span></a>
                <a class="link-arrow" href="/contact">Talk to the Team <span aria-hidden="true">→</span></a>
            </div>
            <?php if($event): ?>
                <div class="stats">
                    <div class="stat"><strong><?= e($event['date_label']) ?></strong><span>Date</span></div>
                    <div class="stat"><strong><?= e($eventService->timeRange($event)) ?></strong><span>Schedule</span></div>
                    <div class="stat"><strong><?= e($event['mode']) ?></strong><span>Mode</span></div>
                    <div class="stat"><strong><?= e($eventService->shortSlotSummary($event)) ?></strong><span>Slots</span></div>
                </div>
                <div class="trust-strip">
                    <div class="trust-item">3rd international edition</div>
                    <div class="trust-item">E-certificate</div>
                    <div class="trust-item">8 expert sessions</div>
                    <div class="trust-item">Online access</div>
                    <div class="trust-item"><?= e($event['organizers'] ?? 'Conference Team') ?></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-media">
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

<section class="section impact-section">
    <div class="container">
        <p class="eyebrow">How This Helps</p>
        <h2>Not just a schedule. A clinical learning path.</h2>
        <p class="lede">Every section explains what attendees gain: clinical clarity, speaker context, real agenda timing, and a clear next step for registration or support.</p>
        <div class="impact-grid">
            <article class="impact-card">
                <span class="impact-icon">01</span>
                <h3>Research to practice</h3>
                <p>Connect microbiome research with practical patient conversations, protocol choices, and diet/lifestyle decisions.</p>
            </article>
            <article class="impact-card">
                <span class="impact-icon">02</span>
                <h3>Speaker-led clarity</h3>
                <p>Each session is mapped by time, speaker, country, and topic so visitors understand why the program matters.</p>
            </article>
            <article class="impact-card">
                <span class="impact-icon">03</span>
                <h3>Admin-owned events</h3>
                <p>New events, venues, speakers, agenda slots, payment links, and notifications stay editable from the CMS.</p>
            </article>
        </div>
    </div>
</section>

<section class="section owner-section">
    <div class="container owner-grid">
        <div>
            <p class="eyebrow">Product Owner</p>
            <h2><?= e($ownerName) ?></h2>
            <p class="lede"><?= e($ownerTitle) ?></p>
            <p class="owner-specialty">Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
            <a class="link-arrow" href="<?= e($ownerInstagram) ?>" target="_blank" rel="noopener">View Instagram <span aria-hidden="true">→</span></a>
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
        <h2>Shorts from <?= e($ownerHandle) ?></h2>
        <p class="lede">A continuous right-to-left reel loop using only the Instagram videos supplied for this project.</p>
    </div>
    <div class="reel-marquee" aria-label="Instagram reels from The Gut Expert">
        <div class="reel-track">
            <?php for($loop = 0; $loop < 2; $loop++): ?>
                <?php foreach($reels as $index => $reelUrl): ?>
                    <a class="reel-card" href="<?= e($reelUrl) ?>" target="_blank" rel="noopener" aria-label="Open Instagram reel <?= e((string)($index + 1)) ?>">
                        <span class="reel-phone-top"></span>
                        <span class="reel-brand"><?= e($ownerHandle) ?></span>
                        <strong>Clinical Reel <?= e(str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT)) ?></strong>
                        <span class="reel-play" aria-hidden="true">▶</span>
                        <small>Open on Instagram</small>
                    </a>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="section press-section">
    <div class="container">
        <p class="eyebrow">Press &amp; References</p>
        <h2>Public coverage and clinical positioning.</h2>
        <p class="lede">The public profile content from the screenshots is converted into a cleaner website section with outbound links instead of duplicating long press-release text.</p>
        <div class="press-grid">
            <article class="press-feature">
                <span>25+ years</span>
                <h3>Integrative gut-health expertise</h3>
                <p>Coverage describes Dr. Praveen Jacob as a naturopathy doctor focused on gut health, prebiotics, herbal remedies, and non-invasive care.</p>
                <a class="link-arrow" href="<?= e($pressLinks[0]['url']) ?>" target="_blank" rel="noopener">Read source article <span aria-hidden="true">→</span></a>
            </article>
            <div class="press-list">
                <?php foreach($pressLinks as $press): ?>
                    <article class="press-card">
                        <span><?= e($press['source']) ?> · <?= e($press['date']) ?></span>
                        <h3><?= e($press['title']) ?></h3>
                        <p><?= e($press['summary']) ?></p>
                        <a class="link-arrow" href="<?= e($press['url']) ?>" target="_blank" rel="noopener">Open link <span aria-hidden="true">→</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="section cta-band">
    <div class="container grid-2" style="align-items:center">
        <div>
            <p class="eyebrow" style="color:var(--teal)">Featured Conference</p>
            <h2 style="margin: 0; color: #fff; background: none; -webkit-text-fill-color: initial; font-size: 28px;"><?= e($event['name'] ?? 'The Global Gut Summit 2026') ?></h2>
            <p class="lede" style="margin: 10px 0 0; font-size: 16px;"><?= e($event['subheadline'] ?? 'Bridging Science & Clinical Healing') ?></p>
        </div>
        <div style="text-align:right">
            <a class="link-arrow link-arrow-invert" href="<?= $event ? '/events/' . e($event['slug']) : '/events' ?>"><?= e($event['cta_label'] ?? 'Register') ?> <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>
