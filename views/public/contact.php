<section class="section">
    <div class="container grid-2">
        <div>
            <p class="eyebrow">Contact</p>
            <h1>Talk to the Conference Team</h1>
            <p class="lede">Use this form for registration help, payment questions, speaker coordination, and conference support.</p>
            <div class="card">
                <p><strong>Email:</strong> gutconference2026@gmail.com</p>
                <p><strong>Phone:</strong> +91 97314 82585</p>
                <p><strong>Domain:</strong> gutconference.online</p>
            </div>
        </div>
        <form class="card" method="post" action="/contact">
            <?php if(!empty($success)): ?><div class="flash" style="margin:0 0 16px">Message received. The team will follow up.</div><?php endif; ?>
            <label>Name<input name="name" required></label><br>
            <label>Email<input type="email" name="email" required></label><br>
            <label>Phone<input name="phone"></label><br>
            <label>Subject<input name="subject" value="<?= e($subject ?? '') ?>"></label><br>
            <label>Message<textarea name="message" required></textarea></label><br>
            <button class="btn btn-primary">Send Message</button>
        </form>
    </div>
</section>
