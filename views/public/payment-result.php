<?php
$outcome = $outcome ?? 'success';
$event = $event ?? [];
$registration = $registration ?? [];
$orderId = $orderId ?? '';
$paymentId = $paymentId ?? '';
$reason = $reason ?? '';
$isSuccess = $outcome === 'success';

$reasonCopy = [
    'missing' => 'The payment response from Razorpay did not include every field we need. No money should have been debited. If it was, contact the conference team with the order id below.',
    'signature' => 'The payment signature did not match. Razorpay will only debit a payment that the gateway itself confirms, so this most often means the popup was closed before completion or a network error interrupted the handshake. If money was debited, contact the conference team with the order id below.',
    'not_found' => 'We could not match this payment to a registration record. Please share the order id with the conference team and they will reconcile it manually.',
    'closed' => 'The Razorpay checkout popup was closed before payment completed. You can retry from the event page using the same email.',
    'unknown' => 'Something went wrong while confirming the payment. If money was debited, it will be refunded automatically by Razorpay, or you can contact the conference team with the order id below.',
];
$reasonText = $reasonCopy[$reason] ?? $reasonCopy['unknown'];

$statusLabel = $registration['payment_status'] ?? ($isSuccess ? 'paid' : 'failed');
$eventName = $event['name'] ?? 'this event';
$eventSlug = $event['slug'] ?? '';
$eventDate = $event['date_label'] ?? '';
$eventVenue = $event['venue']['name'] ?? '';
?>
<section class="section payment-result">
    <div class="container">
        <div class="payment-result__card" data-outcome="<?= e($outcome) ?>">
            <p class="eyebrow"><?= $isSuccess ? 'Payment Confirmed' : 'Payment Could Not Complete' ?></p>
            <h1><?= $isSuccess ? 'You are registered for ' : 'We could not finish your registration for ' ?><?= e($eventName) ?>.</h1>
            <p class="lede">
                <?php if ($isSuccess): ?>
                    Your seat is held. A confirmation email and event reminders are on the way.
                <?php else: ?>
                    <?= e($reasonText) ?>
                <?php endif; ?>
            </p>

            <dl class="payment-result__meta">
                <div>
                    <dt>Status</dt>
                    <dd><span class="badge badge--<?= e($statusLabel) ?>"><?= e(ucfirst($statusLabel)) ?></span></dd>
                </div>
                <?php if ($eventDate !== ''): ?>
                    <div>
                        <dt>Event date</dt>
                        <dd><?= e($eventDate) ?></dd>
                    </div>
                <?php endif; ?>
                <?php if ($eventVenue !== ''): ?>
                    <div>
                        <dt>Venue</dt>
                        <dd><?= e($eventVenue) ?></dd>
                    </div>
                <?php endif; ?>
                <div>
                    <dt>Order id</dt>
                    <dd><code><?= e($orderId !== '' ? $orderId : '—') ?></code></dd>
                </div>
                <div>
                    <dt>Payment id</dt>
                    <dd><code><?= e($paymentId !== '' ? $paymentId : '—') ?></code></dd>
                </div>
                <div>
                    <dt>Registration id</dt>
                    <dd><code><?= e((string)($registration['id'] ?? '—')) ?></code></dd>
                </div>
            </dl>

            <div class="payment-result__actions">
                <?php if ($isSuccess): ?>
                    <a class="btn btn--primary" href="/dashboard">Go to dashboard</a>
                    <?php if ($eventSlug !== ''): ?>
                        <a class="btn btn--ghost" href="/events/<?= e($eventSlug) ?>">View event</a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($eventSlug !== ''): ?>
                        <a class="btn btn--primary" href="/events/<?= e($eventSlug) ?>#registration">Try payment again</a>
                    <?php endif; ?>
                    <a class="btn btn--ghost" href="/contact">Contact support</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<style>
    .payment-result__card {
        max-width: 720px;
        margin: 0 auto;
        background: var(--card, #fff);
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 16px;
        padding: 36px 32px;
        box-shadow: 0 18px 40px -28px rgba(15, 23, 42, 0.18);
    }
    .payment-result__card[data-outcome="failed"] {
        border-color: rgba(220, 38, 38, 0.32);
        background: linear-gradient(180deg, rgba(254, 226, 226, 0.32), var(--card, #fff) 60%);
    }
    .payment-result__card .lede {
        color: var(--muted, #475569);
        font-size: 16px;
        line-height: 1.55;
        margin: 0 0 20px;
    }
    .payment-result__meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px 24px;
        margin: 0 0 24px;
    }
    .payment-result__meta div {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .payment-result__meta dt {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted, #64748b);
    }
    .payment-result__meta dd {
        margin: 0;
        font-weight: 600;
        color: var(--ink, #0f172a);
    }
    .payment-result__meta code {
        font-size: 13px;
        background: rgba(15, 23, 42, 0.05);
        padding: 2px 6px;
        border-radius: 6px;
        word-break: break-all;
    }
    .payment-result__actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .badge--paid { background: rgba(16, 185, 129, 0.16); color: #047857; padding: 2px 10px; border-radius: 999px; font-size: 12px; }
    .badge--failed { background: rgba(220, 38, 38, 0.14); color: #b91c1c; padding: 2px 10px; border-radius: 999px; font-size: 12px; }
    .badge--pending { background: rgba(234, 179, 8, 0.18); color: #92400e; padding: 2px 10px; border-radius: 999px; font-size: 12px; }
    .badge--manual { background: rgba(59, 130, 246, 0.16); color: #1d4ed8; padding: 2px 10px; border-radius: 999px; font-size: 12px; }
    .btn--ghost { background: transparent; color: var(--ink, #0f172a); border: 1px solid var(--line, #e5e7eb); }
</style>
