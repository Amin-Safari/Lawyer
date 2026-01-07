<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    public function deposit(Wallet $wallet, float $amount, array $data = []): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $amount, $data) {
            $transaction = $wallet->transactions()->create(array_merge([
                'user_id' => $wallet->user_id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => WalletTransaction::TYPE_DEPOSIT,
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance + $amount,
                'status' => WalletTransaction::STATUS_PENDING,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $data));

            $wallet->increment('balance', $amount);

            $transaction->markAsCompleted();

            return $transaction;
        });
    }

    public function withdraw(Wallet $wallet, float $amount, array $data = []): WalletTransaction
    {
        if (!$wallet->canWithdraw($amount)) {
            throw new Exception('موجودی کافی نیست یا کیف پول غیرفعال است');
        }

        return DB::transaction(function () use ($wallet, $amount, $data) {
            $transaction = $wallet->transactions()->create(array_merge([
                'user_id' => $wallet->user_id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => WalletTransaction::TYPE_WITHDRAWAL,
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance - $amount,
                'status' => WalletTransaction::STATUS_PENDING,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $data));

            $wallet->decrement('balance', $amount);

            $transaction->markAsCompleted();

            return $transaction;
        });
    }

    public function transfer(Wallet $fromWallet, Wallet $toWallet, float $amount, array $data = []): array
    {
        if (!$fromWallet->canWithdraw($amount)) {
            throw new Exception('موجودی کافی نیست یا کیف پول مبدا غیرفعال است');
        }

        return DB::transaction(function () use ($fromWallet, $toWallet, $amount, $data) {
            // برداشت از کیف پول مبدا
            $withdrawal = $this->withdraw($fromWallet, $amount, array_merge($data, [
                'type' => WalletTransaction::TYPE_TRANSFER,
                'description' => 'انتقال به کیف پول ' . $toWallet->wallet_number,
            ]));

            // واریز به کیف پول مقصد
            $deposit = $this->deposit($toWallet, $amount, array_merge($data, [
                'type' => WalletTransaction::TYPE_TRANSFER,
                'description' => 'دریافت از کیف پول ' . $fromWallet->wallet_number,
            ]));

            return [
                'withdrawal' => $withdrawal,
                'deposit' => $deposit,
            ];
        });
    }

    private function generateTransactionId(): string
    {
        return 'TXN' . now()->format('YmdHis') . random_int(1000, 9999);
    }
}
