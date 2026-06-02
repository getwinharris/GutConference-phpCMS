<?php
namespace App\Controllers;
use App\Services\{EnvService,JsonStoreService,PasswordResetService};
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
            $_SESSION['user'] = ['sub'=>$u['id'],'email'=>$u['email'],'name'=>$u['name'] ?? '','role'=>$u['role'] ?? (!empty($u['is_admin']) ? 'admin' : 'customer')];
            $this->flash('Signed in.');
            $this->redirect('/');
        }
    }
    $this->flash('Invalid credentials.');
    $this->redirect('/login');
 }
 public function forgotPassword(): void {
    $this->render('public/forgot-password');
 }
 public function forgotPasswordPost(): void {
    $email = trim($_POST['email'] ?? '');
    if ($email !== '') {
        $token = (new PasswordResetService())->createToken($email);
        if ($token) {
            $_SESSION['last_reset_link'] = '/reset-password?token=' . urlencode($token);
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
