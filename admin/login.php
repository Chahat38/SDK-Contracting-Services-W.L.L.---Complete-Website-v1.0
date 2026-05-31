<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/dashboard');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$username || !$password) {
        $error = 'Please enter both username and password.';
    } elseif (!admin_login($username, $password)) {
        $error = 'Invalid username or password. Please try again.';
    } else {
        header('Location: /admin/dashboard');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SDK Construction</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-login-wrap">
    <div class="admin-login-card">
        <div class="login-logo">
            <span class="logo-icon">◆</span>
            <h2>SDK Construction</h2>
            <p>Admin Panel — Secure Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       autocomplete="username" required autofocus
                       placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position:relative;">
                    <input type="password" id="password" name="password"
                           autocomplete="current-password" required
                           placeholder="Enter your password"
                           style="padding-right:48px;">
                    <button type="button" onclick="togglePwd()"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                               background:none;border:none;cursor:pointer;color:var(--muted);
                               font-size:16px;padding:0;line-height:1;" id="eyeBtn">👁️</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;justify-content:center;">
                Sign In →
            </button>
        </form>

        <div style="text-align:center;margin-top:20px;padding-top:20px;border-top:1px solid var(--dark-4);">
            <a href="/admin/forgot-password"
               style="font-size:13px;color:var(--muted);">
               🔑 Forgot your password?
            </a>
        </div>

        <p style="text-align:center;margin-top:16px;font-size:12px;color:var(--muted-2);">
            <a href="/" style="color:var(--muted-2);">← Back to website</a>
        </p>
    </div>
</div>
<script>
function togglePwd() {
    const p = document.getElementById('password');
    const b = document.getElementById('eyeBtn');
    if (p.type === 'password') { p.type = 'text'; b.textContent = '🙈'; }
    else { p.type = 'password'; b.textContent = '👁️'; }
}
</script>
</body>
</html>
