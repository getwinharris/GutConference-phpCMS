<?php
$registrations = $registrations ?? [];
$user = $user ?? ($_SESSION['user'] ?? []);
$certificateName = (string)($user['certificate_name'] ?? $user['name'] ?? '');
$paidRegistrations = array_values(array_filter($registrations, fn($registration) => in_array(($registration['payment_status'] ?? ''), ['paid', 'manual'], true)));
$readyCertificates = array_values(array_filter($paidRegistrations, fn($registration) => !empty($registration['certificate_available'])));
?>
<section class="section dashboard-hero">
    <div class="container dashboard-shell">
        <aside class="dashboard-menu">
            <p class="eyebrow">My Account</p>
            <strong><?= e($user['name'] ?? 'Member') ?></strong>
            <span><?= e($user['email'] ?? '') ?></span>
            <nav>
                <a href="#events">Event/Course</a>
                <a href="#certificates">Certificates</a>
                <a href="/logout">Logout</a>
            </nav>
        </aside>
        <div class="dashboard-main">
            <p class="eyebrow">Dashboard</p>
            <h1>Your learning account.</h1>
            <p class="lede">Track event and course signups, payment status, and certificates after the scheduled completion time.</p>
            <div class="dashboard-stats">
                <div><strong><?= e((string)count($registrations)) ?></strong><span>Event/Course records</span></div>
                <div><strong><?= e((string)count($paidRegistrations)) ?></strong><span>Paid or confirmed</span></div>
                <div><strong><?= e((string)count($readyCertificates)) ?></strong><span>Certificates ready</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section-tight" id="events">
    <div class="container">
        <p class="eyebrow">Event/Course</p>
        <h2>Your signed-up programs.</h2>
        <?php if(empty($registrations)): ?>
            <div class="card empty-state">
                <p>No event or course registration is linked to this account yet.</p>
                <a class="link-arrow" href="/events">Browse Events &amp; Classes <span aria-hidden="true">→</span></a>
            </div>
        <?php else: ?>
            <div class="account-card-grid">
                <?php foreach($registrations as $registration): ?>
                    <?php
                    $event = $registration['event'] ?? [];
                    $paid = in_array(($registration['payment_status'] ?? ''), ['paid', 'manual'], true);
                    ?>
                    <article class="account-card">
                        <div>
                            <span class="badge"><?= e(ucwords(str_replace('_', ' ', (string)($registration['payment_status'] ?? 'pending')))) ?></span>
                            <h3><?= e($event['name'] ?? $registration['event_slug'] ?? 'Event/Course') ?></h3>
                            <p><?= e($event['date_label'] ?? 'Schedule managed by admin') ?><?= !empty($event['time_label']) ? ' · ' . e($event['time_label']) : '' ?></p>
                        </div>
                        <div class="account-meta">
                            <span>Certificate name</span>
                            <strong><?= e($registration['name'] ?? $certificateName) ?></strong>
                        </div>
                        <?php if(!$paid): ?>
                            <p class="form-helper">Payment is pending or waiting for admin confirmation from the external payment page.</p>
                        <?php else: ?>
                            <p class="form-helper">Access is confirmed. Certificate status appears below after completion.</p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section-tight" id="certificates">
    <div class="container">
        <p class="eyebrow">Certificates</p>
        <h2>Certificate status.</h2>
        <div class="account-card-grid">
            <?php if(empty($registrations)): ?>
                <article class="account-card">
                    <h3>No certificates yet.</h3>
                    <p>Certificates appear here after a paid event or course is completed.</p>
                </article>
            <?php else: ?>
                <?php foreach($registrations as $registration): ?>
                    <?php
                    $event = $registration['event'] ?? [];
                    $paid = in_array(($registration['payment_status'] ?? ''), ['paid', 'manual'], true);
                    $ready = !empty($registration['certificate_available']);
                    ?>
                    <article class="account-card">
                        <span class="badge"><?= $ready ? 'Ready' : ($paid ? 'After Completion' : 'Payment Pending') ?></span>
                        <h3><?= e($event['name'] ?? $registration['event_slug'] ?? 'Certificate') ?></h3>
                        <p><?= $ready ? 'Your certificate is ready for admin delivery/download setup.' : ($paid ? 'Certificate unlocks automatically after the scheduled event or course end time.' : 'Complete payment or wait for admin confirmation to activate certificate eligibility.') ?></p>
                        <div class="account-meta">
                            <span>Certificate name</span>
                            <strong><?= e($registration['name'] ?? $certificateName) ?></strong>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
