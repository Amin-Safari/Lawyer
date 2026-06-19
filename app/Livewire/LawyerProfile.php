<?php

namespace App\Livewire;

use App\Models\Lawyer;
use App\Models\Skill;
use App\Models\SkillClick;
use App\Services\WalletService;
use App\Services\SmsService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LawyerProfile extends Component
{
    public $lawyer;
    public $selectedSkill;
    public $skillId;

    public function mount($lawyerId, $skillId = null)
    {
        $this->lawyer = Lawyer::with(['user', 'province', 'city', 'skills', 'user.wallet'])
            ->findOrFail($lawyerId);

        $this->skillId = $skillId;

        if ($skillId) {
            $this->selectedSkill = Skill::find($skillId);
        }
    }

    public function callLawyer()
    {
        $lawyerId = $this->lawyer->id;
        $skill = $this->selectedSkill;

        // اگر مهارت انتخاب شده باشد، بررسی‌های قبلی را انجام بده
        if ($skill) {
            $lawyerSkill = DB::table('lawyer_skill')
                ->where('lawyer_id', $lawyerId)
                ->where('skill_id', $skill->id)
                ->where('is_active', true)
                ->first();

            if (!$lawyerSkill) {
                session()->flash('error', 'این مهارت برای این وکیل فعال نیست.');
                return;
            }

            // بررسی موجودی کیف پول
            if ($this->lawyer->user->wallet->balance < $skill->click_price) {
                DB::table('lawyer_skill')
                    ->where('lawyer_id', $lawyerId)
                    ->where('skill_id', $skill->id)
                    ->update(['is_active' => false]);

                session()->flash('error', 'امکان تماس وجود ندارد');
                return;
            }
        }

        $lawyer = $this->lawyer;

        if (!$lawyer || !$lawyer->phone) {
            session()->flash('error', 'شماره تماس وکیل یافت نشد.');
            return;
        }

        try {
            $try_count = SkillClick::query()
                ->where('lawyer_id', $lawyerId)
                ->where('ip_address', request()->ip())
                ->where('skill_id', $skill->id)
                ->whereDate('created_at', today())
                ->count();
            // ثبت تماس (اگر مهارت وجود داشت)
            if ($skill && !$try_count) {
                $this->recordCall($skill, $lawyer);
                $this->deductFromWallet($lawyer, $skill->click_price, $skill);
                $this->sendSmsToLawyer($lawyer, $skill);
            }

            // ارسال رویداد برای نمایش شماره
            $this->dispatch('lawyer-contact', [
                'phone'     => $lawyer->phone,
                'lawyerId'  => $lawyer->id,
            ]);

            session()->flash('message', 'شماره تماس با موفقیت نمایش داده شد.');

        } catch (\Exception $e) {
            session()->flash('error', 'خطایی رخ داد: ' . $e->getMessage());
        }
    }

    // متدهای خصوصی (کپی از کامپوننت قبلی)
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
            ]),
        ]);
    }

    private function deductFromWallet(Lawyer $lawyer, $amount, Skill $skill)
    {
        $walletService = app(WalletService::class);
        $walletService->withdraw($lawyer->user->wallet, $amount, [
            'type' => 'payment',
            'description' => 'کسر بابت تماس با وکیل برای مهارت ' . $skill->name,
            'reference_id' => 'PROFILE_CALL_' . $skill->id . '_' . now()->timestamp,
        ]);
    }

    private function sendSmsToLawyer(Lawyer $lawyer, Skill $skill)
    {
        $smsService = app(SmsService::class);

        $message = "تماس جدید برای مهارت {$skill->name}\n"
            . "از طریق صفحه پروفایل\n"
            . "مبلغ: " . number_format($skill->click_price) . " ریال\n"
            . "زمان: " . now()->format('Y/m/d H:i');

        $smsService->sendSms($lawyer->phone, $message);
    }

    public function render()
    {
        return view('livewire.lawyer-profile')
            ->layout('components.layouts.app');
    }
}
