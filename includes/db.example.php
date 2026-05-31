<?php
// ============================================================
// SETUP INSTRUCTIONS:
// 1. Copy this file: cp db.example.php db.php
// 2. Fill in your actual database credentials below
// 3. Never commit db.php to GitHub!
// ============================================================

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host   = 'localhost';
        $dbname = 'YOUR_DB_NAME';      // e.g. u945573062_sdk
        $user   = 'YOUR_DB_USER';      // e.g. u945573062_sdk
        $pass   = 'YOUR_DB_PASSWORD';  // your database password
        $dsn     = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:40px;color:#c0392b">
                <h2>Database Error</h2>
                <p>Please check your credentials in includes/db.php</p>
            </div>');
        }
    }
    return $pdo;
}

function setting(string $key, string $default = ''): string {
    static $cache = null;
    if ($cache === null) {
        try {
            $rows  = db()->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
            $cache = [];
            foreach ($rows as $r) $cache[$r['setting_key']] = $r['setting_value'];
        } catch (Exception $e) {
            $cache = [];
        }
    }
    return $cache[$key] ?? $default;
}
