<div class="container">
    <div class="auth-page">
        <div class="auth-visual">
            <h2>Secure Your Account</h2>
            <p>Set a strong password to protect conference events, registrations, payment settings, and notifications.</p>
        </div>
        <div class="auth-form-container">
            <div class="auth-card">
                <h1>Reset Password</h1>
                <p>Create a new secure password</p>
                <form method="post" action="/reset-password" class="auth-form">
                    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
                    <div class="form-group">
                        <label>New Password
                            <input type="password" name="password" required placeholder="••••••••" minlength="6">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password
                            <input type="password" name="password_confirm" required placeholder="••••••••" minlength="6">
                        </label>
                    </div>
                    <button class="btn btn-primary btn-block" style="margin-top: 10px;">Update Password</button>
                </form>
                <div class="auth-footer">
                    <p>Still having trouble? <a href="/contact">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
