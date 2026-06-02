<?php

namespace App\Livewire\Auth;

use App\Models\Lawyer;
use App\Models\User;
use App\Models\Skill;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Register extends Component
{
    public $step = 1;
    public $email;
    public $password;
    public $password_confirmation;
    public $name;
    public $phone;
    public $province_id;
    public $city_id;
    public $description;
    public $attorneys_license;
    public $skills = [];
    public $verificationCode;
    public $code;
    public $verificationSent = false;
    public $countdown = 0;

    public $provinces = [];
    public $cities = [];

    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|regex:/^09[0-9]{9}$/|unique:lawyers,phone',
        'province_id' => 'required|exists:provinces,id',
        'city_id' => 'required|exists:cities,id',
        'description' => 'required|string|min:100',
        'attorneys_license' => 'required|string|max:50|unique:lawyers,attorneys_license',
        'skills' => 'required|array|min:1',
    ];

    public function generateCode()
    {
        $this->code = random_int(10000, 99999);
        return $this->code;
    }

    public function mount()
    {
        $this->provinces = \App\Models\Province::all();
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $this->cities = \App\Models\City::where('province_id', $value)->get();
        } else {
            $this->cities = [];
            $this->city_id = null;
        }
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'name' => 'required|string|max:255',
            ]);

            $this->step = 2;
        } elseif ($this->step == 2) {
            $this->validate([
                'phone' => 'required|string|regex:/^09[0-9]{9}$/|unique:lawyers,phone',
                'province_id' => 'required|exists:provinces,id',
                'city_id' => 'required|exists:cities,id',
                'description' => 'required|string|min:100',
                'attorneys_license' => 'required|string|max:50|unique:lawyers,attorneys_license',
            ]);


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

            $this->step = 3;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function verifyAndRegister()
    {
        $this->validate([
            'verificationCode' => 'required|digits:5',
            'skills' => 'required|array|min:1',
        ]);

        // بررسی انقضای کد
        $expiresAt = session('verification_expires_' . $this->phone);
        if (!$expiresAt || now()->timestamp > $expiresAt) {
            $this->addError('verificationCode', 'کد تأیید منقضی شده است. لطفاً درخواست کد جدید دهید.');
            return;
        }

        // بررسی صحت کد
        $storedCode = session('verification_code_' . $this->phone);
        if (!$storedCode || $storedCode != $this->verificationCode) {
            $this->addError('verificationCode', 'کد تأیید وارد شده صحیح نیست.');
            return;
        }

        // شروع تراکنش دیتابیس
        \DB::beginTransaction();

        try {
            // ایجاد کاربر
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // ایجاد وکیل
            $lawyer = Lawyer::create([
                'user_id' => $user->id,
                'phone' => $this->phone,
                'description' => $this->description,
                'province_id' => $this->province_id,
                'city_id' => $this->city_id,
                'address' => 'آدرس وارد نشده',
                'attorneys_license' => $this->attorneys_license,
                // 'is_active' => true, // این ستون در جدول lawyers وجود ندارد
            ]);

            // اتصال مهارت‌ها - فقط IDها را سینک می‌کنیم بدون داده اضافی
            // اینجا فقط آرایه‌ای از IDها را پاس می‌دهیم
            if (!empty($this->skills)) {
                // سینک کردن ساده بدون داده اضافی
                $lawyer->skills()->sync($this->skills);
            } else {
                throw new \Exception('مهارت‌های انتخاب شده معتبر نیستند.');
            }

            // ایجاد کیف پول
            $wallet = $user->wallet()->create([
                'wallet_number' => 'WAL' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . now()->timestamp,
                'balance' => 0,
                'pending_balance' => 0,
                'status' => 'active',
            ]);

            // پاک کردن کد تأیید از session
            session()->forget('verification_code_' . $this->phone);
            session()->forget('verification_expires_' . $this->phone);

            // تأیید تراکنش
            \DB::commit();

            // ورود خودکار
            Auth::login($user);

            session()->flash('success', 'ثبت‌نام شما با موفقیت انجام شد.');
            return redirect()->route('lawyer.dashboard');

        } catch (\Exception $e) {
            \DB::rollBack();

            Log::error('Registration failed', [
                'email' => $this->email,
                'phone' => $this->phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('general', 'خطا در ثبت‌نام: ' . $e->getMessage());

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'خطا در ثبت‌نام. لطفاً دوباره تلاش کنید.'
            ]);

            return;
        }
    }

    public function resendCode()
    {
        if ($this->countdown > 0) {
            $this->addError('verificationCode', 'لطفاً قبل از ارسال مجدد منتظر بمانید.');
            return;
        }

        $smsService = app(SmsService::class);
        $result = $smsService->sendSms($this->phone, 'your code is ' . $this->generateCode());

        if ($result['success']) {
            $this->countdown = 120;
            $this->startCountdown();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'کد جدید ارسال شد.'
            ]);
        } else {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'خطا در ارسال پیامک: ' . ($result['message'] ?? 'خطای نامشخص')
            ]);
        }
    }

    private function startCountdown()
    {
        $this->dispatch('start-countdown');
    }

    public function render()
    {
        $allSkills = Skill::where('is_active', true)->get();

        return view('livewire.auth.register', [
            'allSkills' => $allSkills,
        ])->layout('components.layouts.auth');
    }
}
