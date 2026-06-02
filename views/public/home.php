<?php $event = $featuredEvent ?? null; ?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">GutConference Online</p>
            <h1><?= e($event['headline'] ?? 'Microbiome, Probiotics & Gut Nutrition') ?></h1>
            <p class="lede"><?= e($event['description'] ?? 'Professional online conferences for clinical gut-health education.') ?></p>
            <div class="hero-actions">
                <a class="btn btn-cta" href="<?= $event ? '/events/' . e($event['slug']) : '/events' ?>"><?= e($event['cta_label'] ?? 'View Current Conference') ?></a>
                <a class="btn btn-outline" href="/contact">Talk to the Team</a>
            </div>
            <?php if($event): ?>
                <div class="stats">
                    <div class="stat"><strong><?= e($event['date_label']) ?></strong><span>Date</span></div>
                    <div class="stat"><strong><?= e($event['time_label']) ?></strong><span>Schedule</span></div>
                    <div class="stat"><strong><?= e($event['mode']) ?></strong><span>Mode</span></div>
                    <div class="stat"><strong>Rs.<?= e((string)$event['price']) ?></strong><span>Registration</span></div>
                </div>
                <div class="trust-strip">
                    <div class="trust-item">3rd international edition</div>
                    <div class="trust-item">E-certificate</div>
                    <div class="trust-item">8 expert sessions</div>
                    <div class="trust-item">Online access</div>
                    <div class="trust-item">Alpha Naturals & Salus Nutri</div>
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

<section class="section">
    <div class="container">
        <p class="eyebrow">Built for conversion and credibility</p>
        <h2>Conference pages that admins can run without developers</h2>
        <p class="lede">The CMS supports event slugs, editable content sections, speakers, agenda sessions, venue/map details, registration CTAs, Razorpay setup, and notification templates.</p>
        <div class="grid" style="margin-top: 30px;">
            <div class="card">
                <h3>Admin-created events</h3>
                <p style="color: var(--muted); font-size: 14px; margin-top: 8px;">Only the brand admin can create and publish conferences. Each event has its own slug URL.</p>
            </div>
            <div class="card">
                <h3>Conference Page Structure</h3>
                <p style="color: var(--muted); font-size: 14px; margin-top: 8px;">Clear CTAs, outcomes, audience, speaker trust, FAQ, and registration blocks built for a professional medical conference.</p>
            </div>
            <div class="card">
                <h3>Clinical tone</h3>
                <p style="color: var(--muted); font-size: 14px; margin-top: 8px;">The visual language stays medical and professional: focused information, credible speakers, and no fake urgency.</p>
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
            <a class="btn btn-primary" href="<?= $event ? '/events/' . e($event['slug']) : '/events' ?>"><?= e($event['cta_label'] ?? 'Register') ?></a>
        </div>
    </div>
</section>
