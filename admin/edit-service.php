<?php
// redirects to add-service.php in edit mode
$id = (int)($_GET['id'] ?? 0);
header("Location: /admin/add-service?id={$id}");
exit;