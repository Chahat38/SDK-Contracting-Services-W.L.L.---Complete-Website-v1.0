<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$token   = trim($_GET['token'] ?? $_POST['token'] ?? '');
$success = $error = '';
$valid   = false;
$admin   = null;

if ($token) {
    $stmt = db()->prepare(
        "SELECT id, username FROM admins
         WHERE reset_token=? AND reset_expires > NOW() LIMIT 1"
    );
    $stmt->execute([$token]);
    $admin = $stmt->fetch();
    if ($admin) $valid = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $new  = $_POST['new_password']     ?? '';
    $conf = $_POST['confirm_password'] ?? '';

    if (strlen($new) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $new)) {
        $error = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $new)) {
        $error = 'Password must contain at least one number.';
    } elseif ($new !== $conf) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
        db()->prepare(
            "UPDATE admins SET password_hash=?, reset_token=NULL, reset_expires=NULL WHERE id=?"
        )->execute([$hash, $admin['id']]);
        $success = 'Password reset successfully! You can now login.';
        $valid   = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SDK Construction</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-login-wrap">
    <div class="admin-login-card" style="max-width:460px;">
        <div class="login-logo">
            <span class="logo-icon">🔐</span>
            <h2>Reset Password</h2>
            <?php if ($valid): ?>
                <p>Hello <strong style="color:var(--gold)"><?= htmlspecialchars($admin['username']) ?></strong>, set your new password</p>
            <?php endif; ?>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= $success ?></div>
            <a href="/admin/login" class="btn btn-primary" style="width:100%;justify-content:center;">
                Go to Login →
            </a>

        <?php elseif (!$token || !$valid): ?>
            <div class="alert alert-error">
                ⚠️ This reset link is <strong>invalid or has expired</strong> (links expire after 1 hour).
            </div>
            <a href="/admin/forgot-password" class="btn btn-outline" style="width:100%;justify-content:center;">
                Request New Link →
            </a>

        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/admin/reset-password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label>New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="np" name="new_password"
                               placeholder="Min 8 chars, 1 uppercase, 1 number"
                               required style="padding-right:48px;">
                        <button type="button" onclick="togglePwd('np','e1')"
                            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                                   background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px;"
                            id="e1">👁️</button>
                    </div>
                    <!-- Strength bar -->
                    <div id="strengthBar" style="height:4px;background:var(--dark-4);border-radius:4px;margin-top:8px;overflow:hidden;">
                        <div id="strengthFill" style="height:100%;width:0%;transition:all 0.3s;border-radius:4px;"></div>
                    </div>
                    <div id="strengthText" style="font-size:11px;color:var(--muted);margin-top:4px;"></div>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="cp" name="confirm_password"
                               placeholder="Repeat your new password"
                               required style="padding-right:48px;">
                        <button type="button" onclick="togglePwd('cp','e2')"
                            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                                   background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px;"
                            id="e2">👁️</button>
                    </div>
                    <div id="matchMsg" style="font-size:11px;margin-top:4px;"></div>
                </div>

                <!-- Requirements checklist -->
                <div style="background:var(--dark-3);border-radius:var(--radius);padding:14px;margin-bottom:20px;font-size:13px;">
                    <div id="r1" style="color:var(--muted);margin-bottom:6px;">⬜ At least 8 characters</div>
                    <div id="r2" style="color:var(--muted);margin-bottom:6px;">⬜ One uppercase letter</div>
                    <div id="r3" style="color:var(--muted);">⬜ One number</div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Set New Password →
                </button>
            </form>
        <?php endif; ?>

        <p style="text-align:center;margin-top:20px;font-size:13px;">
            <a href="/admin/login" style="color:var(--muted);">← Back to Login</a>
        </p>
    </div>
</div>

<script>
function togglePwd(id, btnId) {
    const f = document.getElementById(id);
    const b = document.getElementById(btnId);
    f.type = f.type === 'password' ? 'text' : 'password';
    b.textContent = f.type === 'password' ? '👁️' : '🙈';
}

const np = document.getElementById('np');
const cp = document.getElementById('cp');

if (np) {
    np.addEventListener('input', function() {
        const v = this.value;
        const r1 = document.getElementById('r1');
        const r2 = document.getElementById('r2');
        const r3 = document.getElementById('r3');
        const fill = document.getElementById('strengthFill');
        const txt  = document.getElementById('strengthText');

        const c1 = v.length >= 8;
        const c2 = /[A-Z]/.test(v);
        const c3 = /[0-9]/.test(v);

        r1.textContent = (c1 ? '✅' : '⬜') + ' At least 8 characters';
        r2.textContent = (c2 ? '✅' : '⬜') + ' One uppercase letter';
        r3.textContent = (c3 ? '✅' : '⬜') + ' One number';

        r1.style.color = c1 ? '#6fcf97' : 'var(--muted)';
        r2.style.color = c2 ? '#6fcf97' : 'var(--muted)';
        r3.style.color = c3 ? '#6fcf97' : 'var(--muted)';

        const score = [c1, c2, c3, v.length >= 12, /[!@#$%]/.test(v)].filter(Boolean).length;
        const colors = ['','#e74c3c','#e67e22','var(--gold)','#6fcf97','#27AE60'];
        const labels = ['','Weak','Fair','Good','Strong','Very Strong'];
        fill.style.width  = (score * 20) + '%';
        fill.style.background = colors[score] || '';
        txt.textContent   = labels[score] || '';
        txt.style.color   = colors[score] || '';
    });
}

if (cp) {
    cp.addEventListener('input', function() {
        const msg = document.getElementById('matchMsg');
        if (this.value === np.value) {
            msg.textContent = '✅ Passwords match';
            msg.style.color = '#6fcf97';
        } else {
            msg.textContent = '❌ Passwords do not match';
            msg.style.color = '#e74c3c';
        }
    });
}
</script>
</body>
</html>
