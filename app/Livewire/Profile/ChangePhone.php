<?php
// app/Livewire/Profile/ChangePhone.php

namespace App\Livewire\Profile;

use App\Models\VerificationCode;
use App\Services\VerificationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChangePhone extends Component
{
    public $newPhone;
    public $currentPhone;
    public $code;
    public $step = 1;
    public $message;
    public $messageType;

    protected $rules = [
        'newPhone' => 'required|regex:/^09[0-9]{9}$/',
        'code' => 'required|string|size:6',
    ];

    protected $verificationService;

    public function boot(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function mount()
    {
        $this->currentPhone = auth()->user()->lawyer?->phone ?? '';
    }

    public function sendVerification()
    {
        $this->validate(['newPhone' => 'required|regex:/^09[0-9]{9}$/']);

        try {
            $user = auth()->user();

            if ($this->newPhone === $this->currentPhone) {
                $this->message = 'شماره جدید با شماره فعلی یکی است';
                $this->messageType = 'error';
                return;
            }

            $this->verificationService->sendCode(
                $user,
                VerificationCode::TYPE_PHONE,
                $this->newPhone
            );

            $this->step = 2;
            $this->message = 'کد تأیید به شماره جدید ارسال شد';
            $this->messageType = 'success';

        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function verifyCode()
    {
        $this->validate(['code' => 'required|string|size:6']);

        try {
            $user = auth()->user();

            $verified = $this->verificationService->verifyCode(
                $user,
                VerificationCode::TYPE_PHONE,
                $this->newPhone,
                $this->code
            );

            if (!$verified) {
                $this->message = 'کد تأیید نامعتبر یا منقضی شده است';
                $this->messageType = 'error';
                return;
            }

            // تغییر شماره موبایل
            $lawyer = $user->lawyer;
            $lawyer->update(['phone' => $this->newPhone]);

            $this->message = 'شماره موبایل با موفقیت تغییر یافت';
            $this->messageType = 'success';

            $this->reset(['newPhone', 'code', 'step']);
            $this->currentPhone = $lawyer->phone;

        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function resendCode()
    {
        try {
            $user = auth()->user();

            $this->verificationService->sendCode(
                $user,
                VerificationCode::TYPE_PHONE,
                $this->newPhone
            );

            $this->message = 'کد تأیید مجدداً ارسال شد';
            $this->messageType = 'success';

        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function render()
    {
        return view('livewire.profile.change-phone');
    }
}
