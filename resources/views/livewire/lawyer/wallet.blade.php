<!-- resources/views/livewire/lawyer/wallet.blade.php -->
<div>
    <!-- Wallet Balance -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-8">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="wallet-icon me-3">
                            <i class="bi bi-wallet2 text-white display-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">موجودی کیف پول</h6>
                            <h2 class="display-5 fw-bold text-primary mb-0">
                                {{ number_format($wallet->balance) }} ریال
                            </h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                    <i class="bi bi-arrow-down-circle"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">موجودی قابل برداشت</small>
                                    <div class="fw-bold">{{ number_format($wallet->balance) }} ریال</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">در انتظار تایید</small>
                                    <div class="fw-bold">{{ number_format($wallet->pending_balance) }} ریال</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 bg-primary text-white">
                <div class="card-body h-100 d-flex flex-column justify-content-center">
                    <h5 class="mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        اطلاعات کیف پول
                    </h5>
                    <div class="mb-2">
                        <small>شماره کیف پول:</small>
                        <div class="fw-bold">{{ $wallet->wallet_number }}</div>
                    </div>
                    <div class="mb-2">
                        <small>تاریخ ایجاد:</small>
                        <div class="fw-bold">{{ $wallet->created_at->format('Y/m/d') }}</div>
                    </div>
                    <div>
                        <small>وضعیت:</small>
                        <div>
                            <span class="badge bg-success">فعال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charge and Withdraw -->
    <div class="row mb-4">
        <!-- Charge Wallet -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle text-success me-2"></i>
                        شارژ کیف پول
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="chargeWallet">
                        <div class="mb-4">
                            <label class="form-label">مبلغ شارژ (ریال)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent span-color">ریال</span>
                                <input type="number"
                                       class="form-control"
                                       wire:model="chargeAmount"
                                       min="10000"
                                       step="1000"
                                       placeholder="حداقل ۱۰,۰۰۰ ریال">
                            </div>
                            @error('chargeAmount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Quick Amounts -->
                        <div class="mb-4">
                            <label class="form-label d-block mb-2">مبالغ پیشنهادی</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button"
                                            wire:click="$set('chargeAmount', 50000)"
                                            class="btn btn-outline-primary w-100">
                                        ۵۰,۰۰۰
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button"
                                            wire:click="$set('chargeAmount', 100000)"
                                            class="btn btn-outline-primary w-100">
                                        ۱۰۰,۰۰۰
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button"
                                            wire:click="$set('chargeAmount', 250000)"
                                            class="btn btn-outline-primary w-100">
                                        ۲۵۰,۰۰۰
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button"
                                            wire:click="$set('chargeAmount', 500000)"
                                            class="btn btn-outline-primary w-100">
                                        ۵۰۰,۰۰۰
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-credit-card me-2"></i>
                                انتقال به درگاه پرداخت
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdraw -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-dash-circle text-danger me-2"></i>
                        درخواست برداشت
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="withdrawWallet">
                        <div class="mb-4">
                            <label class="form-label">مبلغ برداشت (ریال)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent span-color">ریال</span>
                                <input type="number"
                                       class="form-control"
                                       wire:model="withdrawAmount"
                                       min="10000"
                                       max="{{ $wallet->balance }}"
                                       step="1000"
                                       placeholder="حداکثر {{ number_format($wallet->balance) }} ریال">
                            </div>
                            @error('withdrawAmount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">توضیحات (اختیاری)</label>
                            <textarea class="form-control"
                                      rows="3"
                                      wire:model="withdrawDescription"
                                      placeholder="دلیل برداشت را وارد کنید..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            درخواست برداشت شما پس از تایید مدیریت، به حساب شما واریز خواهد شد.
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-danger btn-lg"
                                {{ $wallet->balance < 10000 ? 'disabled' : '' }}>
                                <i class="bi bi-bank me-2"></i>
                                ثبت درخواست برداشت
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <!-- Recent Transactions -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">تراکنش‌های اخیر</h5>

            <!-- ✅ انتخابگر تعداد نمایش در هر صفحه -->
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted small mb-0">نمایش:</label>
                <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                    <option value="5">۵</option>
                    <option value="10">۱۰</option>
                    <option value="15">۱۵</option>
                    <option value="20">۲۰</option>
                    <option value="25">۲۵</option>
                    <option value="50">۵۰</option>
                    <option value="100">۱۰۰</option>
                </select>
                <span class="text-muted small">نتیجه</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>زمان</th>
                        <th>نوع</th>
                        <th>توضیحات</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th>شماره تراکنش</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $transaction->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($transaction->type == 'deposit')
                                    <span class="badge badge-deposit">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    واریز
                                </span>
                                @else
                                    <span class="badge badge-withdrawal">
                                    <i class="bi bi-dash-circle me-1"></i>
                                    برداشت
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $transaction->description }}</div>
                                <small class="text-muted">{{ $transaction->reference_type ?? '' }}</small>
                            </td>
                            <td>
                                <div class="{{ $transaction->type == 'deposit' ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $transaction->type == 'deposit' ? '+' : '-' }}
                                    {{ number_format($transaction->amount) }} ریال
                                </div>
                            </td>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    تکمیل شده
                                </span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i>
                                    در انتظار
                                </span>
                                @elseif($transaction->status == 'failed')
                                    <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    ناموفق
                                </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted font-monospace">{{ $transaction->reference_id ?? $transaction->transaction_id }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-cash-coin text-muted display-4 d-block mb-3"></i>
                                <h6 class="text-muted">هنوز تراکنشی ثبت نشده است</h6>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ✅ صفحه‌بندی -->
            <div class="card-footer bg-transparent border-0 pt-3 pb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">
                        نمایش {{ $recentTransactions->firstItem() ?? 0 }} تا {{ $recentTransactions->lastItem() ?? 0 }} از {{ $recentTransactions->total() }} تراکنش
                    </div>
                    <div>
                        {{ $recentTransactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Methods -->

    <style>
        .span-color{
            color:black;
        }
        .span-color [data-theme="dark"]{
            color: white !important;
        }
        /* استایل‌های صفحه‌بندی */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            color: var(--primary-color, #4361ee);
            background-color: var(--bg-card, #ffffff);
            border-color: var(--border-color, #dee2e6);
            margin: 0 3px;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: var(--primary-color, #4361ee);
            color: white;
            transform: translateY(-2px);
        }

        .pagination .active .page-link {
            background-color: var(--primary-color, #4361ee);
            border-color: var(--primary-color, #4361ee);
            color: white;
        }

        .pagination .disabled .page-link {
            color: var(--text-secondary, #6c757d);
            background-color: var(--bg-card, #ffffff);
        }

        /* در حالت شب */
        [data-theme="dark"] .pagination .page-link {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-theme="dark"] .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Responsive Pagination */
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        /* استایل برای انتخابگر تعداد نمایش */
        select.form-select-sm {
            cursor: pointer;
            padding: 0.25rem 2rem 0.25rem 0.5rem;
            border-radius: 8px;
        }

        /* انیمیشن برای تغییر صفحه */
        .table-responsive {
            transition: opacity 0.2s ease;
        }

        .loading {
            opacity: 0.6;
        }
        .wallet-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-method {
            transition: all 0.3s ease;
            background: white;
        }

        .payment-method:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: #4361ee !important;
        }

        .badge-deposit, .badge-withdrawal {
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</div>
