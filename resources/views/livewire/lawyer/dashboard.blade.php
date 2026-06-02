<!-- resources/views/livewire/lawyer/dashboard.blade.php -->
<div>
    <!-- Time Range Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">آمار دوره‌ای</h5>
                <div class="btn-group" role="group">
                    <button type="button"
                            wire:click="$set('timeRange', 'day')"
                            class="btn btn-outline-primary {{ $timeRange == 'day' ? 'active' : '' }}">
                        امروز
                    </button>
                    <button type="button"
                            wire:click="$set('timeRange', 'week')"
                            class="btn btn-outline-primary {{ $timeRange == 'week' ? 'active' : '' }}">
                        هفته جاری
                    </button>
                    <button type="button"
                            wire:click="$set('timeRange', 'month')"
                            class="btn btn-outline-primary {{ $timeRange == 'month' ? 'active' : '' }}">
                        ماه جاری
                    </button>
                    <button type="button"
                            wire:click="$set('timeRange', 'year')"
                            class="btn btn-outline-primary {{ $timeRange == 'year' ? 'active' : '' }}">
                        سال جاری
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-icon views">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h6 class="text-muted mb-2">بازدید کل</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_views']) }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>
                                12% نسبت به ماه قبل
                            </small>
                        </div>
                        <div class="text-primary display-4 opacity-25">
                            <i class="bi bi-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-icon clicks">
                                <i class="bi bi-mouse"></i>
                            </div>
                            <h6 class="text-muted mb-2">کلیک‌ها</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_clicks']) }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>
                                8% نسبت به ماه قبل
                            </small>
                        </div>
                        <div class="text-success display-4 opacity-25">
                            <i class="bi bi-mouse"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-icon calls">
                                <i class="bi bi-telephone-outbound"></i>
                            </div>
                            <h6 class="text-muted mb-2">تماس‌ها</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_calls']) }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>
                                15% نسبت به ماه قبل
                            </small>
                        </div>
                        <div class="text-warning display-4 opacity-25">
                            <i class="bi bi-telephone-outbound"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-icon wallet">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <h6 class="text-muted mb-2">کیف پول</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($wallet->balance) }} ریال</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>
                                {{ number_format($stats['total_deposits']) }} ریال واریزی
                            </small>
                        </div>
                        <div class="text-purple display-4 opacity-25">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activities -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">نمودار فعالیت‌ها</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">آمار سریع</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div
                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-currency-exchange text-success me-2"></i>
                                <span>درآمد این دوره</span>
                            </div>
                            <span class="fw-bold text-success">
                                {{ number_format($stats['total_cost']) }} ریال
                            </span>
                        </div>

                        <div
                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-cash-coin text-primary me-2"></i>
                                <span>موجودی قابل برداشت</span>
                            </div>
                            <span class="fw-bold text-primary">
                                {{ number_format($wallet->balance) }} ریال
                            </span>
                        </div>

                        <div
                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-hourglass-split text-warning me-2"></i>
                                <span>در انتظار تایید</span>
                            </div>
                            <span class="fw-bold text-warning">
                                {{ number_format($stats['pending_balance']) }} ریال
                            </span>
                        </div>

                        <div
                            class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-tags text-info me-2"></i>
                                <span>مهارت‌های فعال</span>
                            </div>
                            <span class="fw-bold text-info">
                                {{ $lawyer->skills()->count() }}
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Clicks -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">کلیک‌های اخیر</h5>
                    <a href="{{ route('lawyer.clicks') }}" class="btn btn-sm btn-outline-primary">
                        مشاهده همه
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>مهارت</th>
                                <th>نوع</th>
                                <th>زمان</th>
                                <th>مبلغ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentClicks as $click)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="bi bi-tag text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $click->skill->name }}</div>
                                                <small class="text-muted">{{ $click->ip_address }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($click->type == 'view')
                                            <span class="badge badge-view">
                                                    <i class="bi bi-eye me-1"></i>بازدید
                                                </span>
                                        @elseif($click->type == 'click')
                                            <span class="badge badge-click">
                                                    <i class="bi bi-mouse me-1"></i>کلیک
                                                </span>
                                        @else
                                            <span class="badge badge-call">
                                                    <i class="bi bi-telephone me-1"></i>تماس
                                                </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $click->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($click->cost)
                                            <span class="fw-bold text-success">
                                                    {{ number_format($click->cost) }}
                                                </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                                        <p class="text-muted">هنوز کلیکی ثبت نشده است</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">تراکنش‌های اخیر</h5>
                    <a href="{{ route('lawyer.transactions') }}" class="btn btn-sm btn-outline-primary">
                        مشاهده همه
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>توضیحات</th>
                                <th>نوع</th>
                                <th>مبلغ</th>
                                <th>وضعیت</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $transaction->description }}</div>
                                        <small
                                            class="text-muted">{{ $transaction->created_at->format('Y/m/d H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->type == 'deposit')
                                            <span class="badge badge-deposit">
                                                    <i class="bi bi-plus-circle me-1"></i>واریز
                                                </span>
                                        @else
                                            <span class="badge badge-withdrawal">
                                                    <i class="bi bi-dash-circle me-1"></i>برداشت
                                                </span>
                                        @endif
                                    </td>
                                    <td>
                                            <span
                                                class="fw-bold {{ $transaction->type == 'deposit' ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($transaction->amount) }}
                                            </span>
                                    </td>
                                    <td>
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success">تکمیل شده</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning">در انتظار</span>
                                        @else
                                            <span class="badge bg-danger">لغو شده</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-cash-coin text-muted display-4 d-block mb-3"></i>
                                        <p class="text-muted">هنوز تراکنشی ثبت نشده است</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">عملیات سریع</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('lawyer.skills') }}"
                               class="card quick-action-card text-center text-decoration-none">
                                <div class="card-body py-4">
                                    <div class="display-6 text-primary mb-3">
                                        <i class="bi bi-tags"></i>
                                    </div>
                                    <h6>مدیریت مهارت‌ها</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-6">
                            <a href="{{ route('lawyer.wallet') }}"
                               class="card quick-action-card text-center text-decoration-none">
                                <div class="card-body py-4">
                                    <div class="display-6 text-success mb-3">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <h6>شارژ کیف پول</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-6">
                            <a href="{{ route('lawyer.profile') }}"
                               class="card quick-action-card text-center text-decoration-none">
                                <div class="card-body py-4">
                                    <div class="display-6 text-info mb-3">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <h6>ویرایش پروفایل</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-6">
                            <a href="{{ route('home') }}"
                               class="card quick-action-card text-center text-decoration-none">
                                <div class="card-body py-4">
                                    <div class="display-6 text-warning mb-3">
                                        <i class="bi bi-eye"></i>
                                    </div>
                                    <h6>مشاهده پروفایل</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Activity Chart
            const ctx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'],
                    datasets: [
                        {
                            label: 'بازدیدها',
                            data: [120, 150, 180, 140, 200, 170, 190],
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'کلیک‌ها',
                            data: [80, 90, 100, 85, 120, 95, 110],
                            borderColor: '#4cc9f0',
                            backgroundColor: 'rgba(76, 201, 240, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'تماس‌ها',
                            data: [15, 20, 25, 18, 30, 22, 28],
                            borderColor: '#f8961e',
                            backgroundColor: 'rgba(248, 150, 30, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            });

            // Quick action cards hover effect
            const quickActionCards = document.querySelectorAll('.quick-action-card');
            quickActionCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>

    <style>
        .quick-action-card {
            border: 1px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .quick-action-card:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.05));
        }

        .text-purple {
            color: #6f42c1;
        }

        .badge-view, .badge-click, .badge-call, .badge-deposit, .badge-withdrawal {
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</div>
