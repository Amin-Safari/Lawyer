<!-- resources/views/livewire/lawyer/clicks.blade.php -->
<div>
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-bar-chart display-6 opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">کل آمار</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-eye display-6 opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">بازدیدها</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['views']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-mouse display-6 opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">کلیک‌ها</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['clicks']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-telephone display-6 opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">تماس‌ها</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['calls']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-cash-coin display-6 opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">هزینه کل</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['cost']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Time Range -->
                <div class="col-md-3">
                    <label class="form-label">بازه زمانی</label>
                    <select class="form-select" wire:model.live="timeRange">
                        <option value="day">امروز</option>
                        <option value="week">هفته جاری</option>
                        <option value="month">ماه جاری</option>
                        <option value="year">سال جاری</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="col-md-3">
                    <label class="form-label">نوع فعالیت</label>
                    <select class="form-select" wire:model.live="typeFilter">
                        <option value="all">همه</option>
                        <option value="view">بازدید</option>
                        <option value="click">کلیک</option>
                        <option value="call">تماس</option>
                    </select>
                </div>

                <!-- Skill Filter -->
                <div class="col-md-3">
                    <label class="form-label">فیلتر مهارت</label>
                    <select class="form-select" wire:model.live="skillFilter">
                        <option value="">همه مهارت‌ها</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="col-md-3">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0"
                               placeholder="جستجو..."
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clicks Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>مهارت</th>
                        <th>نوع</th>
                        <th>اطلاعات</th>
                        <th>زمان</th>
                        <th>مبلغ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($clicks as $click)
                        <tr>
                            <td class="text-center">
                                <div class="avatar-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="background: {{ getClickColor($click->type) }}; width: 35px; height: 35px;">
                                    <i class="bi {{ getClickIcon($click->type) }} text-white"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $click->skill->name }}</div>
                                <small class="text-muted">{{ $click->skill->category ?? 'دسته‌بندی نشده' }}</small>
                            </td>
                            <td>
                                    <span class="badge {{ getClickBadgeClass($click->type) }}">
                                        {{ getClickTypeText($click->type) }}
                                    </span>
                            </td>
                            <td>
                                <div>
                                    <small class="d-block">
                                        <i class="bi bi-globe me-1"></i>
                                        {{ $click->ip_address }}
                                    </small>
                                    <small class="text-muted d-block mt-1">
                                        <i class="bi bi-browser-chrome me-1"></i>
                                        {{ Str::limit($click->user_agent, 50) }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $click->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $click->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($click->cost)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-currency-exchange text-success me-2"></i>
                                        <div>
                                            <div class="fw-bold text-success">
                                                {{ number_format($click->cost) }} ریال
                                            </div>
                                            <small class="text-muted d-block">کسر شده</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-mouse text-muted display-4 d-block mb-3"></i>
                                    <h5 class="text-muted">هیچ فعالیتی یافت نشد</h5>
                                    <p class="text-muted mb-0">هنوز هیچ کلیک، بازدید یا تماسی برای نمایش وجود ندارد</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($clicks->hasPages())
            <div class="card-footer bg-transparent border-0">
                {{ $clicks->links() }}
            </div>
        @endif
    </div>

    <!-- Export Options -->
    <div class="mt-4">
        <div class="d-flex justify-content-end">
            <button class="btn btn-outline-primary me-2">
                <i class="bi bi-download me-2"></i>
                خروجی Excel
            </button>
            <button class="btn btn-outline-success">
                <i class="bi bi-printer me-2"></i>
                چاپ گزارش
            </button>
        </div>
    </div>

    <script>
        function getClickColor(type) {
            switch(type) {
                case 'view': return 'rgba(67, 97, 238, 0.2)';
                case 'click': return 'rgba(76, 201, 240, 0.2)';
                case 'call': return 'rgba(248, 150, 30, 0.2)';
                default: return '#6c757d';
            }
        }

        function getClickIcon(type) {
            switch(type) {
                case 'view': return 'bi-eye';
                case 'click': return 'bi-mouse';
                case 'call': return 'bi-telephone';
                default: return 'bi-info-circle';
            }
        }

        function getClickBadgeClass(type) {
            switch(type) {
                case 'view': return 'badge bg-info';
                case 'click': return 'badge bg-success';
                case 'call': return 'badge bg-warning';
                default: return 'badge bg-secondary';
            }
        }

        function getClickTypeText(type) {
            switch(type) {
                case 'view': return 'بازدید';
                case 'click': return 'کلیک';
                case 'call': return 'تماس';
                default: return 'نامشخص';
            }
        }
    </script>

    <style>
        .empty-state {
            opacity: 0.7;
        }

        .avatar-sm {
            transition: all 0.3s ease;
        }

        .avatar-sm:hover {
            transform: scale(1.1);
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05) !important;
        }
    </style>
</div>
