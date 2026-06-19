<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'click_price',
        'total_clicks',
        'is_active'
    ];

    // رابطه many-to-many با جدول واسط lawyer_skill
    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(Lawyer::class, 'lawyer_skill')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    // وکلای فعال برای این مهارت
    public function activeLawyers(): BelongsToMany
    {
        // اگر خود مهارت غیرفعال است، خالی برگردان
        if (!$this->is_active) {
            return $this->belongsToMany(Lawyer::class, 'lawyer_skill')->whereRaw('0');
        }
        return $this->belongsToMany(Lawyer::class, 'lawyer_skill')
            ->wherePivot('is_active', true)
            ->whereHas('user.wallet', function($q) {
                $q->where('balance', '>', 1000)
                    ->where('status', 'active');
            });
    }

    public function skillClicks()
    {
        return $this->hasMany(SkillClick::class);
    }
}
