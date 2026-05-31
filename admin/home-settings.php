<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Home Settings';
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fields = [
            'hero_badge', 'hero_title_line1', 'hero_title_line2', 'hero_subtitle',
            'stat_1_num', 'stat_1_label', 'stat_2_num', 'stat_2_label',
            'stat_3_num', 'stat_3_label', 'stat_4_num', 'stat_4_label',
            'why_title',
            'why_card_1_icon', 'why_card_1_title', 'why_card_1_desc',
            'why_card_2_icon', 'why_card_2_title', 'why_card_2_desc',
            'why_card_3_icon', 'why_card_3_title', 'why_card_3_desc',
            'why_card_4_icon', 'why_card_4_title', 'why_card_4_desc',
        ];
        $stmt = db()->prepare("INSERT INTO settings (setting_key, setting_value)
            VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($fields as $f) {
            $stmt->execute([$f, trim($_POST[$f] ?? '')]);
        }
        $success = 'Home settings saved successfully!';
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Load current settings
$rows = db()->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
$s = [];
foreach ($rows as $r) $s[$r['setting_key']] = $r['setting_value'];

require_once '_header.php';
?>

<div style="max-width:860px;">
    <div class="admin-page-title">🏠 Home Page Settings</div>
    <div class="admin-page-sub">Edit hero section, stats, and "Why Us" cards.</div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">

        <!-- HERO SECTION -->
        <div class="admin-card">
            <h3>Hero Section</h3>

            <div class="form-group">
                <label>Badge Text</label>
                <input type="text" name="hero_badge" value="<?= htmlspecialchars($s['hero_badge'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Title Line 1</label>
                    <input type="text" name="hero_title_line1" value="<?= htmlspecialchars($s['hero_title_line1'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Title Line 2 (Gold)</label>
                    <input type="text" name="hero_title_line2" value="<?= htmlspecialchars($s['hero_title_line2'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Subtitle</label>
                <textarea name="hero_subtitle" rows="3"><?= htmlspecialchars($s['hero_subtitle'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- STATS -->
        <div class="admin-card">
            <h3>Stats Bar</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Stat 1 Number</label>
                    <input type="text" name="stat_1_num" value="<?= htmlspecialchars($s['stat_1_num'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat 1 Label</label>
                    <input type="text" name="stat_1_label" value="<?= htmlspecialchars($s['stat_1_label'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stat 2 Number</label>
                    <input type="text" name="stat_2_num" value="<?= htmlspecialchars($s['stat_2_num'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat 2 Label</label>
                    <input type="text" name="stat_2_label" value="<?= htmlspecialchars($s['stat_2_label'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stat 3 Number</label>
                    <input type="text" name="stat_3_num" value="<?= htmlspecialchars($s['stat_3_num'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat 3 Label</label>
                    <input type="text" name="stat_3_label" value="<?= htmlspecialchars($s['stat_3_label'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stat 4 Number</label>
                    <input type="text" name="stat_4_num" value="<?= htmlspecialchars($s['stat_4_num'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Stat 4 Label</label>
                    <input type="text" name="stat_4_label" value="<?= htmlspecialchars($s['stat_4_label'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- WHY US -->
        <div class="admin-card">
            <h3>Why Us Section</h3>
            <div class="form-group">
                <label>Section Title</label>
                <input type="text" name="why_title" value="<?= htmlspecialchars($s['why_title'] ?? '') ?>">
            </div>
            <?php for ($i = 1; $i <= 4; $i++): ?>
            <div style="border:1px solid var(--dark-5); border-radius:8px; padding:20px; margin-bottom:16px;">
                <div style="font-size:12px; font-weight:700; color:var(--gold); letter-spacing:0.1em; margin-bottom:14px;">CARD <?= $i ?></div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Icon (emoji)</label>
                        <input type="text" name="why_card_<?= $i ?>_icon" value="<?= htmlspecialchars($s["why_card_{$i}_icon"] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="why_card_<?= $i ?>_title" value="<?= htmlspecialchars($s["why_card_{$i}_title"] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="why_card_<?= $i ?>_desc" value="<?= htmlspecialchars($s["why_card_{$i}_desc"] ?? '') ?>">
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; padding:16px; font-size:15px;">
            💾 Save All Settings
        </button>
    </form>
</div>

<?php require_once '_footer.php'; ?>