 <?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Edit Project';
$errors  = [];

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = db()->prepare("SELECT * FROM projects WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /admin/dashboard');
    exit;
}

define('UPLOAD_DIR', __DIR__ . '/../uploads/projects/');
define('MAX_SIZE',   5 * 1024 * 1024);
$allowed_types = ['image/jpeg','image/png','image/webp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = in_array($_POST['status']??'', ['active','hidden']) ? $_POST['status'] : 'active';

    if (!$title) $errors[] = 'Project title is required.';

    $image_name = $project['image'];

    if (!empty($_FILES['image']['name'])) {
        $file     = $_FILES['image'];
        $tmp      = $file['tmp_name'];
        $orig_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime     = mime_content_type($tmp);

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed (code ' . $file['error'] . ').';
        } elseif (!in_array($mime, $allowed_types)) {
            $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
        } elseif ($file['size'] > MAX_SIZE) {
            $errors[] = 'Image must be under 5 MB.';
        } else {
            $new_name = bin2hex(random_bytes(12)) . '.' . $orig_ext;
            if (move_uploaded_file($tmp, UPLOAD_DIR . $new_name)) {
                if ($project['image'] && file_exists(UPLOAD_DIR . $project['image'])) {
                    unlink(UPLOAD_DIR . $project['image']);
                }
                $image_name = $new_name;
            } else {
                $errors[] = 'Could not save the uploaded file. Check folder permissions.';
            }
        }
    }

    if (empty($errors)) {
        db()->prepare(
            "UPDATE projects SET title=?, description=?, image=?, status=? WHERE id=?"
        )->execute([$title, $description, $image_name, $status, $id]);

        header('Location: /admin/dashboard?saved=1');
        exit;
    }

    $project['title']       = $title;
    $project['description'] = $description;
    $project['status']      = $status;
}

require_once '_header.php';
?>

<div class="admin-page-title">Edit Project</div>
<p class="admin-page-sub">Editing: <strong style="color:var(--gold)"><?= htmlspecialchars($project['title']) ?></strong></p>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
            <div>⚠ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="admin-card" style="max-width:680px;">
    <h3>Project Details</h3>
    <form method="POST" action="/admin/edit-project?id=<?= $id ?>" enctype="multipart/form-data">

        <div class="form-group">
            <label for="title">Project Title *</label>
            <input type="text" id="title" name="title"
                   value="<?= htmlspecialchars($project['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Current Image</label>
            <?php if ($project['image'] && file_exists('../uploads/projects/'.$project['image'])): ?>
                <div style="margin-bottom:12px;">
                    <img src="/uploads/projects/<?= htmlspecialchars($project['image']) ?>"
                         alt="Current"
                         style="max-width:260px; border-radius:6px; border:1px solid var(--dark-4);">
                </div>
            <?php else: ?>
                <p style="color:var(--muted); font-size:13px; margin-bottom:12px;">No image uploaded.</p>
            <?php endif; ?>
            <label for="image">Replace Image (optional — JPG / PNG / WebP, max 5 MB)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="form-group">
            <label for="status">Visibility</label>
            <select id="status" name="status">
                <option value="active" <?= $project['status']==='active' ? 'selected':'' ?>>Active — visible on website</option>
                <option value="hidden" <?= $project['status']==='hidden' ? 'selected':'' ?>>Hidden — not visible</option>
            </select>
        </div>

        <div style="display:flex; gap:12px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Update Project</button>
            <a href="/admin/dashboard" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require_once '_footer.php'; ?>