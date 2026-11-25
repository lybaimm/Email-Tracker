<?php
require_once 'koneksi.php';
require_once 'auth.php';

if (!empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $koneksi->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_nama']  = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        header("Location: index.php");
        exit;
    }

    $error = 'Email atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Email Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-wrap">
    <div class="login-container">
        <div class="login-logo-box" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="2.5" y="4.5" width="19" height="15" rx="3" fill="#ffffff"/>
                <path d="M3.5 6.5L12 13L20.5 6.5" stroke="#1d78f2" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div class="login-header">
            <h1 class="login-title">Welcome back!</h1>
            <p class="login-subtitle">Enter your email below to login to your account.</p>
        </div>

        <div class="login-tray">
            <div class="login-card">
                <?php if ($error !== ''): ?>
                    <div class="alert-error"><?= e($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label class="form-label">Email Address <span class="req-star">*</span></label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <input type="email" name="email" class="form-control input-with-icon" placeholder="nama@gmail.com" value="<?= e($_POST['email'] ?? ''); ?>" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="req-star">*</span></label>
                        <div class="input-icon-wrap">
                            <svg class="input-icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" name="password" id="passwordInput" class="form-control input-with-icon input-with-eye" placeholder="Masukkan password" required>
                            <button type="button" class="btn-toggle-eye" id="togglePassword" aria-label="Tampilkan password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>

            <div class="login-tray-footer">
                Email Tracker &bull; Client &amp; Email Follow-up CRM
            </div>
        </div>
    </div>
</div>

<script>
    const pwdInput = document.getElementById('passwordInput');
    const togglePwd = document.getElementById('togglePassword');
    if (pwdInput && togglePwd) {
        togglePwd.addEventListener('click', () => {
            pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
        });
    }
</script>

</body>
</html>
