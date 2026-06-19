<?php
// app/Livewire/Profile/ChangePassword.php

namespace App\Livewire\Profile;

use App\Models\VerificationCode;
use App\Services\VerificationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $code;
    public $step = 1; // 1: enter password, 2: verify code
    public $message;
    public $messageType;

    protected $rules = [
        'currentPassword' => 'required|string',
        'newPassword' => 'required|string|min:8|confirmed',
        'newPasswordConfirmation' => 'required|string|min:8',
        'code' => 'required|string|size:6',
    ];

    protected $verificationService;

    public function boot(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function sendVerification()
    {
        $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
            'newPasswordConfirmation' => 'required|string|min:8',
        ]);

        try {
            $user = auth()->user();

            // بررسی رمز فعلی
            if (!Hash::check($this->currentPassword, $user->password)) {
                $this->message = 'رمز عبور فعلی صحیح نیست';
                $this->messageType = 'error';
                return;
            }

            // ارسال کد تأیید به ایمیل
            $this->verificationService->sendCode(
                $user,
                VerificationCode::TYPE_PASSWORD,
                $user->email
            );

            $this->step = 2;
            $this->message = 'کد تأیید به ایمیل شما ارسال شد';
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
                VerificationCode::TYPE_PASSWORD,
                $user->email,
                $this->code
            );

            if (!$verified) {
                $this->message = 'کد تأیید نامعتبر یا منقضی شده است';
                $this->messageType = 'error';
                return;
            }

            // تغییر رمز عبور
            $user->update([
                'password' => Hash::make($this->newPassword)
            ]);

            $this->message = 'رمز عبور با موفقیت تغییر یافت';
            $this->messageType = 'success';

            $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation', 'code', 'step']);

            // لاگ‌اوت کاربر و هدایت به صفحه لاگین
            session()->flash('message', 'رمز عبور تغییر یافت. لطفاً مجدداً وارد شوید.');
            Auth::logout();
            return redirect()->route('login');

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
                VerificationCode::TYPE_PASSWORD,
                $user->email
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
        return view('livewire.profile.change-password');
    }
}
