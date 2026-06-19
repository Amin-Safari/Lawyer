<!-- resources/views/livewire/skill-selection.blade.php -->
<div>
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown mb-4"
             role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Sidebar: Skills List -->
        <div class="col-lg-4 col-xl-3 mb-4">
            <div class="card border-0 shadow-lg h-100 animate__animated animate__fadeInLeft">
                <div class="card-header bg-primary text-white py-4">
                    <h3 class="h5 mb-0">
                        <i class="bi bi-tags-fill me-2"></i>
                        انتخاب مهارت تخصصی
                    </h3>
                </div>
                <div class="card-body p-0">
                    <!-- Search Box -->
                    <div class="p-3 border-bottom">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control border-start-0"
                                   placeholder="جستجوی مهارت...">
                        </div>
                    </div>

                    <!-- Skills List -->
                    <div class="skills-list" style="max-height: 500px; overflow-y: auto;">
                        @php
                            $filteredSkills = $skills->filter(function($skill) {
                                return empty($this->search) ||
                                       str_contains(strtolower($skill->name), strtolower($this->search));
                            });
                        @endphp

                        @if($filteredSkills->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-search text-muted display-4 mb-3"></i>
                                <p class="text-muted">مهارتی یافت نشد</p>
                            </div>
                        @else
                            @foreach($filteredSkills as $skill)
                                <a href="javascript:void(0)"
                                   wire:click="selectSkill({{ $skill->id }})"
                                   class="skill-item d-flex align-items-center p-3 border-bottom hover-effect
                                          {{ $selectedSkillId == $skill->id ? 'active-skill' : '' }}"
                                   style="cursor: pointer; transition: all 0.3s ease;">
                                    <div class="skill-icon me-3">
                                        <div
                                            class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="bi bi-briefcase text-white"></i>
                                        </div>
                                    </div>
                                    <div class="skill-info flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $skill->name }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                {{ $skill->activeLawyers->count() }} وکیل
                                            </small>
                                            <span class="badge bg-primary bg-gradient">
                                                <i class="bi bi-eye me-1"></i>
                                                {{ number_format($skill->total_clicks) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($selectedSkillId == $skill->id)
                                        <div class="selected-indicator ms-2">
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            بر اساس بیشترین بازدید مرتب شده‌اند
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Lawyers List -->
        <div class="col-lg-8 col-xl-9">
            @if(!$selectedSkillId)
                <!-- Empty State -->
                <div class="card border-0 shadow-lg h-100 animate__animated animate__fadeIn">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                        <div class="display-1 text-muted mb-4">
                            <i class="bi bi-search-heart"></i>
                        </div>
                        <h3 class="h4 mb-3">یک مهارت را انتخاب کنید</h3>
                        <p class="text-muted text-center mb-4">
                            لطفاً از لیست سمت راست، مهارت تخصصی مورد نظر خود را انتخاب نمایید
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3 d-flex ">
                                <div class="text-center p-3 bg-light rounded w-100 feature-box">
                                    <i class="bi bi-briefcase text-primary display-6 mb-3"></i>
                                    <h6>مهارت‌های متنوع</h6>
                                    <small class="text-muted">در زمینه‌های مختلف حقوقی</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 d-flex">
                                <div class="text-center p-3 bg-light rounded w-100 feature-box">
                                    <i class="bi bi-shield-check text-success display-6 mb-3"></i>
                                    <h6>وکلای تأیید شده</h6>
                                    <small class="text-muted">با مدارک معتبر</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 d-flex">
                                <div class="text-center p-3 bg-light rounded w-100 feature-box">
                                    <i class="bi bi-telephone text-warning display-6 mb-3"></i>
                                    <h6>تماس مستقیم</h6>
                                    <small class="text-muted">با پرداخت درون‌برنامه‌ای</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Selected Skill Header -->
                @php
                    $selectedSkill = $skills->firstWhere('id', $selectedSkillId);
                @endphp

                <div class="card border-0 shadow-lg mb-4 animate__animated animate__fadeIn">
                    <div class="card-header 1bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="skill-header-icon me-3">
                                    <div
                                        class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center p-3"
                                        style="background: linear-gradient(135deg, #4cc9f0, #4361ee);">
                                        <i class="bi bi-briefcase text-white fs-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="h4 mb-1 fw-bold">{{ $selectedSkill->name }}</h2>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-people me-1"></i>
                                        {{ $lawyers->count() }} وکیل متخصص در این زمینه
                                    </p>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge bg-primary bg-gradient py-2 px-3">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ number_format($selectedSkill->total_clicks) }} بازدید
                                        </span>
                                    </div>
                                    <button class="btn btn-outline-primary"
                                            onclick="location.reload()">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        تغییر مهارت
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lawyers Grid -->
                @if($lawyers->isEmpty())
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-person-x display-1 text-muted mb-4"></i>
                            <h3 class="h4 mb-3">وکیلی در این زمینه یافت نشد</h3>
                            <p class="text-muted mb-4">متأسفانه در حال حاضر وکیل فعالی در این مهارت وجود ندارد.</p>
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="bi bi-arrow-left me-1"></i>
                                بازگشت به لیست مهارت‌ها
                            </button>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($lawyers as $lawyer)
                            <div class="col-md-6 col-xl-6">
                                <div
                                    class="card lawyer-card border-0 shadow-lg h-100 animate__animated animate__fadeInUp"
                                    style="animation-delay: {{ $loop->index * 0.1 }}s">
                                    <div class="card-body">
                                        <!-- Lawyer Header -->
                                        <div class="text-center mb-4">
                                            <div class="lawyer-avatar position-relative d-inline-block mb-3">
                                                <div class="avatar-wrapper rounded-circle overflow-hidden"
                                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                                    @if($lawyer->avatar)
                                                        <img
                                                            src="{{ \Illuminate\Support\Facades\Storage::Url($lawyer->avatar) }}"
                                                            alt="{{ $lawyer->user->name }}"
                                                            class="w-100 h-100 object-fit-cover">
                                                    @else
                                                        <div
                                                            class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                            <i class="bi bi-person-circle text-white display-4"></i>
                                                        </div>
                                                    @endif

                                                </div>
                                                <span class="badge bg-success position-absolute"
                                                      style="bottom: 10px; right: 10px; width: 12px; height: 12px; border-radius: 50%;"></span>
                                            </div>
                                            <h5 class="card-title fw-bold mb-1">{{ $lawyer->user->name }}</h5>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                {{ $lawyer->city->name ?? 'ثبت نشده' }}
                                            </p>

                                        </div>

                                        <!-- Lawyer Info -->
                                        <div class="mb-4">
                                            <div class="info-item d-flex align-items-center mb-3">
                                                <div class="info-icon me-3">
                                                    <i class="bi bi-award text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <small class="text-muted d-block">شماره پروانه</small>
                                                    <span class="fw-semibold">{{ $lawyer->attorneys_license }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Description Preview -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-2">
                                                <i class="bi bi-card-text me-1"></i>
                                                درباره وکیل
                                            </h6>
                                            <p class="card-text text-muted small description-preview">
                                                {{ Str::limit($lawyer->description, 100) }}
                                            </p>
                                        </div>

                                        <!-- Skills Tags -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-2">
                                                <i class="bi bi-tags me-1"></i>
                                                سایر مهارت‌ها
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($lawyer->skills->where('id', '!=', $selectedSkillId)->take(3) as $skill)
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $skill->name }}</span>
                                                @endforeach
                                                @if($lawyer->skills->count() > 4)
                                                    <span
                                                        class="badge bg-secondary">+{{ $lawyer->skills->count() - 4 }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2">
                                            <button wire:click="viewLawyer({{ $lawyer->id }})"
                                                    class="btn btn-outline-primary btn-lg">
                                                <i class="bi bi-person-circle me-2"></i>
                                                مشاهده پروفایل کامل
                                            </button>
                                            <button wire:click="callLawyer({{ $lawyer->id }})"
                                                    class="btn btn-primary btn-lg call-button"
                                                    wire:loading.attr="disabled"
                                                    id="call-lawyer">
                                                <span wire:loading.remove wire:target="callLawyer">
                                                    <i class="bi bi-telephone-forward me-2"></i>
                                                    تماس با وکیل
                                                </span>
                                                <span wire:loading wire:target="callLawyer">
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    در حال برقراری...
                                                </span>
                                            </button>
                                            <button type="button" id="computer-call-{{ $lawyer->id }}"
                                                    class="btn btn-outline-primary computer-call mt-2 p-3 rounded border border-primary text-center fw-bold"
                                                    style="display: none; background-color: #f0f4ff; color: #4361ee; font-size: 1.05rem;">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endforeach
        </div>
        @endif
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('lawyer-contact', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            const phone = data.phone;
            const lawyerId = data.lawyerId;
            const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            if (isMobile) {
                window.location.href = `tel:${phone}`;
                return;
            }
            const targetDiv = document.getElementById(`computer-call-${lawyerId}`);
            if (targetDiv) {
                targetDiv.textContent = phone;
                targetDiv.style.display = 'block';
            }
        });
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('computer-call')) {
                const phone = e.target.textContent.trim();
                if (phone) {
                    copyPhone(phone);
                }
            }
        });
    });

    function copyPhone(phone) {
        navigator.clipboard.writeText(phone)
            .then(() => alert(' شماره با موفقیت کپی شد: ' + phone))
            .catch(err => {
                console.error(err);
                alert(' خطا در کپی شماره');
            });
    }
</script>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #4cc9f0, #4361ee);
    }

    .feature-box {
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .skill-item {
        transition: all 0.3s ease;
        background-color: white;
    }

    .skill-item:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateX(-5px);
        border-right: 3px solid #4361ee !important;
    }

    .active-skill {
        background-color: rgba(67, 97, 238, 0.1) !important;
        border-right: 3px solid #4361ee !important;
    }

    .lawyer-card {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .lawyer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        border-color: #4361ee;
    }

    .call-button {
        background: var(--primary-gradient);
        border: none;
        transition: all 0.3s ease;
    }

    .call-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 10 5px 15px rgba(67, 97, 238, 0.4);
    }

    .call-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .info-icon {
        width: 35px;
        height: 35px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .description-preview {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .skills-list::-webkit-scrollbar {
        width: 1px;
    }

    .skills-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .skills-list::-webkit-scrollbar-thumb {
        background: #4361ee;
        border-radius: 10px;
    }

    .skills-list::-webkit-scrollbar-thumb:hover {
        background: #3a56d4;
    }

    /* Dark Mode Support */
    body[data-theme="dark"] .skill-item {
        background-color: #2d3748;
        color: #e2e8f0;
    }

    body[data-theme="dark"] .skill-item:hover {
        background-color: rgba(67, 97, 238, 0.2);
    }

    body[data-theme="dark"] .active-skill {
        background-color: rgba(67, 97, 238, 0.3) !important;
    }

    body[data-theme="dark"] .skills-list::-webkit-scrollbar-track {
        background: #4a5568;
    }
</style>

<!-- JavaScript for animations -->
<script>
    document.addEventListener('livewire:init', () => {
        // Smooth scroll to top when skill is selected
        Livewire.on('skillSelected', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Confirmation for call
    function confirmCall(lawyerId) {
        if (confirm('آیا مایل به برقراری تماس با این وکیل هستید؟')) {
            Livewire.dispatch('callLawyer', {lawyerId: lawyerId});
        }
    }

    // Add hover animation to cards
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.lawyer-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.classList.add('shadow-lg');
            });

            card.addEventListener('mouseleave', function () {
                this.classList.remove('shadow-lg');
            });
        });
    });
</script>
</div>
