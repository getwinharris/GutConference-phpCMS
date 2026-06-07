<section class="section auth-surface">
    <div class="container auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Account Login</p>
            <h1>Access your GutConference account</h1>
            <p class="lede">Sign in with Google to book consultations, join events, receive calendar reminders, and access event certificates.</p>
        </div>
        <form method="post" action="/login" class="form-card form-stack">
            <a class="google-login-button" href="/auth/google"><span class="google-mark" aria-hidden="true">G</span><span>Continue with Google</span></a>
            <p class="form-helper">Google login is required for customers buying events or classes so Calendar reminders can be created after payment.</p>
            <label>Email Address
                <input type="email" name="email" required placeholder="you@example.com">
            </label>
            <label>Password
                <input type="password" name="password" required placeholder="••••••••">
            </label>
            <label class="consent-check">
                <input type="checkbox" name="terms_accepted" value="1" required>
                <span>I agree to the <a href="/terms" target="_blank" rel="noopener">Terms of Service</a> and <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a>.</span>
            </label>
            <button class="btn btn-primary form-submit">Sign In</button>
            <a class="link-arrow" href="/signup">Create Account <span aria-hidden="true">→</span></a>
            <p class="form-footer"><a href="/forgot-password">Forgot your password?</a></p>
        </form>
    </div>
</section>
