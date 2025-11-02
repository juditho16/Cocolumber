<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine active page
$current = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
if (!function_exists('active')) {
    function active($slug, $current)
    {
        return $slug === $current ? 'active' : '';
    }
}

// Define absolute base (adjust if your folder differs)
$base = "/cocolumber/admin/";

// Display name: prefer a more complete name if available (set it on login), else username
$displayName = htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mahayag Lumber Admin Panel</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --sidebar-bg: #164e3f;
            --sidebar-text: #e2e8f0;
            --sidebar-accent: rgba(255,255,255,0.95);
            --topbar-bg: #0f766e;
            --content-bg: #f1f5f9;
        }
        /* Dark mode variables */
        body.dark {
            --sidebar-bg: #0b2f26;
            --sidebar-text: #dbe9e4;
            --sidebar-accent: #ffffff;
            --topbar-bg: #0b4b41;
            --content-bg: #0f1720;
            color-scheme: dark;
        }

        body { font-family: "Segoe UI", sans-serif; background: var(--content-bg); margin:0; }

        /* Sidebar */
        .sidebar {
            width: 260px !important;
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display:flex;
            flex-direction:column;
            padding:1rem;
            transition: width 0.25s ease, background-color 0.2s;
            overflow-x:hidden;
            z-index:1030;
        }

        .brand {
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:.35rem;
            margin-bottom:1.25rem;
            cursor:pointer;
            padding-bottom: .5rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        /* Circle logo container */
        .brand .logo-wrap {
            width:100px;
            height:100px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: rgba(255,255,255,0.06);
            border-radius:50%;
            overflow:hidden;
            border:2px solid rgba(255,255,255,0.08);
            transition: transform 0.3s ease;
            margin-bottom:12px;
        }

        .brand:hover .logo-wrap {
            transform: scale(1.08);
        }

        /* Logo image */
        .circle-logo {
            width:120%;
            height:120%;
            object-fit:cover;
            border-radius:50%;
        }

        /* Auto shrink when collapsed */
        #sidebar.collapsed .brand .logo-wrap {
            width:50px;
            height:50px;
        }

        .brand h5 {
            margin:0;
            font-size:14px;
            font-weight:700;
            color:var(--sidebar-accent);
            text-align:center;
            line-height:1.05;
        }
        .brand p {
            margin:0;
            font-size:12px;
            color: rgba(255,255,255,0.8);
            text-align:center;
        }

        /* Nav */
        .nav .nav-link {
            color: #cbd5e1;
            border-radius: .5rem;
            padding: .7rem .9rem;
            display:flex;
            align-items:center;
            gap:.75rem;
            transition: transform 180ms ease, background-color 160ms ease, color 160ms ease;
            transform-origin: left center;
            box-sizing: border-box;
            position: relative;
        }

        .nav .nav-link:hover {
            transform: scale(1.03);
            background-color: rgba(255,255,255,0.04);
            color: #fff;
            text-decoration: none;
        }

        .nav .nav-link.active {
            background-color: rgba(255,255,255,0.06);
            color: #fff;
            border-left: 6px solid var(--sidebar-accent);
            padding-left: calc(.9rem - 4px);
        }

        .nav .nav-link i { font-size: 1.1rem; min-width: 22px; text-align:center; }

        /* user area */
        .sidebar .user-block {
            margin-top: 1rem;
            padding: .6rem;
            color: var(--sidebar-text);
            font-size: 0.95rem;
        }
        .sidebar .user-block .name { font-weight:700; color:var(--sidebar-accent); }
        .sidebar .user-block .actions { margin-top:.5rem; display:flex; gap:.5rem; align-items:center; }

        .btn-logout {
            width:100%;
            border:1px solid rgba(255,255,255,0.08);
            color:var(--sidebar-text);
            background: transparent;
            padding:.45rem .6rem;
        }

        .toggle-dark {
            display:flex;
            align-items:center;
            gap:.5rem;
            padding:.35rem .5rem;
            border-radius:8px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.03);
            color: var(--sidebar-text);
            cursor:pointer;
            font-size: .9rem;
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 56px;
            background-color: var(--topbar-bg);
            color: #fff;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 0 1.25rem;
            transition: left 0.25s ease, background-color 0.15s;
            z-index:1020;
        }

        #sidebar.collapsed { width: 84px !important; }
        #sidebar.collapsed ~ .topbar { left:84px !important; }

        .topbar .welcome { font-size: .95rem; color: rgba(255,255,255,0.95); }

        /* Content */
        .content { margin-left:260px; padding:1.8rem; transition: margin-left 0.25s; margin-top:56px; }

        #sidebar.collapsed ~ .content { margin-left:84px; }

        /* responsive */
        @media (max-width: 992px) {
            .sidebar { left:-260px; position:fixed; }
            .sidebar.show { left:0; }
            .topbar { left:0 !important; }
            .content { margin-left:0 !important; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div id="sidebar" class="sidebar" role="navigation" aria-label="Main navigation">
        <div class="brand" id="sidebarToggle" title="Toggle sidebar">
            <div class="logo-wrap" aria-hidden="true">
                <img src="<?php echo $base; ?>pictures/logo1.png" alt="Logo" class="circle-logo">

            </div>

            <h5><strong style="font-weight:800; font-size:16px; margin-top: 4px;">LUMBER OPERATION<br>MANAGEMENT SYSTEM</strong></h5>
            <p style="margin-top:-1px; margin-bottom:0; font-size:15px; color: rgba(255,255,255,0.8);">
                Mahayag, Zamboanga del Sur
            </p>
        </div>

        <ul class="nav nav-pills flex-column mb-auto gap-2" role="menu" aria-label="Sidebar">
            <li role="none">
                <a role="menuitem" href="<?php echo $base; ?>index.php?page=dashboard" class="nav-link <?php echo active('dashboard', $current); ?>">
                    <i class="bi bi-speedometer2" aria-hidden="true"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <li role="none">
                <a role="menuitem" href="<?php echo $base; ?>index.php?page=inventory" class="nav-link <?php echo active('inventory', $current); ?>">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    <span class="sidebar-text">Inventory</span>
                </a>
            </li>

            <li role="none">
                <a role="menuitem" href="<?php echo $base; ?>index.php?page=suppliers" class="nav-link <?php echo active('suppliers', $current); ?>">
                    <i class="bi bi-truck" aria-hidden="true"></i>
                    <span class="sidebar-text">Suppliers</span>
                </a>
            </li>

            <li role="none">
                <a role="menuitem" href="<?php echo $base; ?>index.php?page=workers" class="nav-link <?php echo active('workers', $current); ?>">
                    <i class="bi bi-people-fill" aria-hidden="true"></i>
                    <span class="sidebar-text">Workers</span>
                </a>
            </li>

            <li role="none">
                <a role="menuitem" href="<?php echo $base; ?>index.php?page=cutting_jobs" class="nav-link <?php echo active('cutting_jobs', $current); ?>">
                    <i class="bi bi-scissors" aria-hidden="true"></i>
                    <span class="sidebar-text">Cutting Jobs</span>
                </a>
            </li>
        </ul>

        <div class="user-block">
            <div class="name"><?php echo $displayName; ?></div>
            <div class="actions">
                <form method="POST" action="<?php echo $base; ?>functions/logout.php" style="display:inline;">
                    <button type="submit" class="btn btn-logout" aria-label="Logout">
                        <i class="bi bi-box-arrow-right"></i>&nbsp; Log out
                    </button>
                </form>

                <button id="darkToggle" class="toggle-dark" aria-pressed="false" title="Toggle dark mode">
                    <i id="darkIcon" class="bi bi-moon"></i>
                    <span id="darkLabel">Dark</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TOPBAR -->
    <div class="topbar" role="banner">
        <div class="d-flex align-items-center gap-3">
            <button id="menuBtn" class="btn btn-sm btn-light d-lg-none" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="welcome">Welcome, <strong><?php echo $displayName; ?></strong></div>
        </div>
    </div>

    <!-- CONTENT WRAPPER -->
    <div id="content" class="content" role="main">

<script>
(function(){
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const menuBtn = document.getElementById('menuBtn');

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    });

    menuBtn?.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });

    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    const darkToggle = document.getElementById('darkToggle');
    const darkIcon = document.getElementById('darkIcon');
    const darkLabel = document.getElementById('darkLabel');

    function applyDark(enabled){
        if (enabled) {
            document.body.classList.add('dark');
            darkIcon.className = 'bi bi-sun';
            darkLabel.textContent = 'Light';
            darkToggle.setAttribute('aria-pressed','true');
        } else {
            document.body.classList.remove('dark');
            darkIcon.className = 'bi bi-moon';
            darkLabel.textContent = 'Dark';
            darkToggle.setAttribute('aria-pressed','false');
        }
    }

    const stored = localStorage.getItem('mahayag-dark');
    applyDark(stored === '1');

    darkToggle?.addEventListener('click', () => {
        const isDark = document.body.classList.toggle('dark');
        localStorage.setItem('mahayag-dark', isDark ? '1' : '0');
        applyDark(isDark);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key.toLowerCase() === 'd' && (e.ctrlKey || e.metaKey)) {
            darkToggle.click();
        }
    });
})();
</script>
