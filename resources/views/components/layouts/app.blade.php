<!-- resources/views/components/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه وکلای ایران - انتخاب مهارت</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            background-color: #f8f9fa;
            color: #0a0a0a;
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            background: var(--gradient-bg);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-content {
            padding-top: 80px;
        }

        /* Custom Navbar */
        .custom-navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }

        /* Theme Switcher */
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
        }

        .theme-toggle-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .text-nav {
            color: rgba(0, 0, 0, 0.7);
        }

        .text-nav:hover {
            color: black;
        }

        body[data-theme="dark"] .text-nav {
            color: rgba(255, 255, 255, 0.7);
        }

        body[data-theme="dark"] .text-nav:hover {
            color: rgba(255, 255, 255, 1);
        }

        /* Dark Mode Styles */
        body[data-theme="dark"] {
            background-color: #1a202c;
            color: #e2e8f0;

        }

        body[data-theme="dark"] .custom-navbar {
            background-color: #2d3748;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        body[data-theme="dark"] .card {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        body[data-theme="dark"] .text-muted {
            color: #a0aec0 !important;
        }

        body[data-theme="dark"] .bg-light {
            background-color: #4a5568 !important;
        }

        body[data-theme="dark"] .border {
            border-color: #4a5568 !important;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg custom-navbar py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
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
                            <div
                                class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center me-2"
                                style="width: 35px; height: 35px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
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
</script>
</body>
</html>
