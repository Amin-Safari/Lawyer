<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificationService
{
    public function generateCode($user, $type, $target)
    {
        // حذف کدهای قبلی
        VerificationCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('target', $target)
            ->delete();

        // تولید کد ۶ رقمی
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'target' => $target,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public function sendCode($user, $type, $target)
    {
        $verification = $this->generateCode($user, $type, $target);
        $code = $verification->code;

        switch ($type) {
            case VerificationCode::TYPE_EMAIL:
                $this->sendEmail($user, $target, $code);
                break;
            case VerificationCode::TYPE_PHONE:
                $this->sendSms($user, $target, $code);
                break;
            case VerificationCode::TYPE_PASSWORD:
                $this->sendEmail($user, $user->email, $code);
                break;
        }

        return $verification;
    }

    protected function sendEmail($user, $email, $code)
    {
        try {
            Mail::send('emails.verification-code', [
                'user' => $user,
                'code' => $code,
                'type' => 'تغییر اطلاعات حساب'
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('کد تأیید تغییر اطلاعات');
            });
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            throw new \Exception('ارسال ایمیل با خطا مواجه شد');
        }
    }

    protected function sendSms($user, $phone, $code)
    {
        try {
             $api = new SmsService();
             $api->sendSms( $phone, "کد تأیید شما: $code");
            Log::info("SMS Code for $phone: $code");
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            throw new \Exception('ارسال پیامک با خطا مواجه شد');
        }
    }

    public function verifyCode($user, $type, $target, $code)
    {
        $verification = VerificationCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('target', $target)
            ->where('code', $code)
            ->valid()
            ->first();

        if (!$verification) {
            return false;
        }

        $verification->update(['used_at' => now()]);
        return true;
    }
}
