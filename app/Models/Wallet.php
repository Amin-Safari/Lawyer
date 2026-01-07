<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'status',
        'wallet_number',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function activeTransactions(): HasMany
    {
        return $this->transactions()->where('status', 'completed');
    }

    public function pendingTransactions(): HasMany
    {
        return $this->transactions()->where('status', 'pending');
    }

    public function canWithdraw($amount): bool
    {
        return $this->balance >= $amount && $this->status === 'active';
    }

    public function generateWalletNumber(): string
    {
        return 'WAL' . str_pad($this->user_id, 6, '0', STR_PAD_LEFT) . now()->format('Ymd');
    }
}
