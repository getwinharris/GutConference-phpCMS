<section class="section">
    <div class="container grid-2" style="align-items: center;">
        <div>
            <p class="eyebrow">Admin Login</p>
            <h1>Conference CMS Access</h1>
            <p class="lede">Sign in to manage events, page sections, speakers, agenda, venues, payment settings, and notifications.</p>
        </div>
        <form method="post" action="/login" class="card" style="display:flex; flex-direction:column; gap:18px;">
            <a class="link-arrow link-arrow-primary" href="/auth/google">Continue with Google <span aria-hidden="true">→</span></a>
            <p style="font-size: 12px; color: var(--muted);">Google login is required for customers buying events or classes so Calendar reminders can be created after payment.</p>
            <label>Email Address
                <input type="email" name="email" required placeholder="admin@gutconference.online">
            </label>
            <label>Password
                <input type="password" name="password" required placeholder="••••••••">
            </label>
            <button class="btn btn-primary" style="margin-top: 10px; align-self: flex-start;">Sign In</button>
            <a class="link-arrow" href="/signup">Create Account <span aria-hidden="true">→</span></a>
            <p style="margin-top: 10px; font-size: 13px;"><a href="/forgot-password">Forgot your password?</a></p>
            <p style="font-size: 12px; color: var(--muted);">Password login is kept for admins and legacy manual users. Manual users must connect Google before payment.</p>
        </form>
    </div>
</section>
