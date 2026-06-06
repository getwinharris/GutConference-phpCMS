<section class="section checkout-surface" id="checkout">
    <div class="checkout-backdrop" aria-hidden="true"></div>
    <div class="container checkout-shell">
        <div class="checkout-popup" role="dialog" aria-modal="true" aria-labelledby="checkout-title">
            <div class="checkout-popup__mark">
                <img src="/assets/images/media/gutconference-mark.png" alt="">
            </div>
            <p class="eyebrow">Secure Razorpay Checkout</p>
            <h1 id="checkout-title"><?= e($event['name'] ?? 'GutConference Event') ?></h1>
            <p class="lede">Your booking is ready. Complete payment in the Razorpay popup to confirm your registration.</p>

            <div class="checkout-summary">
                <div>
                    <span>Conference Pass</span>
                    <strong><?= e((string)($event['currency'] ?? 'INR')) ?> <?= e((string)($event['price'] ?? '')) ?></strong>
                </div>
                <div>
                    <span>Date</span>
                    <strong><?= e($event['date_label'] ?? '') ?></strong>
                </div>
                <div>
                    <span>Order</span>
                    <strong><?= e($order['id'] ?? '') ?></strong>
                </div>
            </div>

            <button class="link-arrow link-arrow-primary checkout-pay-button" id="razorpay-pay-button" type="button">Open Razorpay Payment <span aria-hidden="true">→</span></button>
            <form id="razorpay-verify-form" method="post" action="/events/<?= e($event['slug'] ?? '') ?>/payment/verify" hidden>
                <input type="hidden" name="registration_id" value="<?= e($registration['id'] ?? '') ?>">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?= e($order['id'] ?? '') ?>">
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            </form>

            <p class="checkout-policy">
                By continuing, you agree to the <a href="/terms" target="_blank" rel="noopener">Terms of Service</a> and <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a>. Razorpay's own <a href="https://razorpay.com/terms/" target="_blank" rel="noopener">terms</a> and <a href="https://razorpay.com/privacy-policy/" target="_blank" rel="noopener">privacy policy</a> apply to payment processing.
            </p>
            <p class="form-helper">If the payment popup does not open, allow browser popups or contact <?= e($event['contact_email'] ?? 'the conference team') ?>.</p>
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
    image: window.location.origin + '/assets/images/media/gutconference-mark.png',
    order_id: <?= json_encode($order['id'] ?? '') ?>,
    handler: function (response) {
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id || '';
        document.getElementById('razorpay_signature').value = response.razorpay_signature || '';
        document.getElementById('razorpay-verify-form').submit();
    },
    prefill: {
        name: <?= json_encode($registration['name'] ?? '') ?>,
        email: <?= json_encode($registration['email'] ?? '') ?>,
        contact: <?= json_encode($registration['phone'] ?? '') ?>
    },
    notes: {
        event_slug: <?= json_encode($event['slug'] ?? '') ?>,
        registration_id: <?= json_encode($registration['id'] ?? '') ?>
    },
    theme: {
        color: '#35b7a5',
        backdrop_color: 'rgba(2, 18, 32, 0.72)'
    },
    modal: {
        escape: true,
        backdropclose: false,
        confirm_close: true,
        ondismiss: function () {
            document.getElementById('razorpay-pay-button').focus();
        }
    }
};

function openRazorpayCheckout() {
    if (typeof Razorpay === 'undefined') return;
    new Razorpay(checkoutOptions).open();
}

document.getElementById('razorpay-pay-button').addEventListener('click', openRazorpayCheckout);
window.addEventListener('load', () => {
    window.setTimeout(openRazorpayCheckout, 350);
});
</script>
