<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $phone;
    public $email;
    public $password;
    public $step = 1;
    public $verificationCode;
    public $verificationSent = false;
    public $countdown = 0;
    public $code;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'password' => 'required',
    ];

    public function generateCode()
    {
        $this->code = random_int(10000, 99999);
        return $this->code;
    }

    public function login()
    {
        $this->validate();

        if (Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            $this->phone = $user->lawyer->phone;
            $this->step = 2;

            $smsService = app(SmsService::class);
            $result = $smsService->sendSms($this->phone, 'your code is ' . $this->generateCode());

            if (!$result['success']) {
                $this->addError('phone', 'خطا در ارسال پیامک: ' . ($result['message'] ?? 'خطای نامشخص'));
                return;
            }

            session()->put('verification_code_' . $this->phone, $this->code);
            session()->put('verification_expires_' . $this->phone, now()->addMinutes(4)->timestamp);
            $this->verificationSent = true;
            $this->countdown = 120;

            $this->startCountdown();
        } else {
            $this->addError('email', 'اطلاعات ورود نامعتبر است.');
        }
    }

    public function verifyCode()
    {
        $this->validate([
            'verificationCode' => 'required|digits:5',
        ]);

        $expiresAt = session('verification_expires_' . $this->phone);
        if (!$expiresAt || now()->timestamp > $expiresAt) {
            $this->addError('verificationCode', 'کد تأیید منقضی شده است. لطفاً درخواست کد جدید دهید.');
            return;
        }

        $storedCode = session('verification_code_' . $this->phone);
        if (!$storedCode || $storedCode != $this->verificationCode) {
            $this->addError('verificationCode', 'کد تأیید وارد شده صحیح نیست.');
            return;
        }

        $user = User::query()->where('email', $this->email)->first();
        Auth::login($user);
        return redirect()->route('lawyer.dashboard');
    }

    public function resendCode()
    {
        if ($this->countdown > 0) {
            return;
        }
        $smsService = app(SmsService::class);
        $result = $smsService->sendSms($this->phone, 'your code is ' . $this->generateCode());

        if (!$result['success']) {
            $this->addError('phone', 'خطا در ارسال پیامک: ' . ($result['message'] ?? 'خطای نامشخص'));
            return;
        }

        session()->put('verification_code_' . $this->phone, $this->code);
        session()->put('verification_expires_' . $this->phone, now()->addMinutes(4)->timestamp);
        $this->verificationSent = true;
        $this->countdown = 120;

        $this->startCountdown();

        session()->flash('message', 'کد جدید ارسال شد.');
    }

    private function startCountdown()
    {
        $this->dispatch('start-countdown');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}
