<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();
$page_title = 'Dashboard';
$db = db();

$project_count = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$service_count = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$message_count = $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$unread_count  = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
$projects      = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row = $db->prepare("SELECT image FROM projects WHERE id=?");
    $row->execute([$id]);
    $img = $row->fetchColumn();
    if ($img && file_exists('../uploads/projects/' . $img)) unlink('../uploads/projects/' . $img);
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    header('Location: /admin/dashboard?deleted=1');
    exit;
}

require_once '_header.php';
?>

<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success flash-auto-hide">✅ Project deleted.</div><?php endif; ?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success flash-auto-hide">✅ Project saved successfully.</div><?php endif; ?>

<div class="admin-page-title">Dashboard</div>
<p class="admin-page-sub">Welcome back, <strong style="color:var(--gold)"><?= htmlspecialchars(current_admin()) ?></strong>. Here's your site overview.</p>

<div class="stats-row">
    <div class="stat-card">
        <div class="s-icon">📁</div>
        <div class="s-num"><?= $project_count ?></div>
        <div class="s-label">Total Projects</div>
    </div>
    <div class="stat-card">
        <div class="s-icon">🔧</div>
        <div class="s-num"><?= $service_count ?></div>
        <div class="s-label">Total Services</div>
    </div>
    <div class="stat-card">
        <div class="s-icon">✉️</div>
        <div class="s-num"><?= $message_count ?></div>
        <div class="s-label">Total Messages</div>
    </div>
    <div class="stat-card">
        <div class="s-icon">🔔</div>
        <div class="s-num" style="color:<?= $unread_count>0 ? 'var(--gold)':'var(--muted)' ?>"><?= $unread_count ?></div>
        <div class="s-label">Unread Messages</div>
    </div>
</div>

<!-- Projects Table -->
<div class="admin-card" id="projects">
    <h3>
        All Projects
        <a href="/admin/add-project" class="btn btn-primary btn-sm">+ Add New</a>
    </h3>
    <?php if (empty($projects)): ?>
        <p style="color:var(--muted);">No projects yet. <a href="/admin/add-project">Add one →</a></p>
    <?php else: ?>
    <div class="admin-card-scroll">
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th>Added</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td>
                    <?php if ($p['image'] && file_exists('../uploads/projects/'.$p['image'])): ?>
                        <img src="/uploads/projects/<?= htmlspecialchars($p['image']) ?>"
                             style="width:56px;height:44px;object-fit:cover;border-radius:6px;" onerror="this.style.display=\'none\'">>
                    <?php else: ?>
                        <div style="width:56px;height:44px;background:var(--dark-3);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:20px;">🏗️</div>
                    <?php endif; ?>
                </td>
                <td style="font-weight:500;"><?= htmlspecialchars($p['title']) ?></td>
                <td><span style="font-size:13px;color:var(--muted)"><?= htmlspecialchars($p['category'] ?? '—') ?></span></td>
                <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                <td>
                    <div class="table-actions">
                        <a href="/admin/edit-project?id=<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                        <a href="/admin/dashboard.php?delete=<?= $p['id'] ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="Delete '<?= htmlspecialchars($p['title']) ?>'? This cannot be undone.">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Recent Messages -->
<div class="admin-card">
    <h3>
        Recent Messages
        <a href="/admin/view-messages" class="btn btn-outline btn-sm">View All</a>
    </h3>
    <?php $recent = db()->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll(); ?>
    <?php if (empty($recent)): ?>
        <p style="color:var(--muted);">No messages yet.</p>
    <?php else: ?>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Email</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['email']) ?></td>
            <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
            <td><span class="badge <?= $m['is_read'] ? 'badge-hidden':'badge-unread' ?>"><?= $m['is_read'] ? 'Read':'Unread' ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- Quick Links -->
<div class="admin-card">
    <h3>Quick Actions</h3>
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="/admin/add-project"      class="btn btn-outline">➕ Add Project</a>
        <a href="/admin/add-service"      class="btn btn-outline">🔧 Add Service</a>
        <a href="/admin/home-settings"    class="btn btn-outline">🏠 Home Settings</a>
        <a href="/admin/whatsapp-settings" class="btn btn-outline">💬 WhatsApp</a>
        <a href="/admin/view-messages"    class="btn btn-outline">✉️ Messages</a>
    </div>
</div>

<?php require_once '_footer.php'; ?>