 <?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Add Service';
$success = '';
$error   = '';

// Edit mode
$edit = null;
if (isset($_GET['id'])) {
    $stmt = db()->prepare("SELECT * FROM services WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    $edit = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $status      = $_POST['status'] ?? 'active';
    $image       = $edit['image'] ?? '';

    if (!$title) {
        $error = 'Title is required.';
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Only JPG, PNG, WEBP images allowed.';
            } elseif ($_FILES['image']['size'] > 6 * 1024 * 1024) {
                $error = 'Image must be under 6MB.';
            } else {
                $upload_dir = __DIR__ . '/../uploads/services/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $filename = uniqid('svc_') . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
                    if (!empty($edit['image']) && file_exists($upload_dir . $edit['image'])) {
                        unlink($upload_dir . $edit['image']);
                    }
                    $image = $filename;
                } else {
                    $error = 'Image upload failed.';
                }
            }
        }

        if (!$error) {
            try {
                if (isset($_POST['edit_id'])) {
                    db()->prepare("UPDATE services SET title=?, description=?, image=?, sort_order=?, status=? WHERE id=?")
                        ->execute([$title, $description, $image, $sort_order, $status, (int)$_POST['edit_id']]);
                    $success = 'Service updated successfully!';
                } else {
                    db()->prepare("INSERT INTO services (title, description, image, sort_order, status) VALUES (?,?,?,?,?)")
                        ->execute([$title, $description, $image, $sort_order, $status]);
                    $success = 'Service added successfully!';
                }
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}

require_once '_header.php';
?>

<div style="max-width:700px;">
    <div class="admin-page-title"><?= $edit ? '✏️ Edit Service' : '➕ Add Service' ?></div>
    <div class="admin-page-sub"><?= $edit ? 'Update service details.' : 'Add a new service to the website.' ?></div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
            <a href="/admin/services" style="color:var(--gold); margin-left:12px;">← Back to Services</a>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit): ?>
                <input type="hidden" name="edit_id" value="<?= $edit['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Service Image</label>
                <?php if (!empty($edit['image'])): ?>
                    <div style="margin-bottom:10px;">
                        <img src="/uploads/services/<?= htmlspecialchars($edit['image']) ?>"
                             style="width:120px; height:80px; object-fit:cover; border-radius:6px; border:1px solid var(--dark-5);">
                        <div style="font-size:12px; color:var(--muted); margin-top:4px;">Current image — upload new to replace</div>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                <small style="color:var(--muted); font-size:12px; margin-top:6px; display:block;">JPG, PNG, WEBP — max 6MB</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($edit['sort_order'] ?? '0') ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active" <?= ($edit['status'] ?? 'active')==='active' ? 'selected':'' ?>>Active</option>
                        <option value="hidden" <?= ($edit['status'] ?? '')==='hidden' ? 'selected':'' ?>>Hidden</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn btn-primary"><?= $edit ? '💾 Update Service' : '➕ Add Service' ?></button>
                <a href="/admin/services" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '_footer.php'; ?>