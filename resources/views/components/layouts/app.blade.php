<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'سامانه وکلای ایران' }} - سامانه وکلای ایران</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* ======================================== */
        /* متغیرهای اصلی - حالت روز (پیش‌فرض) */
        /* ======================================== */
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #e63946;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            /* رنگ‌های حالت روز */
            --bg-body: #f8f9fa;
            --bg-navbar: #ffffff;
            --bg-card: #ffffff;
            --bg-dropdown: #ffffff;
            --text-primary: #0a0a0a;
            --text-secondary: #6c757d;
            --text-nav: rgba(0, 0, 0, 0.7);
            --text-nav-hover: #000000;
            --border-color: #dee2e6;
            --dropdown-bg: #ffffff;
            --dropdown-text: #212529;
            --dropdown-hover: #f8f9fa;
        }

        /* ======================================== */
        /* متغیرهای حالت شب */
        /* ======================================== */
        [data-theme="dark"] {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            /* رنگ‌های حالت شب */
            --bg-body: #1a202c;
            --bg-navbar: #2d3748;
            --bg-card: #2d3748;
            --bg-dropdown: #2d3748;
            --text-primary: #e2e8f0;
            --text-secondary: #a0aec0;
            --text-nav: rgba(255, 255, 255, 0.7);
            --text-nav-hover: #ffffff;
            --border-color: #4a5568;
            --dropdown-bg: #2d3748;
            --dropdown-text: #e2e8f0;
            --dropdown-hover: #4a5568;
        }

        /* ======================================== */
        /* استایل‌های پایه */
        /* ======================================== */
        body {
            background-color: var(--bg-body);
            color: var(--text-primary);
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ======================================== */
        /* Navbar */
        /* ======================================== */
        .custom-navbar {
            background: var(--bg-navbar);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        [data-theme="dark"] .custom-navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            background: var(--gradient-bg);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .navbar-brand i {
            -webkit-text-fill-color: var(--primary-color);
            color: var(--primary-color);
        }

        .text-nav {
            color: var(--text-nav) !important;
            transition: color 0.3s ease;
        }

        .text-nav:hover {
            color: var(--text-nav-hover) !important;
        }

        /* ======================================== */
        /* Main Content */
        /* ======================================== */
        .main-content {
            padding-top: 80px;
        }

        /* ======================================== */
        /* Cards */
        /* ======================================== */
        .card {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            transition: background 0.3s ease, color 0.3s ease, border 0.3s ease;
        }

        .card-header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .card-footer {
            background-color: var(--bg-card);
            border-top: 1px solid var(--border-color);
        }

        /* ======================================== */
        /* Buttons */
        /* ======================================== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* ======================================== */
        /* Dropdown */
        /* ======================================== */
        .dropdown-menu {
            background-color: var(--dropdown-bg);
            border-color: var(--border-color);
        }

        .dropdown-item {
            color: var(--dropdown-text);
        }

        .dropdown-item:hover {
            background-color: var(--dropdown-hover);
            color: var(--dropdown-text);
        }

        .dropdown-divider {
            border-color: var(--border-color);
        }

        /* ======================================== */
        /* Text Utilities */
        /* ======================================== */
        .text-muted {
            color: var(--text-secondary) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        /* ======================================== */
        /* Background Utilities */
        /* ======================================== */
        .bg-light {
            background-color: var(--bg-card) !important;
        }

        /* ======================================== */
        /* Border Utilities */
        /* ======================================== */
        .border {
            border-color: var(--border-color) !important;
        }

        /* ======================================== */
        /* Forms */
        /* ======================================== */
        .form-control, .form-select {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--bg-card);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .input-group-text {
            background-color: var(--bg-navbar);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        /* ======================================== */
        /* Tables */
        /* ======================================== */
        .table {
            color: var(--text-primary);
        }

        .table thead th {
            background-color: var(--bg-navbar);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .table td, .table th {
            border-color: var(--border-color);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* ======================================== */
        /* List Group */
        /* ======================================== */
        .list-group-item {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .list-group-item-action:hover {
            background-color: var(--bg-navbar);
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
            background-color: var(--bg-card);
            border-color: var(--border-color);
            border-bottom-color: var(--bg-card);
            color: var(--primary-color);
        }

        [data-theme="dark"] .nav-tabs .nav-link.active {
            background-color: var(--bg-card);
            color: var(--primary-color);
        }

        /* ======================================== */
        /* Pagination */
        /* ======================================== */
        .pagination .page-link {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination .page-link:hover {
            background-color: var(--bg-navbar);
            color: var(--primary-color);
        }

        .pagination .active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* ======================================== */
        /* Modals */
        /* ======================================== */
        .modal-content {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        .modal-header, .modal-footer {
            border-color: var(--border-color);
        }

        /* ======================================== */
        /* Alerts */
        /* ======================================== */
        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .alert-info {
            background-color: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.2);
            color: #06b6d4;
        }

        /* ======================================== */
        /* Avatar */
        /* ======================================== */
        .avatar-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ======================================== */
        /* Theme Switcher Button */
        /* ======================================== */
        .theme-switcher {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }

        .theme-toggle-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-bg);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .theme-toggle-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        /* ======================================== */
        /* Responsive */
        /* ======================================== */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 70px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }

        /* ======================================== */
        /* Animation Classes */
        /* ======================================== */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ======================================== */
        /* Custom Scrollbar for Dark Mode */
        /* ======================================== */
        [data-theme="dark"] ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        [data-theme="dark"] ::-webkit-scrollbar-track {
            background: var(--bg-navbar);
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg custom-navbar py-3">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-shield-check me-2"></i>
            سامانه وکلای ایران
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-nav" href="#">
                        <i class="bi bi-tags me-1"></i>
                        مهارت‌ها
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-nav" href="#">
                        <i class="bi bi-people me-1"></i>
                        وکلای برتر
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-nav" href="#">
                        <i class="bi bi-info-circle me-1"></i>
                        درباره ما
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown me-3">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                           data-bs-toggle="dropdown">
                            <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center me-2">
                                @php
                                    $lawyer = App\Models\Lawyer::where('user_id', auth()->id())->first();
                                    $avatarPath = $lawyer && $lawyer->avatar ? $lawyer->avatar : null;
                                @endphp

                                @if($avatarPath && Storage::disk('public')->exists($avatarPath))
                                    <img src="{{ Storage::url($avatarPath) }}" alt="user photo">
                                @else
                                    <i class="bi bi-person text-white" style="font-size: 24px;"></i>
                                @endif
                            </div>
                            <span class="fw-semibold" style="color: var(--text-primary);">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('lawyer.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>داشبورد
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-left me-2"></i>خروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        ورود
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        ثبت‌نام وکیل
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content">
    <div class="container py-4">
        {{ $slot }}
    </div>
</main>

<!-- Theme Switcher -->
<div class="theme-switcher">
    <button id="themeToggle" class="theme-toggle-btn">
        <i class="bi bi-moon-stars"></i>
    </button>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function() {
        // Theme Switching
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const themeIcon = themeToggle.querySelector('i');
            const currentTheme = localStorage.getItem('theme');

            // اعمال تم ذخیره شده
            if (currentTheme === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
                if (themeIcon) themeIcon.className = 'bi bi-sun';
            }

            // رویداد کلیک برای تغییر تم
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

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Close dropdowns when clicking outside (optional improvement)
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !dropdown.previousElementSibling?.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    })();
</script>

{{ $scripts ?? '' }}
</body>
</html>
