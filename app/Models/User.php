<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // رابطه با وکیل
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class);
    }

    // رابطه با کیف پول
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    // رابطه با تراکنش‌های کیف پول
    public function walletTransactions()
    {
        return $this->hasManyThrough(
            WalletTransaction::class,
            Wallet::class,
            'user_id', // Foreign key on wallets table
            'wallet_id', // Foreign key on wallet_transactions table
            'id', // Local key on users table
            'id' // Local key on wallets table
        );
    }
}
