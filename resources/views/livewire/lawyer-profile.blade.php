<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">

            <!-- Back Button -->
            <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4">
                <i class="bi bi-arrow-right me-2"></i>
                بازگشت به لیست مهارت‌ها
            </a>

            <div class="card border-0 shadow-lg">
                <!-- Header -->
                <div class="position-relative">
                    <div style="height: 240px; background: linear-gradient(135deg, #4361ee 0%, #764ba2 100%);"></div>

                    <div class="text-center position-relative" style="margin-top: -90px;">
                        <div class="mx-auto mb-3" style="width: 170px; height: 170px;">
                            @if($lawyer->avatar)
                                <img src="{{ Storage::url($lawyer->avatar) }}"
                                     alt="{{ $lawyer->user->name }}"
                                     class="rounded-circle border border-5 border-white shadow"
                                     style="width: 170px; height: 170px; object-fit: cover;">
                            @else
                                <div
                                    class="rounded-circle border border-5 border-white shadow bg-primary d-flex align-items-center justify-content-center"
                                    style="width: 170px; height: 170px;">
                                    <i class="bi bi-person-circle text-white" style="font-size: 85px;"></i>
                                </div>
                            @endif
                        </div>

                        <h1 class="fw-bold mb-1">{{ $lawyer->user->name }}</h1>
                        <p class="text-muted fs-5">
                            وکیل پایه یک دادگستری
                            @if($selectedSkill)
                                <span class="badge bg-primary ms-2 fs-6">{{ $selectedSkill->name }}</span>
                            @endif
                        </p>

                        <div class="d-flex justify-content-center gap-4 mt-3 text-muted">
                            <div>
                                <i class="bi bi-geo-alt me-1"></i> {{ $lawyer->city?->name ?? $lawyer->province?->name ?? 'نامشخص' }}
                            </div>
                            <div><i class="bi bi-award me-1"></i> {{ $lawyer->attorneys_license }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">
                    <!-- Description -->
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4"><i class="bi bi-person-badge me-2"></i>درباره من</h4>
                        <div class="bg-light p-4 rounded-3">
                            <p class="lead skill-span" style="line-height: 1.85;">
                                {{ $lawyer->description ?? 'توضیحات توسط وکیل ثبت نشده است.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4"><i class="bi bi-tags me-2"></i>مهارت‌های تخصصی</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($lawyer->skills as $skill)
                                <span class="badge bg-light border skill-span px-4 py-2 fs-6">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 h-100">
                                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>آدرس دفتر:
                                </h5>
                                @if($lawyer->address)
                                    <p> {{ $lawyer->address }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 h-100">
                                <h5 class="fw-bold mb-3"><i class="bi bi-info-square me-2 text-primary"></i>اطلاعات
                                    حقوقی</h5>
                                <p><strong>شماره پروانه:</strong> {{ $lawyer->attorneys_license }}</p>
                                <p><strong>استان:</strong> {{ $lawyer->province?->name }}</p>
                                <p><strong>شهر:</strong> {{ $lawyer->city?->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="card-footer  p-4" style="background:rgba(0,0,0,0.27);">
                    <div class="d-grid gap-3">
                        <button wire:click="callLawyer"
                                class="btn btn-primary btn-lg call-button"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="callLawyer">
                                <i class="bi bi-telephone-forward me-2"></i>
                                تماس با وکیل
                            </span>
                            <span wire:loading wire:target="callLawyer">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                در حال برقراری...
                            </span>
                        </button>

                        <!-- نمایش شماره -->
                        <div id="computer-call-{{ $lawyer->id }}"
                             class="computer-call p-4 rounded-3 border border-primary text-center fw-bold fs-5"
                             style="display: none; background: #f0f4ff; color: #4361ee; cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .skill-span {
            color: black;
        }

        [data-theme="dark"] .skill-span {
            color: white;
        }
    </style>
</div>

{{--@push('scripts')--}}
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
            } else {
                console.error(` Div computer-call-${lawyerId} پیدا نشد`);
            }
        });

        // کلیک برای کپی شماره
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('computer-call')) {
                const phone = e.target.textContent.trim();
                if (phone) copyPhone(phone);
            }
        });
    });

    function copyPhone(phone) {
        navigator.clipboard.writeText(phone)
            .then(() => alert(' شماره با موفقیت کپی شد: ' + phone))
            .catch(() => alert(' خطا در کپی شماره'));
    }
</script>
{{--@endpush--}}
