<?php

namespace App\Livewire;

use App\Models\Skill;
use App\Models\Lawyer;
use App\Models\SkillClick;
use App\Services\WalletService;
use App\Services\SmsService;
use Livewire\Component;

class SkillSelection extends Component
{
    public $skills;
    public $selectedSkillId;
    public $lawyers = [];
    public $search = '';

    public function mount()
    {
        $this->skills = Skill::where('is_active', true)
            ->orderBy('total_clicks', 'desc')
            ->limit(20)
            ->get();
    }

    public function selectSkill($skillId)
    {
        $this->selectedSkillId = $skillId;
        $skill = Skill::findOrFail($skillId);

        // ثبت کلیک نمایش مهارت
        $this->recordView($skill);

        // دریافت وکلا
        $this->lawyers = $this->getActiveLawyersForSkill($skill);
    }

    private function getActiveLawyersForSkill(Skill $skill)
    {
        return $skill->lawyers()
//            ->wherePivot('is_active', true)
            ->whereHas('user.wallet', function ($query) {
                $query->where('balance', '>', 0)
                    ->where('status', 'active');
            })
            ->with(['user', 'province', 'city', 'skills'])
            ->get();
    }

    public function viewLawyer($lawyerId)
    {
        $skill = Skill::find($this->selectedSkillId);
        if ($skill) {
            // ثبت کلیک روی وکیل
            $this->recordClick($skill, $lawyerId);
        }

        // ریدایرکت به صفحه وکیل
        return redirect()->route('lawyer.profile.public', [
            'lawyer' => $lawyerId,
            'skill' => $this->selectedSkillId
        ]);
    }

    public function callLawyer($lawyerId)
    {
        $skill = Skill::find($this->selectedSkillId);
        $lawyer = Lawyer::with('user.wallet')->find($lawyerId);

        if (!$lawyer || !$lawyer->user || !$lawyer->user->wallet) {
            session()->flash('error', 'وکیل مورد نظر یافت نشد یا کیف پول فعال ندارد.');
            return;
        }

        // ثبت تماس
        $this->recordCall($skill, $lawyer);

        // کسر هزینه از کیف پول وکیل
        $this->deductFromWallet($lawyer, $skill->click_price);

        // ارسال SMS به وکیل
        $this->sendSmsToLawyer($lawyer, $skill);

        session()->flash('message', 'تماس با موفقیت ثبت شد. وکیل به زودی با شما تماس خواهد گرفت.');
    }

    private function recordView(Skill $skill)
    {
        SkillClick::create([
            'skill_id' => $skill->id,
            'type' => SkillClick::TYPE_VIEW,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'referrer' => url()->previous(),
                'selected_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    private function recordClick(Skill $skill, $lawyerId)
    {
        SkillClick::create([
            'skill_id' => $skill->id,
            'lawyer_id' => $lawyerId,
            'type' => SkillClick::TYPE_CLICK,
            'cost' => $skill->click_price,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
        ]);
    }

    private function recordCall(Skill $skill, Lawyer $lawyer)
    {
        SkillClick::create([
            'skill_id' => $skill->id,
            'lawyer_id' => $lawyer->id,
            'type' => SkillClick::TYPE_CALL,
            'cost' => $skill->click_price,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'call_time' => now()->toDateTimeString(),
                'caller_ip' => request()->ip(),
            ],
        ]);
    }

    private function deductFromWallet(Lawyer $lawyer, $amount, Skill $skill)
    {
        $walletService = app(WalletService::class);

        try {
            $walletService->withdraw($lawyer->user->wallet, $amount, [
                'type' => 'payment',
                'description' => 'کسر بابت کلیک تماس مهارت',
                'reference_id' => 'CLICK_' . now()->timestamp,
            ]);
        } catch (\Exception $e) {
            // اگر موجودی کافی نبود، غیرفعال کردن مهارت برای این وکیل
            $lawyer->skills()->updateExistingPivot($skill->id, [
                'is_active' => false
            ]);

            throw new \Exception('موجودی کیف پول وکیل کافی نیست.');
        }
    }

    private function sendSmsToLawyer(Lawyer $lawyer, Skill $skill)
    {
        $smsService = app(SmsService::class);

        $message = " یک تماس جدید برای مهارت {$skill->name}\n"
            . " از طریق سایت\n"
            . " مبلغ: " . number_format($skill->click_price) . " ریال\n"
            . " زمان: " . now()->format('Y/m/d H:i');

        $smsService->send($lawyer->phone, $message);
    }

    public function render()
    {
        return view('livewire.skill-selection')
            ->layout('components.layouts.app');
    }
}
