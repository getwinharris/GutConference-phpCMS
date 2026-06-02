<section class="section">
    <div class="container grid-2" style="align-items: center;">
        <div>
            <p class="eyebrow">Admin Login</p>
            <h1>Conference CMS Access</h1>
            <p class="lede">Sign in to manage events, page sections, speakers, agenda, venues, payment settings, and notifications.</p>
        </div>
        <form method="post" action="/login" class="card" style="display:flex; flex-direction:column; gap:18px;">
            <label>Email Address
                <input type="email" name="email" required placeholder="admin@gutconference.online">
            </label>
            <label>Password
                <input type="password" name="password" required placeholder="••••••••">
            </label>
            <button class="btn btn-primary" style="margin-top: 10px; align-self: flex-start;">Sign In</button>
            <p style="margin-top: 10px; font-size: 13px;"><a href="/forgot-password">Forgot your password?</a></p>
        </form>
    </div>
</section>
