<?php
// app/Livewire/Profile/ChangeEmail.php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\VerificationCode;
use App\Services\VerificationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChangeEmail extends Component
{
    public $newEmail;
    public $currentEmail;
    public $code;
    public $step = 1; // 1: enter email, 2: verify code
    public $message;
    public $messageType;

    protected $rules = [
        'newEmail' => 'required|email|unique:users,email',
        'code' => 'required|string|size:6',
    ];

    protected $verificationService;

    public function boot(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function mount()
    {
        $this->currentEmail = auth()->user()->email;
    }

    public function sendVerification()
    {
        $this->validate(['newEmail' => 'required|email|unique:users,email']);

        try {
            $user = auth()->user();

            // بررسی اینکه ایمیل جدید با ایمیل فعلی یکی نباشد
            if ($this->newEmail === $user->email) {
                $this->message = 'ایمیل جدید با ایمیل فعلی یکی است';
                $this->messageType = 'error';
                return;
            }

            $this->verificationService->sendCode(
                $user,
                VerificationCode::TYPE_EMAIL,
                $this->newEmail
            );

            $this->step = 2;
            $this->message = 'کد تأیید به ایمیل جدید ارسال شد';
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
                VerificationCode::TYPE_EMAIL,
                $this->newEmail,
                $this->code
            );

            if (!$verified) {
                $this->message = 'کد تأیید نامعتبر یا منقضی شده است';
                $this->messageType = 'error';
                return;
            }

            // تغییر ایمیل
            $user->update(['email' => $this->newEmail]);

            $this->message = 'ایمیل با موفقیت تغییر یافت';
            $this->messageType = 'success';

            // ریست کردن فرم
            $this->reset(['newEmail', 'code', 'step']);
            $this->currentEmail = $user->email;

            // ارسال نوتیفیکیشن به ایمیل قدیمی
            // Mail::to($user->email)->send(new EmailChangedNotification());

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
                VerificationCode::TYPE_EMAIL,
                $this->newEmail
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
        return view('livewire.profile.change-email');
    }
}
