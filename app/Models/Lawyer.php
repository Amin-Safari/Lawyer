<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Lawyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'description',
        'province_id',
        'city_id',
        'address',
        'phone',
        'attorneys_license',
    ];


    protected $casts = [
        'avatar' => 'string',
    ];
    public function clicks()
    {
        return $this->hasMany(SkillClick::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'lawyer_skill')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    // رابطه با کیف پول از طریق کاربر
    public function wallet()
    {
        return $this->hasOneThrough(
            Wallet::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        );
    }

    // رابطه مستقیم‌تر
    public function getWalletAttribute()
    {
        return $this->user->wallet ?? null;
    }

    public function skillClicks()
    {
        return $this->hasMany(SkillClick::class);
    }

    public function getActiveSkillsAttribute()
    {
        return $this->skills()->wherePivot('is_active', true)->get();
    }

    public function getExperienceYearsAttribute()
    {
        if (!$this->created_at) {
            return 0;
        }
        return $this->created_at->diffInYears(now());
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }

        // اگر آواتار اختصاصی نداشت، از تصویر پروفایل کاربر استفاده کن
        if ($this->user && $this->user->profile_image) {
            return asset($this->user->profile_image);
        }

        // تصویر پیش‌فرض
        return asset('images/default-avatar.png');
    }

}
