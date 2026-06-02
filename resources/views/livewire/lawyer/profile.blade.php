<!-- resources/views/livewire/lawyer/profile.blade.php -->
<div>
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="profile-cover" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
        <div class="card-body pt-0">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <!-- Avatar -->
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
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="profile-info flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h2 class="mb-1 fw-bold">{{ $name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="bi bi-award me-1"></i>
                                شماره پروانه: {{ $attorneys_license }}
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $city->name ?? 'ثبت نشده' }}
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
                                <label class="form-label">ایمیل</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       wire:model="email"
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">شماره موبایل</label>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       wire:model="phone"
                                       pattern="09[0-9]{9}"
                                       required>
                                @error('phone')
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
                                        wire:model="province_id"
                                        wire:change="updatedProvinceId($event.target.value)"
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

        <!-- Stats & Additional Info -->
        <div class="col-lg-4">
            <!-- Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart text-primary me-2"></i>
                        آمار پروفایل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-eye text-info me-2"></i>
                                <span>بازدید پروفایل</span>
                            </div>
                            <span class="fw-bold">۱,۲۴۵</span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-mouse text-success me-2"></i>
                                <span>کلیک‌ها</span>
                            </div>
                            <span class="fw-bold">۸۹</span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-telephone text-warning me-2"></i>
                                <span>تماس‌ها</span>
                            </div>
                            <span class="fw-bold">۲۳</span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                            <div>
                                <i class="bi bi-star text-primary me-2"></i>
                                <span>امتیاز</span>
                            </div>
                            <div>
                                <span class="fw-bold">۴.۸</span>
                                <i class="bi bi-star-fill text-warning ms-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Preview -->
            <div class="card border-0 shadow-sm">
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
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check text-primary me-2"></i>
                        امنیت حساب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-warning">
                            <i class="bi bi-key me-1"></i>
                            تغییر رمز عبور
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="bi bi-shield-exclamation me-1"></i>
                            حذف حساب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Upload Progress -->
    @if($avatar)
        <div class="alert alert-info mt-3">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <i class="bi bi-upload me-2"></i>
                    در حال آپلود تصویر...
                </div>
                <div class="spinner-border spinner-border-sm ms-2"></div>
            </div>
        </div>
    @endif

    <style>
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
            background: var(--primary-color);
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
            background: var(--secondary-color);
            transform: scale(1.1);
        }

        .avatar-section {
            z-index: 1;
        }
    </style>
</div>
