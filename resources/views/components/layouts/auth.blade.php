<!-- resources/views/components/layouts/auth.blade.php -->
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه وکلای ایران</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --text-dark: #343a40;
            --text-light: #f8f9fa;
        }

        [data-theme="dark"] {
            --light-bg: #212529;
            --dark-bg: #f8f9fa;
            --text-dark: #f8f9fa;
            --text-light: #343a40;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
            color: var(--text-dark);
        }

        .auth-container {
            max-width: 1200px;
            margin: 2rem auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .auth-content {
            background-color: var(--light-bg);
            padding: 3rem;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: white;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border: none;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .step.completed .step-number {
            background: var(--success-color);
            color: white;
        }

        .theme-switcher {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .countdown-timer {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
            padding: 10px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .skill-tag {
            display: inline-block;
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            margin: 5px;
            font-size: 0.9rem;
        }

        .skill-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
        }

        .animate-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Dark Mode Styles */
        [data-theme="dark"] .auth-content {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        [data-theme="dark"] .form-card {
            background: #4a5568;
            color: #e2e8f0;
        }

        [data-theme="dark"] .form-control {
            background-color: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        [data-theme="dark"] .form-control:focus {
            background-color: #2d3748;
            color: #e2e8f0;
        }
    </style>
</head>
<body>
<!-- Theme Switcher -->
<div class="theme-switcher">
    <button id="themeToggle" class="btn btn-outline-secondary">
        <i class="bi bi-moon-stars"></i>
    </button>
</div>

<!-- Main Content -->
<div class="auth-container">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-5">
            <div class="auth-sidebar h-100 d-flex flex-column justify-content-center">
                <h1 class="logo animate__animated animate__fadeInDown">
                    <i class="bi bi-shield-check"></i> سامانه وکلای ایران
                </h1>
                <p class="lead animate__animated animate__fadeInUp animate__delay-1s">
                    به جامعه بزرگ وکلا بپیوندید و خدمات حقوقی خود را ارائه دهید
                </p>

                <!-- Stats Chart -->
                <div class="mt-5 animate__animated animate__fadeInUp animate__delay-2s">
                    <canvas id="statsChart" height="200"></canvas>
                </div>

                <div class="mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="display-6">۱۵۰۰+</h3>
                            <p>وکیل فعال</p>
                        </div>
                        <div class="col-4">
                            <h3 class="display-6">۹۸٪</h3>
                            <p>رضایت کاربران</p>
                        </div>
                        <div class="col-4">
                            <h3 class="display-6">۲۴/۷</h3>
                            <p>پشتیبانی</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-7">
            <div class="auth-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Theme Switching
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle.querySelector('i');

    // Check for saved theme or prefer-color-scheme
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
        document.body.setAttribute('data-theme', 'dark');
        themeIcon.className = 'bi bi-sun';
    } else {
        themeIcon.className = 'bi bi-moon-stars';
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

    // Chart.js Implementation
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
                datasets: [{
                    label: 'ثبت‌نام وکلا',
                    data: [120, 150, 180, 210, 240, 280],
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white',
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    });

    // Countdown Timer Function
    function startCountdown() {
        const timerElement = document.getElementById('countdownTimer');
        if (!timerElement) return;

        let countdown = parseInt(timerElement.dataset.time) || 120;

        const timer = setInterval(() => {
            const minutes = Math.floor(countdown / 60);
            const seconds = countdown % 60;

            timerElement.innerHTML = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            timerElement.style.color = countdown < 30 ? '#dc3545' : '#4361ee';

            if (countdown <= 0) {
                clearInterval(timer);
                timerElement.innerHTML = 'زمان به پایان رسید';
                timerElement.style.color = '#6c757d';

                const resendBtn = document.querySelector('[wire\\:click="resendCode"]');
                if (resendBtn) {
                    resendBtn.classList.remove('disabled');
                }
            }

            countdown--;
        }, 1000);
    }

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('start-countdown', () => {
            setTimeout(startCountdown, 500); // Small delay for DOM update
        });
    });
</script>
</body>
</html>
