<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // ایجاد کیف پول برای کاربر
        if (!$user->wallet) {
            Wallet::create([
                'user_id' => $user->id,
                'wallet_number' => $this->generateWalletNumber($user),
                'balance' => 0,
                'pending_balance' => 0,
                'status' => 'active',
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * تولید شماره کیف پول
     */
    private function generateWalletNumber(User $user): string
    {
        return 'WAL' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . date('Ymd');
    }
}
