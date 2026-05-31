<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Messages';
$db = db();

// Mark as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $db->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([(int)$_GET['read']]);
    header('Location: /admin/view-messages');
    exit;
}

// Delete message
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $db->prepare("DELETE FROM messages WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: /admin/view-messages?deleted=1');
    exit;
}

// Mark all read
if (isset($_GET['readall'])) {
    $db->exec("UPDATE messages SET is_read=1");
    header('Location: /admin/view-messages');
    exit;
}

$messages = $db->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
$unread   = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();

require_once '_header.php';
?>

<div class="admin-page-title">Contact Messages</div>
<p class="admin-page-sub">
    <?= count($messages) ?> total messages
    <?php if ($unread > 0): ?>
        — <span style="color:var(--gold)"><?= $unread ?> unread</span>
        &nbsp;<a href="/admin/view-messages.php?readall=1" class="btn btn-outline btn-sm">Mark All Read</a>
    <?php endif; ?>
</p>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success flash-auto-hide">Message deleted.</div>
<?php endif; ?>

<?php if (empty($messages)): ?>
    <div class="alert alert-info">No messages yet.</div>
<?php else: ?>

<?php foreach ($messages as $m): ?>
<div class="admin-card" style="border-left: 3px solid <?= $m['is_read'] ? 'var(--dark-4)' : 'var(--gold)' ?>; margin-bottom:16px;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; padding-bottom:16px; border-bottom:1px solid var(--dark-4); margin-bottom:16px;">
        <div>
            <strong style="font-size:15px;"><?= htmlspecialchars($m['name']) ?></strong>
            <?php if (!$m['is_read']): ?>
                <span class="badge badge-unread" style="margin-left:8px;">New</span>
            <?php endif; ?>
            <div style="margin-top:6px; font-size:13px; color:var(--muted);">
                ✉️ <?= htmlspecialchars($m['email']) ?>
                <?php if ($m['phone']): ?>
                    &nbsp; 📞 <?= htmlspecialchars($m['phone']) ?>
                <?php endif; ?>
            </div>
            <div style="font-size:12px; color:#555; margin-top:4px;">
                📅 <?= date('D, d M Y — H:i', strtotime($m['created_at'])) ?>
            </div>
        </div>
        <div class="table-actions">
            <?php if (!$m['is_read']): ?>
                <a href="/admin/view-messages.php?read=<?= $m['id'] ?>"
                   class="btn btn-outline btn-sm">Mark Read</a>
            <?php endif; ?>
            <a href="/admin/view-messages.php?delete=<?= $m['id'] ?>"
               class="btn btn-danger btn-sm"
               data-confirm="Delete this message from <?= htmlspecialchars($m['name']) ?>?">
               Delete
            </a>
        </div>
    </div>
    <p class="msg-body"><?= nl2br(htmlspecialchars($m['message'])) ?></p>
</div>
<?php endforeach; ?>

<?php endif; ?>

<?php require_once '_footer.php'; ?>