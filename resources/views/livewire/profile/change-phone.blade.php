<!-- resources/views/livewire/profile/change-phone.blade.php -->
<div>
    @if($message)
        <div class="alert alert-{{ $messageType === 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تغییر شماره موبایل</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">شماره فعلی</label>
                <input type="text" class="form-control" value="{{ $currentPhone }}" readonly>
            </div>

            @if($step == 1)
                <div class="mb-3">
                    <label class="form-label">شماره جدید</label>
                    <input type="text"
                           class="form-control @error('newPhone') is-invalid @enderror"
                           wire:model="newPhone"
                           placeholder="09XXXXXXXXX"
                           maxlength="11">
                    @error('newPhone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary" wire:click="sendVerification">
                    <i class="bi bi-send me-1"></i>
                    ارسال کد تأیید
                </button>
            @endif

            @if($step == 2)
                <div class="mb-3">
                    <label class="form-label">کد تأیید</label>
                    <div class="input-group">
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               wire:model="code"
                               placeholder="کد ۶ رقمی را وارد کنید"
                               maxlength="6">
                        <button class="btn btn-outline-secondary" wire:click="resendCode" type="button">
                            ارسال مجدد
                        </button>
                    </div>
                    @error('code')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">کد به شماره جدید ارسال شد</small>
                </div>

                <button class="btn btn-success" wire:click="verifyCode">
                    <i class="bi bi-check-circle me-1"></i>
                    تأیید و تغییر شماره
                </button>
            @endif
        </div>
    </div>
</div>
