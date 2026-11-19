<?php
require_once 'includes/functions.php';
if (logged_in()) redirect();
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $stmt=db()->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=? AND active=1');
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $u=$stmt->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
        session_regenerate_id(true);
        unset($u['password_hash']); $_SESSION['user']=$u; redirect('dashboard.php');
    }
    $error='Incorrect email or password.';
}
header_html('Login');
?>
<section class="auth card">
<h1>Laboratory Report Portal</h1><p class="muted">Sign in to continue</p>
<?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<form method="post"><?=csrf_field()?>
<label>Email</label><input type="email" name="email" required>
<label>Password</label><input type="password" name="password" required>
<button type="submit">Login</button></form>
<p class="muted small">First time? Run <a href="setup.php">project setup</a> once.</p>
</section>
<?php footer_html(); ?>
