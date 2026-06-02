<!-- resources/views/livewire/auth/register.blade.php -->
<div>
    <!-- Step Indicator -->
    <div class="step-indicator">
        @for($i = 1; $i <= 3; $i++)
            <div class="step {{ $step == $i ? 'active' : '' }} {{ $step > $i ? 'completed' : '' }}">
                <div class="step-number">
                    @if($step > $i)
                        <i class="bi bi-check"></i>
                    @else
                        {{ $i }}
                    @endif
                </div>
                <span>
                    @if($i == 1) اطلاعات پایه @endif
                    @if($i == 2) اطلاعات تخصصی @endif
                    @if($i == 3) تأیید شماره @endif
                </span>
            </div>
        @endfor
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Step 1: Basic Information -->
    @if($step == 1)
        <div class="form-card animate__animated animate__fadeIn">
            <div class="text-center mb-4">
                <h2 class="fw-bold">ثبت‌نام وکیل جدید</h2>
                <p class="text-muted">لطفا اطلاعات اولیه خود را وارد کنید</p>
            </div>

            <form wire:submit.prevent="nextStep">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person-fill me-2"></i>نام و نام خانوادگی
                        </label>
                        <input type="text"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               wire:model="name"
                               placeholder="علی محمدی"
                               required>
                        @error('name')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>رمز عبور
                        </label>
                        <input type="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               wire:model="password"
                               placeholder="حداقل ۸ کاراکتر"
                               required>
                        @error('password')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>تکرار رمز عبور
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               class="form-control"
                               wire:model="password_confirmation"
                               placeholder="تکرار رمز عبور"
                               required>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        ادامه
                        <i class="bi bi-arrow-left me-2"></i>
                        <span wire:loading wire:target="nextStep">
                        <span class="spinner-border spinner-border-sm ms-2"></span>
                    </span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="mb-2">قبلاً حساب دارید؟</p>
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    ورود به حساب
                </a>
            </div>
        </div>
    @endif

    <!-- Step 2: Professional Information -->
    @if($step == 2)
        <div class="form-card animate__animated animate__fadeIn">
            <div class="text-center mb-4">
                <h2 class="fw-bold">اطلاعات تخصصی</h2>
                <p class="text-muted">لطفا اطلاعات حرفه‌ای خود را تکمیل کنید</p>
            </div>

            <form wire:submit.prevent="nextStep">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-phone-fill me-2"></i>شماره موبایل
                        </label>
                        <input type="tel"
                               id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               wire:model="phone"
                               placeholder="09123456789"
                               pattern="09[0-9]{9}"
                               required>
                        @error('phone')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="attorneys_license" class="form-label">
                            <i class="bi bi-award-fill me-2"></i>شماره پروانه وکالت
                        </label>
                        <input type="text"
                               id="attorneys_license"
                               class="form-control @error('attorneys_license') is-invalid @enderror"
                               wire:model="attorneys_license"
                               placeholder="123456"
                               required>
                        @error('attorneys_license')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="province_id" class="form-label">
                            <i class="bi bi-geo-alt-fill me-2"></i>استان
                        </label>
                        <select id="province_id"
                                class="form-select @error('province_id') is-invalid @enderror"
                                wire:model.live="province_id"
                                required>
                            <option value="">انتخاب استان</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province_id')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="city_id" class="form-label">
                            <i class="bi bi-geo-fill me-2"></i>شهر
                        </label>
                        <select id="city_id"
                                class="form-select @error('city_id') is-invalid @enderror"
                                wire:model="city_id"
                                {{ !$province_id ? 'disabled' : '' }}
                                required>
                            <option value="">انتخاب شهر</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">
                            <i class="bi bi-file-text-fill me-2"></i>توضیحات و سوابق
                        </label>
                        <textarea id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  wire:model="description"
                                  rows="4"
                                  placeholder="حداقل ۱۰۰ کاراکتر درباره سوابق و تخصص خود بنویسید..."
                                  required></textarea>
                        @error('description')
                        <div class="invalid-feedback animate__animated animate__headShake">
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-text">حداقل ۱۰۰ کاراکتر</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button"
                            wire:click="previousStep"
                            class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-right me-2"></i>
                        مرحله قبل
                    </button>

                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        ارسال کد تأیید
                        <i class="bi bi-arrow-left me-2"></i>
                        <span wire:loading wire:target="nextStep">
                        <span class="spinner-border spinner-border-sm ms-2"></span>
                    </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Step 3: Verification and Skills -->
    @if($step == 3)
        <div class="animate__animated animate__fadeIn">
            @if($verificationSent)
                <div class="form-card mb-4">
                    <div class="text-center mb-4">
                        <div class="display-1 text-primary mb-3 animate__animated animate__tada">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h2 class="fw-bold">تأیید شماره موبایل</h2>
                        <p class="text-muted">کد تأیید به شماره {{ $phone }} ارسال شد</p>

                        <div class="countdown-timer animate__animated animate__pulse animate__infinite"
                             id="countdownTimer"
                             data-time="{{ $countdown }}">
                            {{ floor($countdown / 60) }}:{{ str_pad($countdown % 60, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="verificationCode" class="form-label">کد تأیید</label>
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

                    <div class="d-grid gap-2 mb-3">
                        <button type="button"
                                wire:click="resendCode"
                                class="btn btn-outline-secondary {{ $countdown > 0 ? 'disabled' : '' }}">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            ارسال مجدد کد
                        </button>
                    </div>
                </div>
            @endif

            <div class="form-card">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">انتخاب تخصص‌ها</h2>
                    <p class="text-muted">حداقل یک تخصص را انتخاب کنید</p>
                </div>

                @error('skills')
                <div class="alert alert-danger animate__animated animate__headShake">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $message }}
                </div>
                @enderror

                <div class="row">
                    @foreach($allSkills as $skill)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="form-check card skill-card">
                                <input type="checkbox"
                                       id="skill_{{ $skill->id }}"
                                       class="form-check-input"
                                       value="{{ $skill->id }}"
                                       wire:model="skills">
                                <label class="form-check-label card-body" for="skill_{{ $skill->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="skill-icon me-3">
                                            <i class="bi bi-briefcase-fill fs-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $skill->name }}</h6>
                                            <small class="text-muted">{{ $skill->description ?? 'تخصص حقوقی' }}</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button"
                            wire:click="previousStep"
                            class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-right me-2"></i>
                        مرحله قبل
                    </button>

                    <button type="button"
                            wire:click="verifyAndRegister"
                            class="btn btn-success btn-lg px-5">
                        تکمیل ثبت‌نام
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span wire:loading wire:target="verifyAndRegister">
                        <span class="spinner-border spinner-border-sm ms-2"></span>
                    </span>
                    </button>
                </div>
            </div>

            <div class="text-center mt-4">
                <div class="alert alert-info animate__animated animate__fadeIn">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    پس از تأیید کد و انتخاب تخصص‌ها، حساب شما فعال خواهد شد
                </div>
            </div>
        </div>
    @endif

    <!-- Add some custom styles for skill cards -->
    <style>
        .skill-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .skill-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .skill-card .form-check-input:checked ~ .card-body {
            background-color: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
        }

        .skill-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(76, 201, 240, 0.1));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</div>
