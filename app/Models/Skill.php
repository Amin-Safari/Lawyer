<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'click_price',
        'total_clicks',
        'is_active',
    ];

    protected $casts = [
        'click_price' => 'decimal:0',
        'is_active' => 'boolean',
        'total_clicks' => 'integer',
    ];

    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(Lawyer::class, 'lawyer_skill')
            ->withTimestamps();
    }

    public function clicks()
    {
        return $this->hasMany(SkillClick::class);
    }

    public function getTotalRevenueAttribute()
    {
        return $this->clicks()->where('type', 'call')->sum('cost');
    }

    public function getActiveLawyersAttribute()
    {
        return $this->lawyers()
            ->where('is_active', true)
            ->whereHas('user.wallet', function ($query) {
                $query->where('balance', '>', 0)
                    ->where('status', 'active');
            })
            ->get();
    }
}
