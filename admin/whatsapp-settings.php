<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'WhatsApp Settings';
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number  = preg_replace('/\D/', '', trim($_POST['whatsapp_number'] ?? ''));
    $enabled = $_POST['whatsapp_enabled'] ?? '0';
    $message = trim($_POST['whatsapp_message'] ?? '');

    // Validate: only digits, 7-15 chars
    if ($number && (strlen($number) < 7 || strlen($number) > 15)) {
        $error = 'WhatsApp number must be 7–15 digits (no spaces, dashes, or +).';
    } else {
        try {
            $fields = [
                'whatsapp_enabled' => $enabled,
                'whatsapp_number'  => $number,
                'whatsapp_message' => $message,
            ];
            $stmt = db()->prepare("INSERT INTO settings (setting_key, setting_value)
                VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            foreach ($fields as $k => $v) $stmt->execute([$k, $v]);
            $success = 'WhatsApp settings saved!';
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$rows = db()->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
$s = [];
foreach ($rows as $r) $s[$r['setting_key']] = $r['setting_value'];

require_once '_header.php';
?>

<div style="max-width:600px;">
    <div class="admin-page-title">💬 WhatsApp Settings</div>
    <div class="admin-page-sub">Configure the WhatsApp floating button on the website.</div>

    <?php if ($success): ?>
        <div class="alert alert-success flash-auto-hide">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <form method="POST" id="waForm">

            <div class="form-group">
                <label>Enable WhatsApp Button</label>
                <select name="whatsapp_enabled">
                    <option value="1" <?= ($s['whatsapp_enabled'] ?? '1')==='1' ? 'selected':'' ?>>✅ Enabled</option>
                    <option value="0" <?= ($s['whatsapp_enabled'] ?? '1')==='0' ? 'selected':'' ?>>❌ Disabled</option>
                </select>
            </div>

            <div class="form-group" id="phoneGroup">
                <label>WhatsApp Number <span style="color:var(--gold)">*</span></label>
                <input type="text" name="whatsapp_number" id="waNumber"
                       value="<?= htmlspecialchars($s['whatsapp_number'] ?? '') ?>"
                       placeholder="97455556666"
                       inputmode="numeric"
                       maxlength="15">
                <span class="field-hint">Digits only — country code included, no + or spaces. Example: 97455556666</span>
                <span class="field-error" id="waNumberError" style="display:none;"></span>
            </div>

            <div class="form-group">
                <label>Default Message</label>
                <textarea name="whatsapp_message" rows="3"
                          placeholder="Hello SDK Construction!"><?= htmlspecialchars($s['whatsapp_message'] ?? '') ?></textarea>
                <span class="field-hint">Pre-filled message when user clicks the WhatsApp button.</span>
            </div>

            <!-- Live Preview -->
            <div style="background:var(--dark-3); border:1px solid var(--dark-5); border-radius:8px; padding:16px; margin-bottom:22px;">
                <div style="font-size:11px; font-weight:700; color:var(--gold); letter-spacing:0.1em; margin-bottom:8px;">PREVIEW LINK</div>
                <code id="waPreview" style="font-size:12px; color:var(--muted); word-break:break-all;">
                    https://wa.me/<?= htmlspecialchars($s['whatsapp_number'] ?? '') ?>
                </code>
                <div style="margin-top:10px;">
                    <a id="waTestLink" href="https://wa.me/<?= htmlspecialchars($s['whatsapp_number'] ?? '') ?>"
                       target="_blank" class="btn btn-sm btn-outline">
                        Test This Number →
                    </a>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:16px;">
                💾 Save WhatsApp Settings
            </button>
        </form>
    </div>
</div>

<script>
const waInput   = document.getElementById('waNumber');
const waPreview = document.getElementById('waPreview');
const waTest    = document.getElementById('waTestLink');
const waError   = document.getElementById('waNumberError');

waInput.addEventListener('input', () => {
    // Strip non-digits live
    const clean = waInput.value.replace(/\D/g, '');
    waInput.value = clean;

    const url = 'https://wa.me/' + clean;
    waPreview.textContent = url;
    waTest.href = url;

    if (clean.length > 0 && (clean.length < 7 || clean.length > 15)) {
        waError.textContent = '⚠ Must be 7–15 digits';
        waError.style.display = 'block';
    } else {
        waError.style.display = 'none';
    }
});

document.getElementById('waForm').addEventListener('submit', e => {
    const clean = waInput.value.replace(/\D/g, '');
    if (clean && (clean.length < 7 || clean.length > 15)) {
        e.preventDefault();
        waError.textContent = '⚠ Enter a valid number (7–15 digits)';
        waError.style.display = 'block';
        waInput.focus();
    }
});
</script>

<?php require_once '_footer.php'; ?>
