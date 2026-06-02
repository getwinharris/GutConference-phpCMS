<section class="section">
    <div class="container grid-2">
        <div>
            <p class="eyebrow">Admin Login</p>
            <h1>Conference CMS Access</h1>
            <p class="lede">Sign in to manage events, page sections, speakers, agenda, venues, payment settings, and notifications.</p>
        </div>
        <form method="post" action="/login" class="card">
            <label>Email Address<input type="email" name="email" required placeholder="admin@gutconference.online"></label><br>
            <label>Password<input type="password" name="password" required></label><br>
            <button class="btn btn-primary">Sign In</button>
            <p><a href="/forgot-password">Forgot your password?</a></p>
        </form>
    </div>
</section>
