<section class="section" id="checkout">
    <div class="container">
        <div class="card checkout-card">
            <p class="eyebrow">Secure Checkout</p>
            <h1><?= e($event['name'] ?? 'GutConference Event') ?></h1>
            <p class="lede">Complete the Razorpay payment to confirm your registration.</p>
            <div class="pricing-panel single-price">
                <div>
                    <span>Conference Pass</span>
                    <strong><?= e((string)($event['currency'] ?? 'INR')) ?> <?= e((string)($event['price'] ?? '')) ?></strong>
                    <small><?= e($event['date_label'] ?? '') ?></small>
                </div>
            </div>
            <button class="link-arrow link-arrow-primary" id="razorpay-pay-button" type="button">Pay with Razorpay <span aria-hidden="true">→</span></button>
            <form id="razorpay-verify-form" method="post" action="/events/<?= e($event['slug'] ?? '') ?>/payment/verify" hidden>
                <input type="hidden" name="registration_id" value="<?= e($registration['id'] ?? '') ?>">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?= e($order['id'] ?? '') ?>">
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            </form>
            <p class="form-helper">If checkout does not open, confirm Razorpay browser popups are allowed or contact <?= e($event['contact_email'] ?? 'the conference team') ?>.</p>
        </div>
    </div>
</section>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const checkoutOptions = {
    key: <?= json_encode($razorpayKeyId ?? '') ?>,
    amount: <?= json_encode((int)($order['amount'] ?? 0)) ?>,
    currency: <?= json_encode($order['currency'] ?? ($event['currency'] ?? 'INR')) ?>,
    name: 'GutConference',
    description: <?= json_encode($event['name'] ?? 'Conference pass') ?>,
    order_id: <?= json_encode($order['id'] ?? '') ?>,
    handler: function (response) {
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id || '';
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
        document.getElementById('razorpay-verify-form').submit();
    },
    prefill: {
        name: <?= json_encode($registration['name'] ?? '') ?>,
        email: <?= json_encode($registration['email'] ?? '') ?>
    },
    theme: { color: '#35b7a5' }
};
document.getElementById('razorpay-pay-button').addEventListener('click', () => {
    new Razorpay(checkoutOptions).open();
});
</script>
