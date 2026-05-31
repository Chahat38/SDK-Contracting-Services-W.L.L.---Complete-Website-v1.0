<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$base = '';
$current_page = basename($_SERVER['PHP_SELF']);
$wa_enabled = setting('whatsapp_enabled', '1');
$wa_number  = setting('whatsapp_number', '97466927592');
$wa_message = urlencode(setting('whatsapp_message', 'Hello SDK Contracting & Services!'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'SDK Contracting & Services') ?> | SDK Contracting & Services W.L.L. Qatar</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="192x192" href="/favicon-192x192.png">
    <meta name="description" content="SDK Contracting & Services W.L.L. — Qatar's trusted partner for construction, cleaning, legal advisory, typing & translation services. Reg. No. 207895.">
    <meta name="keywords" content="construction Qatar, contractor Qatar, cleaning services Qatar, legal advisor Qatar, typing translation Qatar, building materials Qatar, villa construction Qatar">
    <meta property="og:title" content="SDK Contracting & Services W.L.L. Qatar">
    <meta property="og:description" content="Construction, Cleaning, Legal Advisory & More — All Under One Roof in Qatar.">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="<?= $base ?>/index.php" class="logo">
    <img src="<?= $base ?>/images/logo.png?v=3" alt="SDK Contracting & Services W.L.L. Qatar" class="logo-img">
    <span class="logo-text-mobile">SDK Contracting
        <small>& Services W.L.L. Qatar</small>
    </span>
</a>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
        <nav class="main-nav" id="mainNav" style="font-size:15px;">
            <a href="<?= $base ?>/index.php"    class="<?= $current_page==='index.php'    ? 'active':'' ?>">Home</a>
            <a href="<?= $base ?>/about.php"    class="<?= $current_page==='about.php'    ? 'active':'' ?>">About</a>
            <a href="<?= $base ?>/services.php" class="<?= $current_page==='services.php' ? 'active':'' ?>">Services</a>
            <a href="<?= $base ?>/projects.php" class="<?= $current_page==='projects.php' ? 'active':'' ?>">Projects</a>
            <a href="<?= $base ?>/contact.php"  class="<?= $current_page==='contact.php'  ? 'active':'' ?> btn-nav">Contact Us</a>
        </nav>
    </div>
</header>

<?php if ($wa_enabled): ?>
<div class="whatsapp-float" style="bottom:20px;right:20px;">
    <span class="whatsapp-tooltip">Chat on WhatsApp</span>
    <a href="https://wa.me/<?= htmlspecialchars($wa_number) ?>?text=<?= $wa_message ?>"
       class="whatsapp-btn" target="_blank" rel="noopener" aria-label="WhatsApp">
        <span class="pulse-ring"></span>
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</div>
<?php endif; ?>

<main>
