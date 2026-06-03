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
$pressLinks = [
    [
        'source' => 'First India',
        'date' => 'July 31, 2024',
        'title' => 'International Gut Health Expert Brings 25+ Years of Expertise to the Community Life at Nature',
        'url' => 'https://firstindia.co.in/news/press-releases/international-gut-health-expert-brings-25-years-of-expertise-to-the-community-life-at-nature',
        'summary' => 'Profile coverage on gut health, prebiotics, herbal remedies, and holistic clinical practice.',
    ],
    [
        'source' => 'Nisarga Hospital',
        'date' => 'Professional profile',
        'title' => 'Dr. Praveen Jacob professional profile',
        'url' => $ownerProfileUrl,
        'summary' => 'Clinic profile reference for appointments, professional positioning, and patient-facing context.',
    ],
];
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">Dr. Praveen Jacob</p>
            <h1>Integrative gut-health education, consultation, and clinical events.</h1>
            <p class="lede"><?= e($ownerTitle) ?> Specialized in Gut, Skin &amp; Autoimmune Disorders.</p>
            <div class="hero-actions">
                <a class="link-arrow link-arrow-primary" href="/contact?subject=Online%20Consultation">Book Online Consultation <span aria-hidden="true">→</span></a>
                <a class="link-arrow" href="<?= $event ? '/events/' . e($event['slug']) : '/events' ?>">View Events &amp; Classes <span aria-hidden="true">→</span></a>
            </div>
            <?php if($event): ?>
                <div class="stats">
                    <div class="stat"><strong>25+ years</strong><span>Practice & research</span></div>
                    <div class="stat"><strong>Gut, Skin</strong><span>Autoimmune focus</span></div>
                    <div class="stat"><strong><?= e($event['date_label']) ?></strong><span>Next event</span></div>
                    <div class="stat"><strong><?= e($eventService->shortSlotSummary($event)) ?></strong><span>Event slots</span></div>
                </div>
                <div class="trust-strip">
                    <div class="trust-item">Traditional medicine</div>
                    <div class="trust-item">Modern research</div>
                    <div class="trust-item">Online consultation</div>
                    <div class="trust-item">Clinical events</div>
                    <div class="trust-item">E-certificate support</div>
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-media">
            <div class="brand-hero profile-hero-card">
                <img src="/assets/images/media/gutconference-mark.png" alt="GutConference circular mark">
                <div class="brand-hero-title">
                    <small>Portfolio Platform</small>
                    <strong><?= e($ownerName) ?></strong>
                    <span>Consultations, events, classes, and microbiome-focused education under one verified profile.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section impact-section">
    <div class="container">
        <p class="eyebrow">How This Helps</p>
        <h2>A profile-first platform with booking paths.</h2>
        <p class="lede">The website should behave like a premium professional portfolio first, then guide visitors into consultation, event booking, and future class purchases only when they are ready.</p>
        <div class="impact-grid">
            <article class="impact-card">
                <span class="impact-icon">01</span>
                <h3>Clinical authority</h3>
                <p>Lead with the doctor profile, focus areas, verified press, and hospital/profile references before asking users to buy.</p>
            </article>
            <article class="impact-card">
                <span class="impact-icon">02</span>
                <h3>Guided decisions</h3>
                <p>Use quiet luxury cards, clear hierarchy, and one primary action per section so consultation and event paths do not compete.</p>
            </article>
            <article class="impact-card">
                <span class="impact-icon">03</span>
                <h3>Admin-owned growth</h3>
                <p>Events, venues, slots, Razorpay links, WhatsApp templates, and certificates stay admin-managed as the business evolves.</p>
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
        <p class="lede">Verified profile and press links are used as trust signals without sending visitors to unrelated pages.</p>
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
