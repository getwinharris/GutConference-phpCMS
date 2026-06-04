<section class="section auth-surface">
    <div class="container auth-shell">
        <div class="auth-copy auth-visual">
            <h2>Secure Your Account</h2>
            <p>Set a strong password to protect conference events, registrations, payment settings, and notifications.</p>
        </div>
        <form method="post" action="/reset-password" class="form-card form-stack">
            <h1>Reset Password</h1>
            <p class="form-helper">Create a new secure password.</p>
            <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
            <label>New Password
                <input type="password" name="password" required placeholder="••••••••" minlength="6">
            </label>
            <label>Confirm Password
                <input type="password" name="password_confirm" required placeholder="••••••••" minlength="6">
            </label>
            <button class="btn btn-primary btn-block">Update Password</button>
            <p class="form-footer">Still having trouble? <a href="/contact">Contact Support</a></p>
        </form>
    </div>
</section>
