<!-- resources/views/livewire/lawyer/skills.blade.php -->
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
    <div class="row">
        <!-- Available Skills -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">مهارت‌های موجود</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($allSkills as $skill)
                            <div class="col-md-6 col-lg-4">
                                <div class="skill-option card h-100 border hover-effect
                                          {{ in_array($skill->id, $selectedSkills) ? 'selected-skill' : '' }}"
                                     style="cursor: pointer; transition: all 0.3s ease;"
                                     wire:click="toggleSkill({{ $skill->id }})">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       wire:model="selectedSkills"
                                                       value="{{ $skill->id }}"
                                                       id="skill_{{ $skill->id }}">
                                            </div>
                                            <div class="skill-icon">
                                                <i class="bi bi-briefcase text-primary fs-4"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-2">{{ $skill->name }}</h6>
                                        <p class="text-muted small mb-3">{{ $skill->description ?? 'توضیحات ندارد' }}</p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                {{ $skill->lawyers_count ?? 0 }} وکیل
                                            </small>
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
                </div>
            </div>
        </div>

        <!-- Selected Skills Configuration -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">تنظیمات مهارت‌ها</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateSkills">
                        @if(count($selectedSkills) > 0)
                            <div class="selected-skills-list mb-4">
                                @foreach($allSkills->whereIn('id', $selectedSkills) as $skill)
                                    <div class="selected-skill-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0">{{ $skill->name }}</h6>
                                            <div class="form-check form-switch">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       role="switch"
                                                       wire:model="isActive.{{ $skill->id }}"
                                                       id="active_{{ $skill->id }}">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small mb-1">قیمت هر کلیک (ریال)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent">
                                                    <i class="bi bi-currency-exchange"></i>
                                                </span>
                                                <input type="number"
                                                       class="form-control form-control-sm"
                                                       wire:model="prices.{{ $skill->id }}"
                                                       min="0"
                                                       placeholder="{{ $skill->click_price }}">
                                            </div>
                                            <small class="text-muted">قیمت پیشفرض: {{ number_format($skill->click_price) }} ریال</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>
                                    ذخیره تغییرات
                                </button>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-tags text-muted display-4 d-block mb-3"></i>
                                <h6 class="text-muted">هنوز مهارتی انتخاب نشده</h6>
                                <p class="text-muted small">برای شروع، مهارت‌های مورد نظر خود را از لیست سمت راست انتخاب کنید</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-12">
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
                                <th>بازدید کل</th>
                                <th>کلیک</th>
                                <th>تماس</th>
                                <th>درآمد</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lawyer->skills as $skill)
                                @php
                                    $stats = $lawyer->clicks()
                                        ->where('skill_id', $skill->id)
                                        ->selectRaw('type, count(*) as count, sum(cost) as total_cost')
                                        ->groupBy('type')
                                        ->get()
                                        ->keyBy('type');
                                @endphp

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="skill-avatar me-3">
                                                <i class="bi bi-briefcase text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $skill->name }}</div>
                                                <small class="text-muted">آخرین فعالیت: {{ $skill->pivot->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($skill->pivot->is_active)
                                            <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    فعال
                                                </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    غیرفعال
                                                </span>
                                        @endif
                                    </td>
                                    <td>
                                            <span class="fw-bold text-primary">
                                                {{ number_format($skill->pivot->click_price) }}
                                            </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-eye text-info me-2"></i>
                                            <span>{{ $stats['view']->count ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-mouse text-success me-2"></i>
                                            <span>{{ $stats['click']->count ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone text-warning me-2"></i>
                                            <span>{{ $stats['call']->count ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-cash-coin text-danger me-2"></i>
                                            <span>{{ number_format($stats['call']->total_cost ?? 0) }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle skill selection
        function toggleSkill(skillId) {
            const skillElement = event.target.closest('.skill-option');
            skillElement.classList.toggle('selected-skill');
        }
    </script>

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

        .selected-skill-item {
            background: white;
            transition: all 0.3s ease;
        }

        .selected-skill-item:hover {
            border-color: #4361ee !important;
        }
    </style>
</div>
