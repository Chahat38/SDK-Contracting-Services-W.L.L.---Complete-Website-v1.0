<?php
require_once 'includes/db.php';
$hash = password_hash('admin123', PASSWORD_BCRYPT);
db()->prepare("UPDATE admins SET password_hash=? WHERE username='admin'")->execute([$hash]);
echo "Done! Password is now: admin123";
?>