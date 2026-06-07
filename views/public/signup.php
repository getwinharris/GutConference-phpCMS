<section class="section auth-surface">
    <div class="container auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Create Account</p>
            <h1>Signup before buying tickets or classes.</h1>
            <p class="lede">Google OAuth is the required customer signup path so event reminders can be added to Google Calendar after payment.</p>
        </div>
        <form method="post" action="/signup" class="form-card form-stack">
            <a class="google-login-button" href="/auth/google"><span class="google-mark" aria-hidden="true">G</span><span>Continue with Google</span></a>
            <p class="form-helper">Google OAuth credentials are configured from Admin -> Integrations. Manual signup below is kept only for legacy/dev testing and must connect Google before payment.</p>
            <label>Name
                <input name="name" required placeholder="Your name">
            </label>
            <label>Name for Certificate
                <input name="certificate_name" required placeholder="Full legal name for certificates">
            </label>
            <label>Email Address
                <input type="email" name="email" required placeholder="you@example.com">
            </label>
            <label>Password
                <input type="password" name="password" required placeholder="Create password">
            </label>
            <label>Confirm Password
                <input type="password" name="password_confirm" required placeholder="Confirm password">
            </label>
            <label class="consent-check">
                <input type="checkbox" name="terms_accepted" value="1" required>
                <span>I agree to the <a href="/terms" target="_blank" rel="noopener">Terms of Service</a> and <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a>.</span>
            </label>
            <button class="btn btn-primary form-submit">Create Account</button>
            <a class="link-arrow" href="/login">Already have an account? Login <span aria-hidden="true">→</span></a>
        </form>
    </div>
</section>
