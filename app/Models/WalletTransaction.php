<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'currency',
        'status',
        'gateway',
        'reference_id',
        'description',
        'metadata',
        'completed_at',
        'failed_at',
        'failed_reason',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_PAYMENT = 'payment';
    const TYPE_REFUND = 'refund';
    const TYPE_BONUS = 'bonus';
    const TYPE_FEE = 'fee';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now(),
            'failed_reason' => $reason,
        ]);
    }

    public function getFormattedAmount(): string
    {
        $sign = in_array($this->type, [self::TYPE_WITHDRAWAL, self::TYPE_PAYMENT, self::TYPE_FEE]) ? '-' : '+';
        return $sign . number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
