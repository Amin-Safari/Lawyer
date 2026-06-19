<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'type' => 'required|in:wallet_charge,service_payment',
            'description' => 'nullable|string',
        ]);

//        $transaction = WalletTransaction::create([
//            'wallet_id' => auth()->user()->wallet->id,
//            'user_id' => auth()->id(),
//            'transaction_id' => 'TXN_' . time() . '_' . rand(1000, 9999),
//            'type' => 'deposit',
//            'amount' => $request->amount,
//            'balance_before' => auth()->user()->wallet->balance,
//            'balance_after' => auth()->user()->wallet->balance + $request->amount,
//            'status' => 'pending',
//            'description' => $request->description ?? 'شارژ کیف پول',
//            'metadata' => [
//                'payment_type' => $request->type,
//                'user_ip' => $request->ip(),
//            ],
//        ]);

        $tempTransactionId = 'TXN' . now()->format('YmdHis') . random_int(1000, 9999);

        session(['payment_temp_id' => $tempTransactionId]);
        session(['payment_amount' => $request->amount]);
        session(['payment_description' => $request->description]);

        $invoice = new Invoice;
        $invoice->amount($request->amount);
        $invoice->detail([
            'description' => $request->description ?? 'شارژ کیف پول در ' . config('app.name'),
            'email' => auth()->user()->email,
            'mobile' => auth()->user()->lawyer->phone,
        ]);

//        session(['payment_transaction_id' => $transaction->transaction_id]);

        return Payment::callbackUrl(route('lawyer.payment.callback'))->purchase($invoice, function($driver, $transactionId) {
            session(['payment_gateway_id' => $transactionId]);
        })->pay()->render();
    }

    public function callback(Request $request)
    {
        $tempTransactionId = session('payment_temp_id');
        $amount = session('payment_amount');
        $description = session('payment_description');
//        $transaction = WalletTransaction::where('transaction_id', $transactionId)->firstOrFail();

        try {
            $receipt = Payment::amount($amount)
                ->transactionId(session('payment_gateway_id'))
                ->verify();


            $walletService = app(WalletService::class);
            $transaction = $walletService->deposit(
                auth()->user()->wallet,
                $amount,
                [
                    'reference_id' => $receipt->getReferenceId(),
                    'description' => $description ?? 'شارژ کیف پول از طریق درگاه بانکی',
                    'gateway' => 'zarinpal',
                    'metadata' => [
                        'receipt' => $receipt->getDetail('name'),
                        'payment_type' => session('payment_type'),
                    ]
                ]
            );
            session()->forget(['payment_temp_id', 'payment_amount', 'payment_description', 'payment_gateway_id']);

//            $transaction->update([
//                'status' => 'completed',
//                'completed_at' => now(),
//                'reference_id' => $receipt->getReferenceId(),
//            ]);

            return redirect()->route('lawyer.dashboard')
                ->with('success', 'پرداخت با موفقیت انجام شد و کیف پول شما شارژ شد.');

        } catch (\Exception $e) {
            $transaction->update([
                'status' => 'failed',
                'failed_at' => now(),
                'failed_reason' => $e->getMessage(),
            ]);

            return redirect()->route('lawyer.dashboard')
                ->with('error', 'پرداخت ناموفق بود. لطفاً مجدداً تلاش کنید.');
        }
    }
}
