<div>
    <!-- Header -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-1">مدیریت مهارت‌های تخصصی</h4>
                    <p class="text-muted mb-0">مهارت‌های خود را مدیریت و قیمت‌گذاری کنید</p>
                </div>
                <div class="badge bg-primary bg-gradient py-2 px-3">
                    <i class="bi bi-tags me-1"></i>
                    {{ count($selectedSkills) }} مهارت فعال
                </div>
            </div>
        </div>
    </div>

    <!-- Skills Selection -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0">
            <h5 class="card-title mb-0">مهارت‌های موجود</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($allSkills as $skill)
                    <div class="col-md-6 col-lg-3">
                        <div class="skill-option card h-100 border hover-effect
                                  {{ in_array($skill->id, $selectedSkills) ? 'selected-skill' : '' }}"
                             style="cursor: pointer; transition: all 0.3s ease;"
                             wire:click="toggleSkill({{ $skill->id }})">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:model.live="selectedSkills"
                                               value="{{ $skill->id }}"
                                               id="skill_{{ $skill->id }}"
                                               onclick="event.stopPropagation();">
                                    </div>
                                    <div class="skill-icon">
                                        <i class="bi bi-briefcase text-primary fs-4"></i>
                                    </div>
                                </div>

                                <h6 class="fw-bold mb-2">{{ $skill->name }}</h6>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-primary fw-semibold">
                                        <i class="bi bi-cash-coin me-1"></i>
                                        {{ number_format($skill->click_price) }} ریال
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- دکمه با loading --}}
            <div class="d-flex justify-content-end mt-4">
                <button wire:click="updateSkills"
                        wire:loading.attr="disabled"
                        class="btn btn-primary px-4">
        <span wire:loading.remove wire:target="updateSkills">
            <i class="bi bi-save me-1"></i>
            اعمال تغییرات
        </span>
                    <span wire:loading wire:target="updateSkills">
            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
            در حال ذخیره...
        </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">آمار مهارت‌های شما</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>مهارت</th>
                        <th>وضعیت</th>
                        <th>قیمت</th>
                        <th>تماس</th>
                        <th>هزینه</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- جدول - وضعیت درست --}}
                    @forelse($lawyer->skills as $skill)
                        @php
                            $stats = $lawyer->clicks()
                                ->where('skill_id', $skill->id)
                                ->selectRaw('type, count(*) as count, sum(cost) as total_cost')
                                ->groupBy('type')
                                ->get()
                                ->keyBy('type');

                            $pivotIsActive = (bool) ($skill->pivot->is_active ?? false);
                            $currentPrice = $skill->click_price;
                        @endphp

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="skill-avatar me-3">
                                        <i class="bi bi-briefcase text-primary"></i>
                                    </div>
                                    <div class="fw-semibold">{{ $skill->name }}</div>
                                </div>
                            </td>
                            <td>
                                @if($pivotIsActive)
                                    <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>فعال
                </span>
                                @else
                                    <span class="badge bg-secondary">
                    <i class="bi bi-x-circle me-1"></i>غیرفعال
                </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($currentPrice) }}</span>
                            </td>
                            <td>
                                <i class="bi bi-telephone text-warning me-2"></i>
                                {{ $stats['call']->count ?? 0 }}
                            </td>
                            <td>
                                <i class="bi bi-cash-coin text-danger me-2"></i>
                                {{ number_format($stats['call']->total_cost ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                هنوز هیچ مهارتی انتخاب نکرده‌اید
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .skill-option {
            border: 2px solid transparent;
        }

        .skill-option:hover {
            border-color: #4361ee;
            transform: translateY(-5px);
        }

        .selected-skill {
            border-color: #4361ee !important;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.05));
        }

        .skill-icon {
            width: 40px;
            height: 40px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .skill-avatar {
            width: 35px;
            height: 35px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</div>
