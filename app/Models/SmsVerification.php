<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'type',
        'verified',
        'expires_at',
        'verified_at',
        'ip_address',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    const TYPE_LOGIN = 'login';
    const TYPE_REGISTER = 'register';
    const TYPE_RESET = 'reset';
    const TYPE_WITHDRAWAL = 'withdrawal';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return !$this->verified && $this->expires_at > now();
    }

    public function markAsVerified(): void
    {
        $this->update([
            'verified' => true,
            'verified_at' => now(),
        ]);
    }

    public static function generateCode(): string
    {
        return (string) rand(10000, 99999);
    }

    /**
     * بررسی اینکه آیا کاربر اخیراً کد دریافت کرده
     */
    public static function recentlySent(string $phone, int $minutes = 3): bool
    {
        return self::where('phone', $phone)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }

    /**
     * شمارش تعداد تلاش‌های امروز
     */
    public static function todayAttempts(string $phone): int
    {
        return self::where('phone', $phone)
            ->whereDate('created_at', today())
            ->count();
    }
}
