<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'lawyer_id',
        'clicker_id',
        'ip_address',
        'user_agent',
        'referrer',
        'type',
        'cost',
        'session_id',
        'metadata',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'metadata' => 'array',
    ];

    const TYPE_VIEW = 'view';
    const TYPE_CLICK = 'click';
    const TYPE_CALL = 'call';

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function clicker()
    {
        return $this->belongsTo(User::class, 'clicker_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
