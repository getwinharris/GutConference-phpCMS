<section class="section">
    <div class="container grid-2">
        <div>
            <p class="eyebrow">Contact</p>
            <h1>Talk to the Conference Team</h1>
            <p class="lede">Use this form for registration help, payment questions, speaker coordination, and conference support.</p>
            <div class="contact-card" style="display:flex; flex-direction:column; gap:16px;">
                <p><strong>📧 Email:</strong> gutconference2026@gmail.com</p>
                <p><strong>📞 Phone:</strong> +91 97314 82585</p>
                <p><strong>🌐 Domain:</strong> gutconference.online</p>
            </div>
        </div>
        <form class="contact-card" method="post" action="/contact" style="display:flex; flex-direction:column; gap:16px;">
            <?php if(!empty($success)): ?>
                <div class="flash" style="margin: 0 0 10px;">Message received. The team will follow up.</div>
            <?php endif; ?>
            <label>Name
                <input name="name" required placeholder="Dr. Jane Doe">
            </label>
            <label>Email
                <input type="email" name="email" required placeholder="jane@example.com">
            </label>
            <label>Phone
                <input name="phone" placeholder="+91 98765 43210">
            </label>
            <label>Subject
                <input name="subject" value="<?= e($subject ?? '') ?>" placeholder="Registration Support">
            </label>
            <label>Message
                <textarea name="message" required placeholder="Type your message details..."></textarea>
            </label>
            <button class="btn btn-primary" style="align-self: flex-start; margin-top: 10px;">Send Message</button>
        </form>
    </div>
</section>
