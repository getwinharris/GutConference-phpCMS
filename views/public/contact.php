<section class="section">
    <div class="container">
        <p class="eyebrow">Book With Dr. Praveen Jacob</p>
        <h1>Consultation and event enquiries.</h1>
        <p class="lede">Use one enquiry form for clinical appointments, event bookings, class access, payment help, certificates, and group bookings.</p>
        <?php if(!empty($success)): ?>
            <div class="flash flash-inline">Message received. The team will follow up.</div>
        <?php endif; ?>
    </div>
</section>

<section class="section-tight">
    <div class="container contact-form-wrap">
        <form class="contact-card contact-form-card" method="post" action="/contact">
            <p class="eyebrow">Enquiry Form</p>
            <h2>Send your request.</h2>
            <p class="form-helper">Choose Clinical Appointment for patient enquiries, or Events &amp; Classes for tickets, access, payment, certificates, or group bookings.</p>
            <label>Enquiry Type
                <select name="request_type" required>
                    <option value="consultation">Clinical Appointment</option>
                    <option value="event_booking">Event or Class Listed</option>
                </select>
            </label>
            <label>Name
                <input name="name" required placeholder="Your name">
            </label>
            <label>Email
                <input type="email" name="email" required placeholder="name@example.com">
            </label>
            <label>Phone
                <input name="phone" placeholder="+91 98765 43210">
            </label>
            <label>Event or Class
                <select name="event_slug">
                    <option value="">Not related to an event</option>
                    <?php foreach(($events ?? []) as $event): ?>
                        <option value="<?= e($event['slug'] ?? '') ?>"><?= e($event['name'] ?? $event['slug'] ?? 'Event') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Subject
                <input name="subject" value="<?= e($subject ?: 'GutConference Enquiry') ?>">
            </label>
            <label>Message
                <textarea name="message" required placeholder="Share appointment details, preferred timing, ticket quantity, payment question, class access, or certificate requirement."></textarea>
            </label>
            <button class="btn btn-primary btn-block" type="submit">Send Enquiry</button>
        </form>
    </div>
</section>

<section class="section-tight">
    <div class="container contact-details-card">
        <div class="contact-detail-item">
            <small>Email</small>
            <span>gutconference2026@gmail.com</span>
        </div>
        <div class="contact-detail-item">
            <small>Phone</small>
            <span>+91 97314 82585</span>
        </div>
        <div class="contact-detail-item">
            <small>Website</small>
            <span>gutconference.online</span>
        </div>
    </div>
</section>
