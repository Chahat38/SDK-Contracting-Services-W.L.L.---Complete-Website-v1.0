<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $secret   = trim($_POST['secret_key'] ?? '');

   define('SDK_RESET_KEY', 'Tahahc2004');

    $stmt = db()->prepare("SELECT id, username FROM admins WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin) {
        $error = 'Username not found.';
    } elseif ($secret !== SDK_RESET_KEY) {
        $error = 'Invalid secret key. Contact your website developer.';
    } else {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        try {
            db()->prepare("UPDATE admins SET reset_token=?, reset_expires=? WHERE id=?")
               ->execute([$token, $expires, $admin['id']]);
            $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/reset-password.php?token=' . $token;
            $_SESSION['reset_link'] = $reset_link;
            $success = 'Verified! Use the link below to set your new password.';
        } catch (Exception $e) {
            $error = 'Database error. Make sure reset_token column exists in admins table.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SDK Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-login-wrap">
    <div class="admin-login-card" style="max-width:460px;">
        <div class="login-logo">
            <span class="logo-icon">🔑</span>
            <h2>Forgot Password</h2>
            <p>Enter your username and secret key</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success && isset($_SESSION['reset_link'])): ?>
            <div class="alert alert-success">✅ <?= $success ?></div>
            <div style="background:var(--dark-3);border:1px solid rgba(200,151,42,0.3);border-radius:var(--radius);padding:16px;margin-bottom:20px;">
                <p style="font-size:12px;color:var(--muted);margin-bottom:8px;">Reset Link (valid 1 hour):</p>
                <p style="font-size:11px;color:var(--gold);word-break:break-all;line-height:1.6;" id="resetLinkText">
                    <?= htmlspecialchars($_SESSION['reset_link']) ?>
                </p>
                <button onclick="copyResetLink()" class="btn btn-outline btn-sm" style="margin-top:12px;width:100%;justify-content:center;">
                    📋 Copy Link
                </button>
            </div>
            <a href="<?= htmlspecialchars($_SESSION['reset_link']) ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:16px;">
                Reset Password Now →
            </a>
            <?php unset($_SESSION['reset_link']); ?>
        <?php else: ?>
        <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">
            Enter your username and the <strong style="color:var(--gold)">secret key</strong> provided by your developer.
            <br><br>
           <!-- Default key: <code style="background:var(--dark-3);padding:2px 8px;border-radius:4px;color:var(--gold);">SDK@Reset2024</code> -->
        </p>
        <form method="POST" action="/admin/forgot-password">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label>Secret Key</label>
                <input type="password" name="secret_key" placeholder="Enter secret key" required>
                <span class="field-hint">Ask your developer if you don't have this key.</span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                Generate Reset Link →
            </button>
        </form>
        <?php endif; ?>

        <p style="text-align:center;margin-top:20px;font-size:13px;">
            <a href="/admin/login" style="color:var(--muted);">← Back to Login</a>
        </p>
    </div>
</div>
<script>
function copyResetLink() {
    const t = document.getElementById('resetLinkText');
    if (t) {
        navigator.clipboard.writeText(t.textContent.trim())
            .then(() => { document.querySelector('[onclick="copyResetLink()"]').textContent = '✅ Copied!'; })
            .catch(() => alert('Please copy the link manually.'));
    }
}
</script>
</body>
</html>
