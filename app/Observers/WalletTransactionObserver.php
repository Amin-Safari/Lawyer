<?php

namespace App\Observers;

use App\Models\WalletTransaction;
use App\Models\Wallet;
use Exception;

class WalletTransactionObserver
{
    /**
     * وقتی تراکنش در حال ایجاد است (قبل از ذخیره)
     */
    public function creating(WalletTransaction $transaction): void
    {
        // محاسبه موجودی قبل و بعد
        if (!$transaction->balance_before && $transaction->wallet_id) {
            $wallet = $transaction->wallet;
            $transaction->balance_before = $wallet?->balance ?? 0;

            // محاسبه موجودی بعد بر اساس نوع تراکنش
            if (in_array($transaction->type, ['withdrawal', 'payment', 'fee'])) {
                $transaction->balance_after = $transaction->balance_before - $transaction->amount;
            } else {
                $transaction->balance_after = $transaction->balance_before + $transaction->amount;
            }
        }
    }

    /**
     * بعد از اینکه تراکنش ذخیره شد (تغییر موجودی کیف پول)
     */
    public function created(WalletTransaction $transaction): void
    {
        try {
            $wallet = $transaction->wallet;

            if (!$wallet) {
                throw new Exception('کیف پول یافت نشد');
            }

            // بر اساس نوع تراکنش، موجودی کیف پول را تغییر بده
            if (in_array($transaction->type, ['deposit', 'bonus', 'refund'])) {
                // افزایش موجودی
                $wallet->increment('balance', $transaction->amount);

            } elseif (in_array($transaction->type, ['withdrawal', 'payment', 'fee'])) {
                // کاهش موجودی
                if ($wallet->balance >= $transaction->amount) {
                    $wallet->decrement('balance', $transaction->amount);
                } else {
                    throw new Exception('موجودی کیف پول کافی نیست');
                }

            } elseif ($transaction->type === 'transfer') {
                // انتقال: خودش در سرویس مدیریت می‌شود
                // اینجا کاری نمی‌کنیم چون دو طرفه است
            }

            // اگر تراکنش completed است و کیف پول تغییر کرد
            if ($transaction->status === 'completed') {
                // اطمینان از هماهنگی موجودی
                $wallet->refresh();

                // اگر موجودی بعد با موجودی فعلی هماهنگ نیست، آپدیت کن
                if ($wallet->balance != $transaction->balance_after) {
                    $transaction->balance_after = $wallet->balance;
                    $transaction->saveQuietly(); // بدون ایجاد حلقه بی‌نهایت
                }
            }

        } catch (Exception $e) {
            // لاگ خطا
            \Log::error('خطا در بروزرسانی کیف پول: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'wallet_id' => $transaction->wallet_id,
                'amount' => $transaction->amount,
            ]);

            // اگر خطا خورد، تراکنش را failed کن
            $transaction->update([
                'status' => 'failed',
                'failed_at' => now(),
                'failed_reason' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * قبل از آپدیت تراکنش
     */
    public function updating(WalletTransaction $transaction): void
    {
        // اگر وضعیت به completed تغییر کرد
        if ($transaction->isDirty('status') && $transaction->status === 'completed') {
            // اگر قبلاً موجودی کیف پول تغییر نکرده بود
            $originalStatus = $transaction->getOriginal('status');

            if ($originalStatus !== 'completed') {
                // موجودی کیف پول را دوباره محاسبه کن
                $wallet = $transaction->wallet;
                $currentBalance = $wallet->balance;

                if (in_array($transaction->type, ['deposit', 'bonus', 'refund'])) {
                    $transaction->balance_after = $currentBalance + $transaction->amount;
                } elseif (in_array($transaction->type, ['withdrawal', 'payment', 'fee'])) {
                    $transaction->balance_after = $currentBalance - $transaction->amount;
                }

                $transaction->balance_before = $currentBalance;
            }
        }
    }

    /**
     * بعد از آپدیت تراکنش
     */
    public function updated(WalletTransaction $transaction): void
    {
        // اگر وضعیت از pending به completed تغییر کرد
        if ($transaction->wasChanged('status') && $transaction->status === 'completed') {
            $this->syncWalletBalance($transaction);
        }

        // اگر وضعیت از pending به failed یا cancelled تغییر کرد
        if ($transaction->wasChanged('status') && in_array($transaction->status, ['failed', 'cancelled'])) {
            // اگر قبلاً موجودی تغییر کرده بود، برگردان
            $originalStatus = $transaction->getOriginal('status');
            if ($originalStatus === 'completed') {
                $this->revertWalletBalance($transaction);
            }
        }
    }

    /**
     * هماهنگ‌سازی موجودی کیف پول با تراکنش
     */
    private function syncWalletBalance(WalletTransaction $transaction): void
    {
        $wallet = $transaction->wallet;
        $expectedBalance = $transaction->balance_after;

        if ($wallet->balance != $expectedBalance) {
            $difference = $expectedBalance - $wallet->balance;
            $wallet->increment('balance', $difference);

            \Log::info('موجودی کیف پول هماهنگ شد', [
                'wallet_id' => $wallet->id,
                'old_balance' => $wallet->balance - $difference,
                'new_balance' => $wallet->balance,
                'expected_balance' => $expectedBalance,
            ]);
        }
    }

    /**
     * برگرداندن تغییرات موجودی (برای تراکنش‌های ناموفق یا لغو شده)
     */
    private function revertWalletBalance(WalletTransaction $transaction): void
    {
        $wallet = $transaction->wallet;

        if (in_array($transaction->type, ['deposit', 'bonus', 'refund'])) {
            // اگر واریز بوده، کم کن
            $wallet->decrement('balance', $transaction->amount);
        } elseif (in_array($transaction->type, ['withdrawal', 'payment', 'fee'])) {
            // اگر برداشت بوده، زیاد کن
            $wallet->increment('balance', $transaction->amount);
        }

        \Log::info('تغییرات کیف پول به دلیل لغو تراکنش برگردانده شد', [
            'transaction_id' => $transaction->id,
            'wallet_id' => $wallet->id,
            'amount' => $transaction->amount,
        ]);
    }
}
