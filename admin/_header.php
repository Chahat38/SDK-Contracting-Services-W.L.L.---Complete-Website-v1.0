<?php
$admin_page = basename($_SERVER['PHP_SELF']);
$base = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Admin') ?> | SDK Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=IBM+Plex+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
    
     <style>
        
        /* Hide public website header/nav/footer from admin */
        .admin-body .site-header,
        .admin-body .hero,
        .admin-body .site-footer,
        .admin-body .whatsapp-float,
        .admin-body .main-nav {
            display: none !important;
        }
        
        /* Reset section backgrounds for admin only */
        .admin-body section,
        .admin-body section:first-of-type,
        .admin-body section:nth-of-type(odd),
        .admin-body section:nth-of-type(even) {
            background: transparent !important;
        }
        
        /* ========== ADMIN HEADER ========== */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #1E1B4B, #312E81, #4F46E5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.25);
        }
        
        /* Logo Section */
        .admin-header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .admin-header .logo img {
            height: 48px;
            width: auto;
            filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.3));
        }
        
        .admin-header .logo-text {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1rem;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1.2;
        }
        
        .admin-header .logo-text small {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.6rem;
            font-weight: 600;
            color: #38BDF8;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            display: block;
            margin-top: 2px;
        }
        
        /* Sidebar Toggle Button */
        #sidebarToggle {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 20px;
            cursor: pointer;
            color: #FFFFFF;
            transition: all 0.25s;
        }
        
        #sidebarToggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
        }
        
        /* Admin Meta (User Info) */
        .admin-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 18px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.15);
             
        }
        
        .admin-name {
            color: #FFFFFF;
            font-weight: 600;
            font-size: 13px;
        }
        
        .admin-name span {
            color: #38BDF8;
            font-weight: 700;
        }
        
        .divider {
            color: rgba(255, 255, 255, 0.3);
        }
        
        .icon-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .icon-link:hover {
            color: #FFFFFF;
            transform: translateY(-1px);
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: #FFFFFF;
            padding: 5px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }
        
        /* ========== ADMIN LAYOUT ========== */
        .admin-layout {
            display: flex;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }
        
        /* ========== SIDEBAR - DARK THEME ========== */
        .admin-sidebar {
            width: 270px;
            flex-shrink: 0;
            background: #0F172A;
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 99;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Sidebar Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 3px;
        }
        
        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        
        /* Sidebar Headings */
        .admin-sidebar h5 {
            color: #818CF8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 20px 20px 8px 20px;
            margin: 0;
        }
        
        /* Sidebar Links */
        .admin-sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            margin: 4px 12px;
            border-radius: 12px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.25s;
        }
        
        .admin-sidebar a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            transform: translateX(4px);
        }
        
        .admin-sidebar a.active {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.3), rgba(124, 58, 237, 0.15));
            color: #FFFFFF;
        }
        
        .admin-sidebar .nav-icon {
            font-size: 18px;
            min-width: 28px;
        }
        
        /* ========== MAIN CONTENT ========== */
        .admin-main {
            flex: 1;
            margin-left: 270px;
            padding: 24px 32px;
            background: #F1F5F9;
            min-height: calc(100vh - 70px);
        }
        
        /* ========== RESPONSIVE ========== */
        @media (max-width: 860px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 260px;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
                padding: 20px;
            }
            
            .admin-header {
                padding: 0 16px;
            }
            
            .admin-header .logo-text {
                display: none;
            }
        }
        
        @media (max-width: 640px) {
            .admin-meta {
                padding: 4px 12px;
                gap: 8px;
            }
            
            .admin-name {
                font-size: 11px;
            }
            
            .admin-meta .divider:first-of-type {
                display: none;
            }
        }
        
        /* Overlay for mobile sidebar */
        #adminOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
            z-index: 98;
            display: none;
        }
        
        #adminOverlay.active {
            display: block;
        }
 
        
.admin-header .logo {
    margin-left: 40px;
}

    </style> 
</head>
<body class="admin-body">

<!-- Sidebar Overlay -->
<div id="adminOverlay" onclick="closeSidebar()"></div>

<header class="admin-header">
    <div style="display: flex; align-items: center; gap: 16px;">
        <button id="sidebarToggle" onclick="toggleSidebar()" aria-label="Menu" style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:10px;padding:8px 12px;font-size:20px;cursor:pointer;color:#fff;">☰</button>
        <a href="/admin/dashboard" class="logo">
            <img src="/images/logo.png" alt="SDK Contracting">
            <span class="logo-text">
                SDK Contracting
                <small>Admin Panel</small>
            </span>
        </a>
    </div>
    
    <div class="admin-meta">
        <span class="admin-name">
            👋 <span><?= htmlspecialchars(current_admin()) ?></span>
        </span>
        <span class="divider">|</span>
        <a href="/" target="_blank" class="icon-link" title="View Website">
            🌐
        </a>
        <span class="divider">|</span>
        <a href="/admin/logout" class="logout-btn">
            🔒 Logout
        </a>
    </div>
</header>

<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <h5>Main</h5>
        <a href="/admin/dashboard" class="<?= $admin_page==='dashboard'?'active':'' ?>">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <h5>Projects</h5>
        <a href="/admin/add-project" class="<?= $admin_page==='add-project'?'active':'' ?>">
            <span class="nav-icon">➕</span> Add Project
        </a>
        <a href="/admin/dashboard.php#projects">
            <span class="nav-icon">📁</span> All Projects
        </a>

        <h5>Services</h5>
        <a href="/admin/services" class="<?= $admin_page==='services'?'active':'' ?>">
            <span class="nav-icon">🔧</span> All Services
        </a>
        <a href="/admin/add-service" class="<?= $admin_page==='add-service'?'active':'' ?>">
            <span class="nav-icon">➕</span> Add Service
        </a>

        <h5>Home Page</h5>
        <a href="/admin/home-settings" class="<?= $admin_page==='home-settings'?'active':'' ?>">
            <span class="nav-icon">🏠</span> Hero & Stats
        </a>
        <a href="/admin/whatsapp-settings" class="<?= $admin_page==='whatsapp-settings'?'active':'' ?>">
            <span class="nav-icon">💬</span> WhatsApp
        </a>

        <h5>Enquiries</h5>
        <a href="/admin/view-messages" class="<?= $admin_page==='view-messages'?'active':'' ?>">
            <span class="nav-icon">✉️</span> Messages
            <?php
            try {
                $u = db()->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
                if ($u>0) echo "<span style='background:linear-gradient(135deg, #4F46E5, #7C3AED);color:#fff;border-radius:100px;font-size:10px;font-weight:700;padding:2px 8px;margin-left:auto;'>{$u}</span>";
            } catch(Exception $e) {}
            ?>
        </a>

        <h5>Security</h5>
        <a href="/admin/change-password" class="<?= $admin_page==='change-password'?'active':'' ?>">
            <span class="nav-icon">🔐</span> Change Password
        </a>

        <h5>Site</h5>
        <a href="/" target="_blank">
            <span class="nav-icon">🌐</span> View Website
        </a>
        <a href="/admin/logout">
            <span class="nav-icon">🔒</span> Logout
        </a>
    </aside>

    <div class="admin-main">

<script>
function toggleSidebar() {
    const s = document.getElementById('adminSidebar');
    const o = document.getElementById('adminOverlay');
    const isOpen = s.classList.contains('open');
    
    if (!isOpen) {
        s.classList.add('open');
        o.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        closeSidebar();
    }
}

function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('adminOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

// Close sidebar on link click (mobile)
document.querySelectorAll('.admin-sidebar a').forEach(a => {
    a.addEventListener('click', () => { 
        if (window.innerWidth <= 860) {
            closeSidebar();
        }
    });
});

// Close sidebar on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});
</script>