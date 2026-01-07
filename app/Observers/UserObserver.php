<?php

namespace App\Observers;

use App\Models\Wallet;

class UserObserver
{
    public function created($user): void
    {
        if (!$user->wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_number' => (new Wallet())->generateWalletNumber(),
            ]);

            $user->wallet()->save($wallet);
        }
    }
}
