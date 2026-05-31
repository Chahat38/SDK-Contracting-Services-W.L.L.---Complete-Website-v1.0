<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_admin();

$page_title = 'Add Project';
$errors = [];

define('UPLOAD_DIR', __DIR__ . '/../uploads/projects/');
define('MAX_SIZE',   5 * 1024 * 1024);
$allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = in_array($_POST['status'] ?? '', ['active','hidden']) ? $_POST['status'] : 'active';

    if (!$title) $errors[] = 'Project title is required.';

    $image_name = null;

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
            $image_name = bin2hex(random_bytes(12)) . '.' . $orig_ext;
            if (!move_uploaded_file($tmp, UPLOAD_DIR . $image_name)) {
                $errors[] = 'Could not save the uploaded file. Check folder permissions.';
                $image_name = null;
            }
        }
    }

    if (empty($errors)) {
        db()->prepare(
            "INSERT INTO projects (title, description, image, status) VALUES (?, ?, ?, ?)"
        )->execute([$title, $description, $image_name, $status]);

        header('Location: /admin/dashboard?saved=1');
        exit;
    }
}

require_once '_header.php';
?>

<div class="admin-page-title">Add New Project</div>
<p class="admin-page-sub">Fill in the details below and upload a project image.</p>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?>
            <div>⚠ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="admin-card" style="max-width:680px;">
    <h3>Project Details</h3>
    <form method="POST" action="/admin/add-project" enctype="multipart/form-data">

        <div class="form-group">
            <label for="title">Project Title *</label>
            <input type="text" id="title" name="title"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                   placeholder="e.g. Al Wakra Villa Complex" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5"
                      placeholder="Brief summary of the project..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Project Image (JPG / PNG / WebP, max 5 MB)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="form-group">
            <label for="status">Visibility</label>
            <select id="status" name="status">
                <option value="active"  <?= ($_POST['status']??'active')==='active' ? 'selected':'' ?>>Active — visible on website</option>
                <option value="hidden"  <?= ($_POST['status']??'')==='hidden'        ? 'selected':'' ?>>Hidden — not visible</option>
            </select>
        </div>

        <div style="display:flex; gap:12px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">Save Project</button>
            <a href="/admin/dashboard" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require_once '_footer.php'; ?>