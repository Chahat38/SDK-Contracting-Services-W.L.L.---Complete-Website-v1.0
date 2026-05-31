<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'use_strict_mode' => true,
    ]);
}
function admin_login(string $username, string $password): bool {
    $stmt = db()->prepare("SELECT id, username, password_hash FROM admins WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        return true;
    }
    return false;
}
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login');
        exit;
    }
}
function current_admin(): string {
    return $_SESSION['admin_user'] ?? 'Admin';
}
function admin_logout(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: /admin/login');
    exit;
}
function get_setting(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        try {
            $s = db()->prepare("SELECT setting_value FROM settings WHERE setting_key=? LIMIT 1");
            $s->execute([$key]);
            $v = $s->fetchColumn();
            $cache[$key] = ($v !== false) ? $v : $default;
        } catch (Exception $e) {
            $cache[$key] = $default;
        }
    }
    return $cache[$key];
}