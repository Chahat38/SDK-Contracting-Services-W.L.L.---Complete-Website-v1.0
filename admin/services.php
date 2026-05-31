<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Services';
$success = '';
$error   = '';

// Delete
if (isset($_GET['delete'])) {
    db()->prepare("DELETE FROM services WHERE id=?")->execute([(int)$_GET['delete']]);
    header('Location: /admin/services?deleted=1');
    exit;
}

// Toggle status
if (isset($_GET['toggle'])) {
    $svc = db()->prepare("SELECT status FROM services WHERE id=?")->execute([(int)$_GET['toggle']]);
    $svc = db()->query("SELECT status FROM services WHERE id=".(int)$_GET['toggle'])->fetch();
    $new = $svc['status'] === 'active' ? 'hidden' : 'active';
    db()->prepare("UPDATE services SET status=? WHERE id=?")->execute([$new, (int)$_GET['toggle']]);
    header('Location: /admin/services');
    exit;
}

if (isset($_GET['deleted'])) $success = 'Service deleted.';

$services = db()->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once '_header.php';
?>

<div class="admin-page-title">🔧 Services</div>
<div class="admin-page-sub">Manage all services shown on the website.</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div style="margin-bottom:20px;">
    <a href="/admin/add-service" class="btn btn-primary">➕ Add New Service</a>
</div>

<div class="admin-card">
    <h3>All Services <span style="font-size:13px; color:var(--muted); font-weight:400;">(<?= count($services) ?> total)</span></h3>
    <?php if (empty($services)): ?>
        <p style="color:var(--muted);">No services yet. Add your first service.</p>
    <?php else: ?>
    <div class="admin-card-scroll"><table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $svc): ?>
            <tr>
                <td>
    <img src="/uploads/services/<?= htmlspecialchars($svc['image'] ?? '') ?>"
         alt="image" 
         style="width:40px; height:40px;">
</td>
                <td style="font-weight:600; color:var(--white);"><?= htmlspecialchars($svc['title']) ?></td>
                <td style="max-width:300px; color:var(--muted);"><?= htmlspecialchars(substr($svc['description'] ?? '', 0, 80)) ?>...</td>
                <td><?= htmlspecialchars($svc['sort_order']) ?></td>
                <td>
                    <span class="badge <?= $svc['status']==='active' ? 'badge-active' : 'badge-hidden' ?>">
                        <?= $svc['status'] ?>
                    </span>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="/admin/edit-service?id=<?= $svc['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                        <a href="?toggle=<?= $svc['id'] ?>" class="btn btn-sm btn-outline">
                            <?= $svc['status']==='active' ? 'Hide' : 'Show' ?>
                        </a>
                        <a href="?delete=<?= $svc['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this service?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</div>

<?php require_once '_footer.php'; ?>