<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'داشبورد وکیل' }} - سامانه وکلای ایران</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* ======================================== */
        /* متغیرهای اصلی */
        /* ======================================== */
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #e63946;
            --sidebar-width: 250px;
            --header-height: 70px;

            /* رنگ‌های حالت روز */
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --bg-header: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
        }

        /* ======================================== */
        /* حالت شب - مهمترین بخش */
        /* ======================================== */
        [data-theme="dark"] {
            --bg-body: #1a1a2e;
            --bg-card: #16213e;
            --bg-header: #0f3460;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        /* استایل پایه */
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ======================================== */
        /* Sidebar */
        /* ======================================== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }

        [data-theme="dark"] .sidebar {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-right-color: white;
            padding-right: 25px;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* ======================================== */
        /* Main Content */
        /* ======================================== */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* ======================================== */
        /* Header */
        /* ======================================== */
        .header {
            height: var(--header-height);
            background: var(--bg-header);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: background 0.3s ease;
        }

        .header h4, .header small {
            color: var(--text-primary);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .theme-switcher {
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-card);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .theme-switcher:hover {
            background: var(--border-color);
        }

        /* ======================================== */
        /* Content Area */
        /* ======================================== */
        .content-wrapper {
            padding: 2rem;
        }

        /* ======================================== */
        /* Cards */
        /* ======================================== */
        .card, .stat-card, .quick-action-card, .form-card {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: var(--text-primary) !important;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }

        .card-header {
            background: transparent !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .card-title {
            color: var(--text-primary) !important;
        }

        .card-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        /* Mobile Menu Button */
        .mobile-menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1101;
            display: none;
        }

        .mobile-menu-toggle .btn {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block !important;
            }

            .header {
                padding-left: 70px;
            }
        }



        /* ======================================== */
        /* Stats Icons */
        /* ======================================== */
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-icon.inc1ome { background: rgba(45, 124, 1, 0.24); color: var(--primary-color); }
        .stat-icon.income { background: rgba(76, 201, 240, 0.1); color: var(--success-color); }
        .stat-icon.calls { background: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
        .stat-icon.wallet { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }

        [data-theme="dark"] .stat-icon.incom1e { background: rgba(102, 126, 234, 0.2); color: #818cf8; }
        [data-theme="dark"] .stat-icon.income { background: rgba(76, 201, 240, 0.2); color: #67e8f9; }
        [data-theme="dark"] .stat-icon.calls { background: rgba(248, 150, 30, 0.2); color: #fdba74; }
        [data-theme="dark"] .stat-icon.wallet { background: rgba(168, 85, 247, 0.2); color: #c084fc; }

        /* ======================================== */
        /* Tables */
        /* ======================================== */
        .table {
            color: var(--text-primary) !important;
        }

        .table th {
            background-color: var(--bg-body) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background-color: var(--bg-body) !important;
        }

        /* ======================================== */
        /* Badges */
        /* ======================================== */
        .badge-view { background: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
        .badge-click { background: rgba(76, 201, 240, 0.1); color: var(--success-color); }
        .badge-call { background: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
        .badge-deposit { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .badge-withdrawal { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

        [data-theme="dark"] .badge-view { background: rgba(102, 126, 234, 0.2); color: #818cf8; }
        [data-theme="dark"] .badge-click { background: rgba(76, 201, 240, 0.2); color: #67e8f9; }
        [data-theme="dark"] .badge-call { background: rgba(248, 150, 30, 0.2); color: #fdba74; }
        [data-theme="dark"] .badge-deposit { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
        [data-theme="dark"] .badge-withdrawal { background: rgba(239, 68, 68, 0.2); color: #f87171; }

        /* ======================================== */
        /* Forms */
        /* ======================================== */
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            background-color: var(--bg-card);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            background-color: var(--bg-card);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        /* ======================================== */
        /* Buttons */
        /* ======================================== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* ======================================== */
        /* Button Groups */
        /* ======================================== */
        .btn-group .btn {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-group .btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-group .btn:hover:not(.active) {
            background-color: var(--bg-body);
        }

        /* ======================================== */
        /* Text Utilities */
        /* ======================================== */
        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* ======================================== */
        /* Responsive */
        /* ======================================== */
        @media (max-width: 768px) {
            .sidebar {
                right: -100%;
            }

            .sidebar.show {
                right: 0;
            }

            .main-content {
                margin-right: 0;
            }

            .content-wrapper {
                padding: 1rem;
            }
        }

        /* ======================================== */
        /* Notifications */
        /* ======================================== */
        .notification {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ======================================== */
        /* List Group */
        /* ======================================== */
        .list-group-item {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .list-group-item:hover {
            background-color: var(--bg-body);
        }

        /* ======================================== */
        /* Nav Tabs */
        /* ======================================== */
        .nav-tabs {
            border-bottom-color: var(--border-color);
        }

        .nav-tabs .nav-link {
            color: var(--text-secondary);
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* ======================================== */
        /* Quick Action Cards */
        /* ======================================== */
        .quick-action-card {
            text-decoration: none;
            display: block;
        }

        .quick-action-card:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.05)) !important;
            transform: translateY(-5px);
        }

        .quick-action-card h6 {
            color: var(--text-primary);
        }

        [data-theme="dark"] .quick-action-card:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2)) !important;
        }
    </style>
</head>
<body>
{{--<div class="sidebar-overlay" id="sidebarOverlay"></div>--}}
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="bi bi-shield-check"></i>
            وکیل من
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-item">
            <a href="{{ route('lawyer.dashboard') }}" class="nav-link {{ request()->routeIs('lawyer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                داشبورد
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('lawyer.clicks') }}" class="nav-link {{ request()->routeIs('lawyer.clicks') ? 'active' : '' }}">
                <i class="bi bi-mouse"></i>
                تاریخچه کلیک‌ها
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('lawyer.skills') }}" class="nav-link {{ request()->routeIs('lawyer.skills') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                مهارت‌ها
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('lawyer.wallet') }}" class="nav-link {{ request()->routeIs('lawyer.wallet') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                کیف پول
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('lawyer.profile') }}" class="nav-link {{ request()->routeIs('lawyer.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                پروفایل
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="bi bi-house-door"></i>
                بازگشت به سایت
            </a>
        </div>

        <div class="nav-item mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger" style="background: none; border: none; width: 100%; text-align: right;">
                    <i class="bi bi-box-arrow-left"></i>
                    خروج از حساب
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <header class="header">
        <div>
            <h4 class="mb-0 fw-bold">{{ $title ?? 'داشبورد' }}</h4>
            <small class="text-muted">
                {{ auth()->user()->lawyer->user->name ?? auth()->user()->name }}
            </small>
        </div>

        <div class="header-right">
            <!-- Theme Switcher -->
            <div class="theme-switcher" id="themeToggle">
                <i class="bi bi-moon-stars"></i>
            </div>

            <!-- User Profile -->
            <div class="user-profile" id="userDropdown">
                <div class="user-avatar">
                    @if(auth()->user()->lawyer->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->lawyer->avatar) }}" alt="Avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">وکیل</small>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="content-wrapper">
        {{ $slot }}
    </div>
</div>

<!-- Mobile Menu Toggle -->
<div class="mobile-menu-toggle">
    <button class="btn btn-primary" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
</div>

<!-- Notifications Container -->
<div id="notifications"></div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Theme Switching
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const themeIcon = themeToggle.querySelector('i');
            const currentTheme = localStorage.getItem('theme');

            if (currentTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                if (themeIcon) themeIcon.className = 'bi bi-sun';
            }

            themeToggle.addEventListener('click', () => {
                if (document.body.getAttribute('data-theme') === 'dark') {
                    document.body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    if (themeIcon) themeIcon.className = 'bi bi-moon-stars';
                } else {
                    document.body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    if (themeIcon) themeIcon.className = 'bi bi-sun';
                }
            });
        }

        // Mobile Sidebar Toggle
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();

                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });


        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (
                window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)
            ) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Livewire notifications
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (event) => {
            showNotification(event.type, event.message);
        });
    });

    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.getElementById('notifications').appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Auto-refresh wallet balance every 30 seconds
    if (window.location.pathname.includes('wallet') || window.location.pathname.includes('dashboard')) {
        setInterval(() => {
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('$refresh');
            }
        }, 30000);
    }
</script>

{{ $scripts ?? '' }}
</body>
</html>
