<?php
namespace App\Controllers;
use App\Integrations\GoogleOAuth\GoogleOAuthClient;
use App\Services\{EmailTemplateService,EnvService,JsonStoreService,NotificationQueueService,PasswordResetService,SecretService,SmtpMailer};
final class AuthController extends BaseController {
 public function logout(): void {
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
   $params = session_get_cookie_params();
   setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
  }
  session_destroy();
  session_start();
  $this->flash('You are signed out.');
  $this->redirect('/login');
 }
 public function loginPost(): void {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') { $this->flash('Email and password required.'); $this->redirect('/login'); }
    $admin = (new EnvService())->adminCredentials();
    if ($admin['email'] !== '' && $admin['password'] !== '' && $email === $admin['email'] && hash_equals($admin['password'], $password)) {
        $_SESSION['user'] = ['sub'=>'env-admin','email'=>$admin['email'],'name'=>$admin['username'] ?: 'Admin','role'=>'admin'];
        $this->flash('Signed in.');
        $this->redirect('/admin');
    }
    $store = new JsonStoreService();
    $users = $store->read('users');
    foreach ($users as $u) {
        if (($u['email'] ?? '') === $email && !empty($u['password_hash']) && password_verify($password,$u['password_hash'])) {
            $isAdmin = ($u['role'] ?? '') === 'admin' || !empty($u['is_admin']);
            $_SESSION['user'] = ['sub'=>$u['id'],'email'=>$u['email'],'name'=>$u['name'] ?? '','certificate_name'=>$u['certificate_name'] ?? $u['name'] ?? '','role'=>$u['role'] ?? ($isAdmin ? 'admin' : 'customer')];
            $this->flash('Signed in.');
            $this->redirect($isAdmin ? '/admin' : '/dashboard');
        }
    }
    $this->flash('Invalid credentials.');
    $this->redirect('/login');
 }
 public function signupPost(): void {
    $name = trim($_POST['name'] ?? '');
    $certificateName = trim($_POST['certificate_name'] ?? $name);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';
    if ($name === '' || $certificateName === '' || $email === '' || $password === '' || $password !== $confirm) {
        $this->flash('Enter your name, certificate name, email, and matching passwords.');
        $this->redirect('/signup');
    }
    $store = new JsonStoreService();
    $users = $store->read('users');
    foreach ($users as $user) {
        if (($user['email'] ?? '') === $email) {
            $this->flash('An account already exists for this email.');
            $this->redirect('/login');
        }
    }
    $user = [
        'id' => uniqid('user_', true),
        'email' => $email,
        'name' => $name,
        'certificate_name' => $certificateName,
        'role' => 'customer',
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => time(),
    ];
    $store->upsert('users', $user);
    (new NotificationQueueService($store))->queueSignup($user);
    $_SESSION['user'] = ['sub'=>$user['id'],'email'=>$user['email'],'name'=>$user['name'],'certificate_name'=>$user['certificate_name'],'role'=>'customer'];
    $this->flash('Account created.');
    $this->redirect('/dashboard');
 }
 public function googleRedirect(): void {
    $client = new GoogleOAuthClient((new SecretService())->all());
    if (!$client->configured()) {
        $this->flash('Google OAuth is not configured yet. Add Google credentials in Admin -> Integrations.');
        $this->redirect('/login');
    }
    $state = bin2hex(random_bytes(16));
    $_SESSION['google_oauth_state'] = $state;
    header('Location: ' . $client->authUrl($state));
    exit;
 }
 public function googleCallback(): void {
    $state = (string)($_GET['state'] ?? '');
    if ($state === '' || $state !== ($_SESSION['google_oauth_state'] ?? '')) {
        $this->flash('Google sign-in could not be verified.');
        $this->redirect('/login');
    }
    unset($_SESSION['google_oauth_state']);
    $googleError = trim((string)($_GET['error'] ?? ''));
    if ($googleError !== '') {
        $description = trim((string)($_GET['error_description'] ?? ''));
        $_SESSION['google_oauth_error'] = trim($googleError . ($description !== '' ? ': ' . $description : ''));
        if ($googleError === 'access_denied') {
            $this->flash('Google sign-in is blocked because the OAuth app is still in testing or not approved for this account. Add this email as a Google OAuth test user or publish/verify the app in Google Cloud Console.');
        } else {
            $this->flash('Google sign-in failed: ' . $googleError . '. Check the OAuth consent screen and client setup.');
        }
        $this->redirect('/login');
    }
    $code = (string)($_GET['code'] ?? '');
    if ($code === '') {
        $this->flash('Google did not return an authorization code.');
        $this->redirect('/login');
    }
    $client = new GoogleOAuthClient((new SecretService())->all());
    if (!$client->configured()) {
        $this->flash('Google OAuth is not configured yet. Add Google credentials in Admin -> Integrations.');
        $this->redirect('/login');
    }
    try {
        $tokens = $client->exchangeCode($code);
        $profile = $client->userInfo((string)$tokens['access_token']);
    } catch (\Throwable $e) {
        $_SESSION['google_oauth_error'] = $e->getMessage();
        $this->flash('Google sign-in failed. Check the OAuth client setup and redirect URI.');
        $this->redirect('/login');
    }
    $store = new JsonStoreService();
    $users = $store->read('users');
    $email = strtolower(trim((string)$profile['email']));
    $existing = null;
    foreach ($users as $user) {
        if (($user['google_sub'] ?? '') === ($profile['sub'] ?? '') || strtolower((string)($user['email'] ?? '')) === $email) {
            $existing = $user;
            break;
        }
    }
    $scope = (string)($tokens['scope'] ?? '');
    $user = array_merge($existing ?: [], [
        'id' => $existing['id'] ?? uniqid('user_', true),
        'email' => $email,
        'name' => trim((string)($profile['name'] ?? $existing['name'] ?? $email)),
        'certificate_name' => trim((string)($existing['certificate_name'] ?? $profile['name'] ?? $email)),
        'role' => $existing['role'] ?? 'customer',
        'google_sub' => (string)$profile['sub'],
        'google_email_verified' => (bool)($profile['email_verified'] ?? false),
        'google_calendar_enabled' => str_contains($scope, 'https://www.googleapis.com/auth/calendar.events'),
        'google_oauth_scope' => $scope,
        'google_token_expires_at' => time() + (int)($tokens['expires_in'] ?? 0),
        'updated_at' => time(),
    ]);
    if (!empty($tokens['refresh_token'])) {
        $user['google_refresh_token'] = (string)$tokens['refresh_token'];
    }
    if (empty($existing['created_at'])) {
        $user['created_at'] = time();
    }
    $store->upsert('users', $user);
    $_SESSION['user'] = [
        'sub' => $user['id'],
        'email' => $user['email'],
        'name' => $user['name'],
        'certificate_name' => $user['certificate_name'],
        'role' => $user['role'],
    ];
    $this->flash('Signed in with Google.');
    $this->redirect(($user['role'] ?? '') === 'admin' ? '/admin' : '/dashboard');
 }
 public function forgotPassword(): void {
    $this->render('public/forgot-password');
 }
 public function forgotPasswordPost(): void {
    $email = trim($_POST['email'] ?? '');
    if ($email !== '') {
        $token = (new PasswordResetService())->createToken($email);
        if ($token) {
            $path = '/reset-password?token=' . urlencode($token);
            $_SESSION['last_reset_link'] = $path;
            $host = $_SERVER['HTTP_HOST'] ?? 'gutconference.online';
            $scheme = str_starts_with($host, '127.0.0.1') || str_starts_with($host, 'localhost') ? 'http' : 'https';
            $link = $scheme . '://' . $host . $path;
            $mailer = new SmtpMailer((new SecretService())->all());
            if ($mailer->configured()) {
                try {
                    $rendered = (new EmailTemplateService())->render('password-reset', [
                        'reset_link' => $link,
                        'title' => 'Reset your password',
                        'message' => 'Use this secure link to reset your GutConference password: ' . $link,
                        'cta_url' => $link,
                        'cta_label' => 'Reset Password',
                    ]);
                    $mailer->send($email, $rendered['subject'], $rendered['html']);
                } catch (\Throwable $e) {
                    $_SESSION['smtp_reset_error'] = $e->getMessage();
                }
            }
        }
    }
    $this->flash('If this email is registered, a reset link will be sent.');
    $this->redirect('/forgot-password');
 }
 public function resetPassword(): void {
    $this->render('public/reset-password', ['token' => $_GET['token'] ?? '']);
 }
 public function resetPasswordPost(): void {
    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';
    if ($password === '' || $password !== $confirm) {
        $this->flash('Passwords do not match.');
        $this->redirect('/reset-password?token=' . urlencode($token));
    }
    if ((new PasswordResetService())->resetPassword($token, $password)) {
        $this->flash('Password updated. Please sign in.');
        $this->redirect('/login');
    }
    $this->flash('Reset link is invalid or expired.');
    $this->redirect('/forgot-password');
 }
}
