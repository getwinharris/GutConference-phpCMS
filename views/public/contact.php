<section class="section">
    <div class="container">
        <p class="eyebrow">Book With Dr. Praveen Jacob</p>
        <h1>Consultation and event enquiries.</h1>
        <p class="lede">Choose the path that matches the visitor intent. Consultation requests go to the doctor profile workflow; event enquiries include the selected conference or class from the admin-created event list.</p>
        <?php if(!empty($success)): ?>
            <div class="flash" style="margin: 0 0 24px;">Message received. The team will follow up.</div>
        <?php endif; ?>
    </div>
</section>

<section class="section-tight">
    <div class="container contact-choice-grid">
        <form class="contact-card" method="post" action="/contact">
            <input type="hidden" name="request_type" value="consultation">
            <p class="eyebrow">Online Consultation</p>
            <h2>Book a clinical appointment.</h2>
            <p style="color:var(--muted); margin-bottom: 18px;">For gut, skin, autoimmune, integrative medicine, and follow-up consultation enquiries.</p>
            <label>Name
                <input name="name" required placeholder="Patient name">
            </label>
            <label>Email
                <input type="email" name="email" required placeholder="patient@example.com">
            </label>
            <label>Phone
                <input name="phone" placeholder="+91 98765 43210">
            </label>
            <label>Subject
                <input name="subject" value="<?= e($subject ?: 'Online Consultation with Dr. Praveen Jacob') ?>">
            </label>
            <label>Message
                <textarea name="message" required placeholder="Share the condition, preferred timing, and whether this is a first consultation or follow-up."></textarea>
            </label>
            <button class="link-arrow link-arrow-primary" type="submit">Request Appointment <span aria-hidden="true">→</span></button>
        </form>

        <form class="contact-card" method="post" action="/contact">
            <input type="hidden" name="request_type" value="event_booking">
            <p class="eyebrow">Events &amp; Classes</p>
            <h2>Ask about an event booking.</h2>
            <p style="color:var(--muted); margin-bottom: 18px;">Use this for conference tickets, class access, payment help, certificate questions, or group bookings.</p>
            <label>Name
                <input name="name" required placeholder="Attendee name">
            </label>
            <label>Email
                <input type="email" name="email" required placeholder="attendee@example.com">
            </label>
            <label>Phone
                <input name="phone" placeholder="+91 98765 43210">
            </label>
            <label>Event
                <select name="event_slug">
                    <option value="">Select an event</option>
                    <?php foreach(($events ?? []) as $event): ?>
                        <option value="<?= e($event['slug'] ?? '') ?>"><?= e($event['name'] ?? $event['slug'] ?? 'Event') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Subject
                <input name="subject" value="<?= e($subject ?: 'Event Booking Enquiry') ?>">
            </label>
            <label>Message
                <textarea name="message" required placeholder="Mention ticket quantity, payment question, class access, or certificate requirement."></textarea>
            </label>
            <button class="link-arrow link-arrow-primary" type="submit">Send Event Enquiry <span aria-hidden="true">→</span></button>
        </form>
    </div>
</section>

<section class="section-tight">
    <div class="container contact-card contact-details-card">
        <div>
            <strong>Email</strong>
            <span>gutconference2026@gmail.com</span>
        </div>
        <div>
            <strong>Phone</strong>
            <span>+91 97314 82585</span>
        </div>
        <div>
            <strong>Domain</strong>
            <span>gutconference.online</span>
        </div>
    </div>
</section>
