<div>
    <!-- Improved Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body py-4" style="background: linear-gradient(135deg, #f8961e, #fb923c); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <i class="bi bi-telephone display-5 opacity-75"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-90">تعداد تماس‌ها</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['calls']) }}</h2>
                            <small class="opacity-75">در بازه انتخابی</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body py-4" style="background: linear-gradient(135deg, #10b981, #34d399); color: white;">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <i class="bi bi-currency-exchange display-5 opacity-75"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-90">هزینه کل (ریال)</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($stats['cost']) }}</h2>
                            <small class="opacity-75">از تماس‌های موفق</small>
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
                <div class="col-md-4">
                    <label class="form-label">بازه زمانی</label>
                    <select class="form-select" wire:model.live="timeRange">
                        <option value="day">امروز</option>
                        <option value="week">هفته جاری</option>
                        <option value="month">ماه جاری</option>
                        <option value="year">سال جاری</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">فیلتر مهارت</label>
                    <select class="form-select" wire:model.live="skillFilter">
                        <option value="">همه مهارت‌ها</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control"
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>مهارت</th>
                        <th>نوع</th>
                        <th>زمان</th>
                        <th> مبلغ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($clicks as $click)
                        <tr>
                            <td class="text-center">
                                <div class="avatar-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="background: {{ $this->getClickColor($click->type) }}; width: 38px; height: 38px;">
                                    <i class="bi {{ $this->getClickIcon($click->type) }} text-white fs-5"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $click->skill->name ?? 'نامشخص' }}</div>
                                <small class="text-muted">{{ $click->skill->category ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $this->getClickBadgeClass($click->type) }} px-3 py-2">
                                    {{ $this->getClickTypeText($click->type) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $click->created_at->format('Y/m/d') }}</div>
                                <small class="text-muted">{{ $click->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($click->cost)
                                    <span class="fw-bold text-success">{{ number_format($click->cost) }} <small>ریال</small></span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <h5 class="text-muted">هیچ فعالیتی یافت نشد</h5>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($clicks->hasPages())
            <div class="card-footer bg-transparent border-0 pt-3">
                {{ $clicks->links() }}
            </div>
        @endif
    </div>

    <!-- Export Buttons -->
    <div class="d-flex justify-content-end gap-2 mt-4">
        <button class="btn btn-outline-primary" wire:click="exportExcel">
            <i class="bi bi-file-earmark-excel me-2"></i>
            خروجی Excel / CSV
        </button>

        <button onclick="window.print()" class="btn btn-outline-success">
            <i class="bi bi-printer me-2"></i>
            چاپ گزارش
        </button>
    </div>

    <style>
        .avatar-sm {
            transition: all 0.3s ease;
        }
        .avatar-sm:hover {
            transform: scale(1.15);
        }
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03) !important;
        }

        /* Print Styles */
        @media print {
            .card-footer, .mt-4, .btn, nav {
                display: none !important;
            }
            .table th, .table td {
                font-size: 0.9rem !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</div>
