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

}
