<!-- resources/views/livewire/auth/login.blade.php -->
<div>
    @if(session()->has('message'))
        <div class="alert alert-success animate__animated animate__fadeInDown">
            <i class="bi bi-check-circle-fill"></i> {{ session('message') }}
        </div>
    @endif

    @if($step == 1)
        <div class="text-center mb-4">
            <h2 class="fw-bold">ورود به حساب کاربری</h2>
            <p class="text-muted">لطفا اطلاعات حساب خود را وارد کنید</p>
        </div>

        <div class="form-card animate__animated animate__fadeIn">
            <form wire:submit.prevent="login">
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill me-2"></i>ایمیل
                    </label>
                    <input type="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           wire:model="email"
                           placeholder="example@email.com"
                           required>
                    @error('email')
                    <div class="invalid-feedback animate__animated animate__headShake">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill me-2"></i>رمز عبور
                    </label>
                    <input type="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           wire:model="password"
                           placeholder="••••••••"
                           required>
                    @error('password')
                    <div class="invalid-feedback animate__animated animate__headShake">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">
                            مرا به خاطر بسپار
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    ورود به حساب
                    <span wire:loading wire:target="login">
                    <span class="spinner-border spinner-border-sm ms-2"></span>
                </span>
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="#" class="text-decoration-none">
                    <i class="bi bi-question-circle me-1"></i>
                    رمز عبور خود را فراموش کرده‌اید؟
                </a>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="mb-2">حساب کاربری ندارید؟</p>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    ثبت‌نام وکیل جدید
                </a>
            </div>
        </div>
    @endif

    @if($step == 2)
        <div class="text-center mb-4">
            <h2 class="fw-bold">تأیید دو مرحله‌ای</h2>
            <p class="text-muted">کد تأیید به شماره {{ $phone }} ارسال شد</p>

            <div class="countdown-timer animate__animated animate__pulse animate__infinite" id="countdownTimer" data-time="{{ $countdown }}">
                {{ floor($countdown / 60) }}:{{ str_pad($countdown % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="form-card animate__animated animate__fadeIn">
            <div class="text-center mb-4">
                <div class="display-1 text-primary mb-3 animate__animated animate__tada">
                    <i class="bi bi-phone-vibrate"></i>
                </div>
                <p>لطفا کد ۵ رقمی ارسال شده به تلفن همراه خود را وارد کنید</p>
            </div>

            <form wire:submit.prevent="verifyCode">
                <div class="mb-4">
                    <input type="text"
                           id="verificationCode"
                           class="form-control text-center fs-2 @error('verificationCode') is-invalid @enderror"
                           wire:model="verificationCode"
                           maxlength="5"
                           placeholder="12345"
                           style="letter-spacing: 10px;"
                           required>
                    @error('verificationCode')
                    <div class="invalid-feedback animate__animated animate__headShake">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        تأیید کد
                        <span wire:loading wire:target="verifyCode">
                        <span class="spinner-border spinner-border-sm ms-2"></span>
                    </span>
                    </button>

                    <button type="button"
                            wire:click="resendCode"
                            class="btn btn-outline-secondary {{ $countdown > 0 ? 'disabled' : '' }}">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        ارسال مجدد کد
                        @if($countdown > 0)
                            ({{ floor($countdown / 60) }}:{{ str_pad($countdown % 60, 2, '0', STR_PAD_LEFT) }})
                        @endif
                    </button>

                    <button type="button"
                            wire:click="$set('step', 1)"
                            class="btn btn-outline-danger">
                        <i class="bi bi-arrow-right me-2"></i>
                        بازگشت
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <div class="alert alert-info animate__animated animate__fadeIn">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    کد تأیید به مدت ۲ دقیقه معتبر است
                </div>
            </div>
        </div>
    @endif
</div>
