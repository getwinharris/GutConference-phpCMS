<section class="section auth-surface">
    <div class="container auth-shell">
        <div class="auth-copy auth-visual">
            <h2>Recover Access</h2>
            <p>Enter your email address and we will send you a secure link to reset your admin password.</p>
        </div>
        <form method="post" action="/forgot-password" class="form-card form-stack">
            <h1>Forgot Password</h1>
            <p class="form-helper">Secure password recovery.</p>
            <label>Email Address
                <input type="email" name="email" required placeholder="admin@gutconference.online">
            </label>
            <button class="btn btn-primary btn-block">Send Reset Link</button>
            <p class="form-footer">Remembered your password? <a href="/login">Sign in here</a></p>
        </form>
    </div>
</section>
