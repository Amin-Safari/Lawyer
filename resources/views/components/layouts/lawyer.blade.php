<!-- resources/views/components/layouts/lawyer.blade.php -->
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

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #e63946;
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
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

        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
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
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .theme-switcher:hover {
            background: #e9ecef;
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }

        .card-header {
            background: transparent;
            border-bottom: none;
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .card-body {
            padding: 1rem 1.5rem 1.5rem;
        }

        /* Stats Icons */
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

        .stat-icon.views { background: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
        .stat-icon.clicks { background: rgba(76, 201, 240, 0.1); color: var(--success-color); }
        .stat-icon.calls { background: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
        .stat-icon.cost { background: rgba(230, 57, 70, 0.1); color: var(--danger-color); }
        .stat-icon.wallet { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }

        /* Tables */
        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-view { background: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
        .badge-click { background: rgba(76, 201, 240, 0.1); color: var(--success-color); }
        .badge-call { background: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
        .badge-deposit { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .badge-withdrawal { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px 10px 0 0;
            margin-bottom: -2px;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Forms */
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        [data-theme="dark"] body {
            background-color: #1a202c;
            color: #e2e8f0;
        }

        [data-theme="dark"] .sidebar {
            background: linear-gradient(135deg, #4a5568, #2d3748);
        }

        [data-theme="dark"] .header {
            background-color: #2d3748;
            box-shadow: 0 2px 15px rgba(0,0,0,0.3);
        }

        [data-theme="dark"] .content-wrapper {
            background-color: #1a202c;
        }

        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .data-table,
        [data-theme="dark"] .form-card {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        [data-theme="dark"] .table th {
            background-color: #4a5568;
            color: #e2e8f0;
        }

        [data-theme="dark"] .table td {
            background-color: #2d3748;
            color: #e2e8f0;
            border-color: #4a5568;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #4a5568;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        /* Responsive */
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

        /* Notifications */
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
    </style>
</head>
<body>
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
<div class="mobile-menu-toggle d-none">
    <button class="btn btn-primary" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
</div>

<!-- Notifications Container -->
<div id="notifications"></div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Theme Switching
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle.querySelector('i');

    const currentTheme = localStorage.getItem('theme');

    if (currentTheme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
        themeIcon.className = 'bi bi-sun';
    }

    themeToggle.addEventListener('click', () => {
        if (document.body.getAttribute('data-theme') === 'dark') {
            document.body.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
            themeIcon.className = 'bi bi-moon-stars';
        } else {
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            themeIcon.className = 'bi bi-sun';
        }
    });

    // Mobile Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            e.target !== sidebarToggle) {
            sidebar.classList.remove('show');
        }
    });

    // Livewire notifications
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (event) => {
            showNotification(event.type, event.message);
        });
    });

    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification alert alert-${type} alert-dismissible fade show`;
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

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

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
