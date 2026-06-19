<?php

namespace App\Livewire;

use App\Models\Skill;
use App\Models\Lawyer;
use App\Models\SkillClick;
use App\Services\WalletService;
use App\Services\SmsService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SkillSelection extends Component
{
    public $skills;
    public $selectedSkillId;
    public $lawyers = [];
    public $search = '';
    public $errorMessage = null;

    public function mount()
    {
        $this->loadSkills();
    }

    public function loadSkills()
    {
        // فقط مهارت‌های فعال را بارگذاری کن
        $this->skills = Skill::where('is_active', true)
            ->orderBy('total_clicks', 'desc')
            ->limit(20)
            ->get();

        // محاسبه تعداد وکلای فعال برای هر مهارت
        foreach ($this->skills as $skill) {
            $skill->active_lawyers_count = $skill->activeLawyers()->count();
        }
    }

    public function selectSkill($skillId)
    {
        // اول بررسی کن که مهارت وجود داشته باشد و فعال باشد
        $skill = Skill::where('id', $skillId)
            ->where('is_active', true)
            ->first();

        if (!$skill) {
            $this->errorMessage = 'این مهارت غیرفعال شده است.';
            session()->flash('error', $this->errorMessage);
            return;
        }

        $this->selectedSkillId = $skillId;

        // ثبت کلیک نمایش مهارت
        $this->recordView($skill);

        // افزایش تعداد بازدید مهارت
        $skill->increment('total_clicks');

        // دریافت وکلای فعال برای این مهارت
        $this->lawyers = $this->getActiveLawyersForSkill($skill);

        if ($this->lawyers->isEmpty()) {
            session()->flash('warning', 'در حال حاضر وکیل فعالی برای این مهارت وجود ندارد.');
        }

        $this->dispatch('skillSelected');
    }

    public function updatedSearch()
    {
        $this->loadSkills();
    }

    private function getActiveLawyersForSkill(Skill $skill)
    {
        // بررسی مجدد اینکه مهارت فعال است
        if (!$skill->is_active) {
            return collect([]);
        }

        // دریافت وکلایی که این مهارت را دارند و برای این مهارت فعال هستند
        // و کیف پول فعال دارند
        return $skill->lawyers()
            ->wherePivot('is_active', true)
            ->whereHas('user.wallet', function ($query) {
                $query->where('balance', '>', 1000)
                    ->where('status', 'active');
            })
            ->with(['user', 'province', 'city', 'skills'])
            ->get();
    }

    public function viewLawyer($lawyerId)
    {
        // اول بررسی کن مهارت هنوز فعال است
        $skill = Skill::where('id', $this->selectedSkillId)
            ->where('is_active', true)
            ->first();

        if (!$skill) {
            session()->flash('error', 'این مهارت دیگر فعال نیست.');
            return redirect()->route('home');
        }

        // بررسی کن وکیل برای این مهارت هنوز فعال است
        $lawyerSkill = DB::table('lawyer_skill')
            ->where('lawyer_id', $lawyerId)
            ->where('skill_id', $skill->id)
            ->where('is_active', true)
            ->first();

        if (!$lawyerSkill) {
            session()->flash('error', 'این وکیل برای این مهارت دیگر فعال نیست.');
            return redirect()->route('home');
        }



        // ریدایرکت به صفحه وکیل
        return redirect()->route('lawyer.profile.public', [
            'lawyerId' => $lawyerId,
            'skillId' => $this->selectedSkillId
        ]);
    }

    public function callLawyer($lawyerId)
    {
        // بررسی کن مهارت هنوز فعال است
        $skill = Skill::where('id', $this->selectedSkillId)
            ->where('is_active', true)
            ->first();

        if (!$skill) {
            session()->flash('error', 'این مهارت دیگر فعال نیست.');
            return;
        }

        // بررسی کن وکیل برای این مهارت هنوز فعال است
        $lawyerSkill = DB::table('lawyer_skill')
            ->where('lawyer_id', $lawyerId)
            ->where('skill_id', $skill->id)
            ->where('is_active', true)
            ->first();

        if (!$lawyerSkill) {
            session()->flash('error', 'این وکیل برای این مهارت دیگر فعال نیست.');
            return;
        }

        $lawyer = Lawyer::with('user.wallet')->find($lawyerId);

        if (!$lawyer || !$lawyer->user || !$lawyer->user->wallet) {
            session()->flash('error', 'وکیل مورد نظر یافت نشد یا کیف پول فعال ندارد.');
            return;
        }

        // بررسی موجودی کیف پول
        if ($lawyer->user->wallet->balance < $skill->click_price) {
            // اگر موجودی کافی نبود، غیرفعال کردن این مهارت برای این وکیل
            DB::table('lawyer_skill')
                ->where('lawyer_id', $lawyerId)
                ->where('skill_id', $skill->id)
                ->update(['is_active' => false]);

            session()->flash('error', 'موجودی کیف پول وکیل کافی نیست. این مهارت برای این وکیل غیرفعال شد.');
            return;
        }
        try {
            $try_count = SkillClick::query()
                ->where('lawyer_id', $lawyerId)
                ->where('ip_address', request()->ip())
                ->where('skill_id', $skill->id)
                ->whereDate('created_at', today())
                ->count();
            if (!$try_count) {
                // ثبت تماس
                $this->recordCall($skill, $lawyer);
                // کسر هزینه از کیف پول وکیل
                $this->deductFromWallet($lawyer, $skill->click_price, $skill);
                // ارسال SMS به وکیل
                $this->sendSmsToLawyer($lawyer, $skill);



                // رفرش لیست وکلا (شاید وکیل غیرفعال شده باشد)

            }
            $this->dispatch('lawyer-contact', [
                'phone' => $lawyer->phone,
                'lawyerId'  => $lawyer->id,
            ]);
            session()->flash('message', 'تماس با موفقیت کپی ثبت شد.');
            $this->lawyers = $this->getActiveLawyersForSkill($skill);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    private function recordView(Skill $skill)
    {
        Skill::where($skill->id)->update([
            'total_clicks' => DB::raw('total_clicks + 1'),
        ]);
    }

    private function recordClick(Skill $skill, $lawyerId)
    {
        SkillClick::create([
            'skill_id' => $skill->id,
            'lawyer_id' => $lawyerId,
            'type' => 'click',
            'cost' => $skill->click_price,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'metadata' => json_encode([
                'skill_is_active' => $skill->is_active
            ]),
        ]);
    }

    private function recordCall(Skill $skill, Lawyer $lawyer)
    {
        SkillClick::create([
            'skill_id' => $skill->id,
            'lawyer_id' => $lawyer->id,
            'type' => 'call',
            'cost' => $skill->click_price,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'metadata' => json_encode([
                'call_time' => now()->toDateTimeString(),
                'caller_ip' => request()->ip(),
                'skill_is_active' => $skill->is_active,
                'lawyer_skill_is_active' => true
            ]),
        ]);
    }

    private function deductFromWallet(Lawyer $lawyer, $amount, Skill $skill)
    {
        $walletService = app(WalletService::class);

        $walletService->withdraw($lawyer->user->wallet, $amount, [
            'type' => 'payment',
            'description' => 'کسر بابت تماس با وکیل برای مهارت ' . $skill->name,
            'reference_id' => 'SKILL_CALL_' . $skill->id . '_' . now()->timestamp,
        ]);
    }

    private function sendSmsToLawyer(Lawyer $lawyer, Skill $skill)
    {
        $smsService = app(SmsService::class);

        $message = "یک تماس جدید برای مهارت {$skill->name}\n"
            . "از طریق سایت\n"
            . "مبلغ: " . number_format($skill->click_price) . " ریال\n"
            . "زمان: " . now()->format('Y/m/d H:i');

        $smsService->sendSms($lawyer->phone, $message);
    }

    public function render()
    {
        // فیلتر کردن مهارت‌ها بر اساس جستجو
        $filteredSkills = $this->skills->filter(function ($skill) {
            return empty($this->search) ||
                str_contains(mb_strtolower($skill->name), mb_strtolower($this->search));
        });

        return view('livewire.skill-selection', [
            'filteredSkills' => $filteredSkills
        ])->layout('components.layouts.app');
    }
}
