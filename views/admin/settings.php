<div class="admin-card">
    <h2>Admin Login</h2>
    <p style="color:var(--muted)">These credentials are loaded from <code>.env</code>. Change them before production use.</p>
    <form class="admin-form" method="post" action="/admin/settings/admin-credentials">
        <div class="admin-form__row">
            <label>Admin Username<input type="text" name="admin_username" value="<?= e($adminCredentials['username'] ?? '') ?>" autocomplete="username" required></label>
            <label>Admin Email<input type="email" name="admin_email" value="<?= e($adminCredentials['email'] ?? '') ?>" autocomplete="email" required></label>
            <label>Admin Password<input type="password" name="admin_password" value="<?= e($adminCredentials['password'] ?? '') ?>" autocomplete="new-password" required></label>
        </div>
        <br><button class="btn btn-primary">Save Admin Login</button>
    </form>
</div>
<div class="admin-card">
    <h2>Site Settings</h2>
    <form class="admin-form" method="post" action="/admin/settings/save">
        <div class="admin-form__row">
            <label>Featured Event Slug<input name="featured_event_slug" value="<?= e($settings['featured_event_slug'] ?? 'global-gut-summit-2026') ?>"></label>
            <label>Currency<input name="currency" value="<?= e($settings['currency'] ?? 'INR') ?>"></label>
            <label>Timezone<input name="timezone" value="<?= e($settings['timezone'] ?? 'Asia/Kolkata') ?>"></label>
            <label>Product Owner Name<input name="product_owner_name" value="<?= e($settings['product_owner_name'] ?? 'Dr. Praveen Jacob') ?>"></label>
            <label>Product Owner Title<input name="product_owner_title" value="<?= e($settings['product_owner_title'] ?? 'Integrating Traditional Medicine with Modern Research.') ?>"></label>
            <label>Product Owner Handle<input name="product_owner_handle" value="<?= e($settings['product_owner_handle'] ?? '@the.gut.expert') ?>"></label>
            <label>Owner Instagram<input type="url" name="product_owner_instagram" value="<?= e($settings['product_owner_instagram'] ?? 'https://www.instagram.com/the.gut.expert/?hl=en') ?>"></label>
            <label>Owner LinkedIn<input type="url" name="product_owner_linkedin" value="<?= e($settings['product_owner_linkedin'] ?? 'https://www.linkedin.com/in/dr-praveen-jacob-61b350341/') ?>"></label>
            <label>Owner YouTube<input type="url" name="product_owner_youtube" value="<?= e($settings['product_owner_youtube'] ?? 'https://www.youtube.com/channel/UC0UAYYxQETPP6KJcCTHgP7w') ?>"></label>
            <label>Owner Facebook<input type="url" name="product_owner_facebook" value="<?= e($settings['product_owner_facebook'] ?? 'https://www.facebook.com/people/Dr-Praveen-Jacob/100063556307522/') ?>"></label>
            <label>Professional Profile URL<input type="url" name="product_owner_profile_url" value="<?= e($settings['product_owner_profile_url'] ?? 'https://nisargahospital.in/doctors/dr-praveen-jacob/') ?>"></label>
        </div>
        <br><button class="btn btn-primary">Save Settings</button>
    </form>
</div>
