<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();
$page_title = 'Change Password';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password']     ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Verify current password
    $stmt = db()->prepare("SELECT password_hash FROM admins WHERE id=? LIMIT 1");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if (!password_verify($current, $admin['password_hash'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $new)) {
        $error = 'Must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $new)) {
        $error = 'Must contain at least one number.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
        db()->prepare("UPDATE admins SET password_hash=? WHERE id=?")
           ->execute([$hash, $_SESSION['admin_id']]);
        $success = 'Password changed successfully!';
    }
}
require_once '_header.php';
?>

<div class="admin-page-title">🔐 Change Password</div>
<p class="admin-page-sub">Update your admin account password securely.</p>

<?php if ($success): ?>
    <div class="alert alert-success">✅ <?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="admin-card" style="max-width:520px;">
    <h3>Update Password</h3>
    <form method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <div style="position:relative;">
                <input type="password" id="cp" name="current_password" required
                       placeholder="Your current password" style="padding-right:48px;">
                <button type="button" onclick="tp('cp','eb1')"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px;"
                    id="eb1">👁️</button>
            </div>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <div style="position:relative;">
                <input type="password" id="np" name="new_password" required
                       placeholder="Min 8 chars, 1 uppercase, 1 number"
                       style="padding-right:48px;" oninput="checkStrength(this.value)">
                <button type="button" onclick="tp('np','eb2')"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px;"
                    id="eb2">👁️</button>
            </div>
            <div id="bar" style="height:4px;background:var(--dark-4);border-radius:4px;margin-top:8px;overflow:hidden;">
                <div id="fill" style="height:100%;width:0%;transition:all 0.3s;border-radius:4px;"></div>
            </div>
            <div id="stxt" style="font-size:11px;color:var(--muted);margin-top:4px;"></div>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <div style="position:relative;">
                <input type="password" id="cfp" name="confirm_password" required
                       placeholder="Repeat new password"
                       style="padding-right:48px;" oninput="checkMatch()">
                <button type="button" onclick="tp('cfp','eb3')"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                           background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px;"
                    id="eb3">👁️</button>
            </div>
            <div id="mm" style="font-size:11px;margin-top:4px;"></div>
        </div>

        <div style="background:var(--dark-3);border-radius:var(--radius);padding:14px;margin-bottom:20px;font-size:13px;">
            <div id="r1" style="color:var(--muted);margin-bottom:5px;">⬜ At least 8 characters</div>
            <div id="r2" style="color:var(--muted);margin-bottom:5px;">⬜ One uppercase letter (A-Z)</div>
            <div id="r3" style="color:var(--muted);">⬜ One number (0-9)</div>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary">Update Password</button>
            <a href="/admin/dashboard" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<script>
function tp(id, bid) {
    const f = document.getElementById(id);
    const b = document.getElementById(bid);
    f.type = f.type==='password' ? 'text' : 'password';
    b.textContent = f.type==='password' ? '👁️' : '🙈';
}
function checkStrength(v) {
    const c1=v.length>=8, c2=/[A-Z]/.test(v), c3=/[0-9]/.test(v);
    document.getElementById('r1').style.color = c1?'#6fcf97':'var(--muted)';
    document.getElementById('r2').style.color = c2?'#6fcf97':'var(--muted)';
    document.getElementById('r3').style.color = c3?'#6fcf97':'var(--muted)';
    document.getElementById('r1').textContent = (c1?'✅':'⬜')+' At least 8 characters';
    document.getElementById('r2').textContent = (c2?'✅':'⬜')+' One uppercase letter (A-Z)';
    document.getElementById('r3').textContent = (c3?'✅':'⬜')+' One number (0-9)';
    const sc=[c1,c2,c3,v.length>=12,/[!@#$%]/.test(v)].filter(Boolean).length;
    const cols=['','#e74c3c','#e67e22','var(--gold)','#6fcf97','#27AE60'];
    const labs=['','Weak','Fair','Good','Strong','Very Strong'];
    document.getElementById('fill').style.width=(sc*20)+'%';
    document.getElementById('fill').style.background=cols[sc]||'';
    document.getElementById('stxt').textContent=labs[sc]||'';
    document.getElementById('stxt').style.color=cols[sc]||'';
}
function checkMatch() {
    const np=document.getElementById('np').value;
    const cf=document.getElementById('cfp').value;
    const m=document.getElementById('mm');
    if(cf===np){m.textContent='✅ Passwords match';m.style.color='#6fcf97';}
    else{m.textContent='❌ Passwords do not match';m.style.color='#e74c3c';}
}
</script>

<?php require_once '_footer.php'; ?>
