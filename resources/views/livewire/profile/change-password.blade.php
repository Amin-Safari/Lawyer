<!-- resources/views/livewire/profile/change-password.blade.php -->
<div>
    @if($message)
        <div class="alert alert-{{ $messageType === 'success' ? 'success' : 'danger' }} alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تغییر رمز عبور</h5>
        </div>
        <div class="card-body">
            @if($step == 1)
                <div class="mb-3">
                    <label class="form-label">رمز عبور فعلی</label>
                    <input type="password"
                           class="form-control @error('currentPassword') is-invalid @enderror"
                           wire:model="currentPassword">
                    @error('currentPassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">رمز عبور جدید</label>
                    <input type="password"
                           class="form-control @error('newPassword') is-invalid @enderror"
                           wire:model="newPassword">
                    @error('newPassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">تکرار رمز عبور جدید</label>
                    <input type="password"
                           class="form-control @error('newPasswordConfirmation') is-invalid @enderror"
                           wire:model="newPasswordConfirmation">
                    @error('newPasswordConfirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary" wire:click="sendVerification">
                    <i class="bi bi-send me-1"></i>
                    دریافت کد تأیید
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
                    <small class="text-muted">کد به ایمیل شما ارسال شد</small>
                </div>

                <button class="btn btn-success" wire:click="verifyCode">
                    <i class="bi bi-check-circle me-1"></i>
                    تأیید و تغییر رمز عبور
                </button>
            @endif
        </div>
    </div>
</div>
