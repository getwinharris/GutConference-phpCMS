<div class="container">
    <div class="auth-page">
        <div class="auth-visual">
            <h2>Recover Access</h2>
            <p>Enter your email address and we will send you a secure link to reset your admin password.</p>
        </div>
        <div class="auth-form-container">
            <div class="auth-card">
                <h1>Forgot Password</h1>
                <p>Secure password recovery</p>
                <form method="post" action="/forgot-password" class="auth-form">
                    <div class="form-group">
                        <label>Email Address
                            <input type="email" name="email" required placeholder="admin@gutconference.online">
                        </label>
                    </div>
                    <button class="btn btn-primary btn-block" style="margin-top: 10px;">Send Reset Link</button>
                </form>
                <div class="auth-footer">
                    <p>Remembered your password? <a href="/login">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
