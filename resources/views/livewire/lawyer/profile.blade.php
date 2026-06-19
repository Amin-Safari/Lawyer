<div>
    @if (session()->has('message'))
        <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
            <i class="bi bi-{{ session('type') === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="profile-cover profile-head" style="height: 200px;"></div>
        <div class="card-body pt-0">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <div class="avatar-section mb-3 mb-md-0 me-md-4">
                    <div class="avatar-wrapper position-relative d-inline-block">
                        <div class="avatar-upload">
                            @if($avatarPreview)
                                <img src="{{ $avatarPreview }}"
                                     alt="Avatar"
                                     class="rounded-circle border border-4 border-white shadow"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle border border-4 border-white shadow d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <i class="bi bi-person text-white display-1"></i>
                                </div>
                            @endif

                            <label for="avatar" class="avatar-upload-btn" title="تغییر عکس">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file"
                                   id="avatar"
                                   class="d-none"
                                   wire:model="avatar"
                                   accept="image/*">
                            @error('avatar') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="profile-info flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="bi bi-award me-1"></i>
                                شماره پروانه: {{ $attorneys_license }}
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $lawyer->city->name ?? 'ثبت نشده' }}
                                </span>
                                <span class="badge bg-success">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $phone }}
                                </span>
                                <span class="badge bg-info">
                                    <i class="bi bi-envelope me-1"></i>
                                    {{ $email }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-2">
                                <span class="badge bg-success py-2 px-3">
                                    <i class="bi bi-check-circle me-1"></i>
                                    تایید شده
                                </span>
                            </div>
                            <small class="text-muted">عضویت از {{ auth()->user()->created_at->format('Y/m/d') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>
                        اطلاعات شخصی
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- ایمیل و شماره تلفن (غیرقابل ویرایش) -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ایمیل</label>
                                <input type="email"
                                       class="form-control"
                                       value="{{ $email }}"
                                       readonly
                                       >
                                <small class="text-muted">ایمیل قابل تغییر نیست</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">شماره موبایل</label>
                                <input type="tel"
                                       class="form-control"
                                       value="{{ $phone }}"
                                       readonly
                                       >
                                <small class="text-muted">شماره موبایل قابل تغییر نیست</small>
                            </div>
                        </div>

                        <hr>

                        <!-- سایر اطلاعات -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نام و نام خانوادگی</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       wire:model="name"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">شماره پروانه وکالت</label>
                                <input type="text"
                                       class="form-control @error('attorneys_license') is-invalid @enderror"
                                       wire:model="attorneys_license"
                                       required>
                                @error('attorneys_license')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">استان</label>
                                <select class="form-select @error('province_id') is-invalid @enderror"
                                        wire:model.live="province_id"
                                        required>
                                    <option value="">انتخاب استان</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">شهر</label>
                                <select class="form-select @error('city_id') is-invalid @enderror"
                                        wire:model="city_id"
                                        {{ !$province_id ? 'disabled' : '' }}
                                        required>
                                    <option value="">انتخاب شهر</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">آدرس دقیق</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          wire:model="address"
                                          rows="3"
                                          required></textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">توضیحات و سوابق</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          wire:model="description"
                                          rows="5"
                                          placeholder="حداقل ۱۰۰ کاراکتر درباره سوابق و تخصص خود بنویسید..."
                                          required></textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">حداقل ۱۰۰ کاراکتر</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Skills Preview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tags text-primary me-2"></i>
                        مهارت‌های فعال
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($lawyer->skills()->wherePivot('is_active', true)->take(6)->get() as $skill)
                            <span class="badge bg-primary bg-gradient">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                        @if($lawyer->skills()->wherePivot('is_active', true)->count() > 6)
                            <span class="badge bg-secondary">
                                +{{ $lawyer->skills()->wherePivot('is_active', true)->count() - 6 }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('lawyer.skills') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-pencil-square me-1"></i>
                        ویرایش مهارت‌ها
                    </a>
                </div>
            </div>

            <!-- Account Security -->
            <!-- در بخش امنیت حساب -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check text-primary me-2"></i>
                        امنیت حساب
                    </h5>
                </div>
                <div class="card-body">
                    <!-- دکمه‌ها با لینک به صفحات -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.change-email') }}" class="btn btn-outline-warning">
                            <i class="bi bi-envelope me-1"></i>
                            تغییر ایمیل
                        </a>
                        <a href="{{ route('profile.change-phone') }}" class="btn btn-outline-info">
                            <i class="bi bi-phone me-1"></i>
                            تغییر شماره موبایل
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-danger">
                            <i class="bi bi-key me-1"></i>
                            تغییر رمز عبور
                        </a>
                        <hr>
                        <button class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-1"></i>
                            حذف حساب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-head {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        [data-theme="dark"] .profile-head {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        }

        .profile-cover {
            position: relative;
        }

        .profile-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.3));
        }

        .avatar-upload {
            position: relative;
            margin-top: -75px;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 10px;
            left: 10px;
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid white;
        }

        .avatar-upload-btn:hover {
            background: #764ba2;
            transform: scale(1.1);
        }

        .avatar-section {
            z-index: 1;
        }

        input:disabled {
            cursor: not-allowed;
        }
    </style>
</div>
<script>
    function confirmDelete() {
        if (confirm('آیا از حذف حساب خود اطمینان دارید؟ این عمل غیرقابل بازگشت است.')) {
            @this.deleteAccount();
        }
    }
</script>
