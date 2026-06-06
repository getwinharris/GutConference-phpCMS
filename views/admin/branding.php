<?php
$brandColors = [
    ['name' => 'Navy', 'token' => '--navy', 'value' => '#073a66'],
    ['name' => 'Blue', 'token' => '--blue', 'value' => '#087fc0'],
    ['name' => 'Teal', 'token' => '--teal', 'value' => '#39b9a8'],
    ['name' => 'Green', 'token' => '--green', 'value' => '#66b63f'],
    ['name' => 'Soft Mint', 'token' => '--soft', 'value' => '#edf9f6'],
    ['name' => 'Line', 'token' => '--line', 'value' => '#d8ece8'],
];
$logos = [
    ['label' => 'Primary Logo', 'path' => '/assets/images/media/gutconference-logo.png'],
    ['label' => 'Logo JPG', 'path' => '/assets/images/media/gutconference-logo.jpg'],
    ['label' => 'Brand Mark', 'path' => '/assets/images/media/gutconference-mark.png'],
];
?>
<div class="admin-card">
    <h2>Brand Identity System</h2>
    <p style="color:var(--muted)">A Figma-style reference for keeping GutConference pages, event cards, speaker cards, forms, and admin surfaces visually consistent.</p>
</div>

<div class="admin-card">
    <h2>Logo Assets</h2>
    <div class="brand-asset-grid">
        <?php foreach($logos as $logo): ?>
            <div class="brand-asset">
                <img src="<?= e($logo['path']) ?>" alt="<?= e($logo['label']) ?>">
                <strong><?= e($logo['label']) ?></strong>
                <code><?= e($logo['path']) ?></code>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="admin-card">
    <h2>Color Tokens</h2>
    <div class="brand-token-grid">
        <?php foreach($brandColors as $color): ?>
            <div class="brand-token">
                <span style="background:<?= e($color['value']) ?>"></span>
                <strong><?= e($color['name']) ?></strong>
                <code><?= e($color['token']) ?> · <?= e($color['value']) ?></code>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="admin-card">
    <h2>Typography</h2>
    <div class="brand-type-grid">
        <div>
            <span>Display</span>
            <strong style="font-family:'Barlow Condensed',Impact,sans-serif;font-size:42px;line-height:1;text-transform:uppercase;color:#073a66">Barlow Condensed</strong>
            <p style="color:var(--muted)">Used for headings, card titles, and event hero text.</p>
        </div>
        <div>
            <span>Body</span>
            <strong style="font-size:24px">Plus Jakarta Sans</strong>
            <p style="color:var(--muted)">Used for body copy, buttons, labels, forms, and navigation.</p>
        </div>
    </div>
</div>

<div class="admin-card">
    <h2>Component Rules</h2>
    <div class="brand-guideline-grid">
        <div><strong>Cards</strong><p>Use 8px radius, light borders, restrained shadows, and no nested card clutter.</p></div>
        <div><strong>Buttons</strong><p>Primary CTAs use the green-teal-blue gradient. Secondary actions stay white with navy text.</p></div>
        <div><strong>Images</strong><p>Speaker portraits are data-driven through <code>photo_url</code>. Use initials fallback only when missing.</p></div>
        <div><strong>Voice</strong><p>Clinical, premium, clear, and founder-led. Events/classes are booking options, not the whole brand.</p></div>
    </div>
</div>

<div class="admin-card">
    <h2>Tracking & Support Wiring</h2>
    <p style="color:var(--muted)">Google tag, Search Console verification, SMTP settings, and Google AI Studio support routing are configured in <a href="/admin/integrations">Integrations</a>. Server-level environment variables are external fallback only.</p>
</div>
