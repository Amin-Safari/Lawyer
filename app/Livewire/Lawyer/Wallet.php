<?php

namespace App\Livewire\Lawyer;

use App\Models\Wallet as WalletModel;
use App\Services\WalletService;
use Livewire\Component;
use Livewire\WithPagination; // ✅ اضافه کردن این خط

class Wallet extends Component
{
    use WithPagination; // ✅ اضافه کردن این خط

    public $wallet;
    public $chargeAmount = 0;
    public $withdrawAmount = 0;
    public $withdrawDescription = '';

    public $perPage = 10; // ✅ تعداد آیتم در هر صفحه (پیش‌فرض 10)

    protected $paginationTheme = 'bootstrap'; // ✅ استایل صفحه‌بندی بوت استرپ

    protected $rules = [
        'chargeAmount' => 'nullable|numeric|min:10000',
        'withdrawAmount' => 'nullable|numeric|min:10000|max:wallet.balance',
        'withdrawDescription' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'withdrawAmount.max' => 'مبلغ برداشت نمی‌تواند بیشتر از موجودی کیف پول باشد.',
    ];

    // ✅ متد برای تغییر تعداد نمایش در هر صفحه
    public function updatedPerPage($value)
    {
        $this->resetPage(); // وقتی تعداد تغییر می‌کند، به صفحه اول برو
    }

    public function mount()
    {
        $this->wallet = auth()->user()->wallet;
    }

    public function chargeWallet()
    {
        $this->validate([
            'chargeAmount' => 'required|numeric|min:10000',
        ]);

        // هدایت به درگاه پرداخت
        return redirect()->route('lawyer.payment.create', [
            'amount' => $this->chargeAmount,
            'type' => 'wallet_charge',
            'description' => 'شارژ کیف پول',
        ]);
    }

    public function withdrawWallet()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:10000|max:' . $this->wallet->balance,
            'withdrawDescription' => 'nullable|string|max:255',
        ]);

        try {
            $walletService = app(WalletService::class);

            $walletService->withdraw($this->wallet, $this->withdrawAmount, [
                'type' => 'withdrawal_request',
                'description' => $this->withdrawDescription ?: 'درخواست برداشت از کیف پول',
                'status' => 'pending',
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'درخواست برداشت شما با موفقیت ثبت شد و در حال بررسی است.'
            ]);

            $this->withdrawAmount = 0;
            $this->withdrawDescription = '';

            // بارگیری مجدد کیف پول
            $this->wallet->refresh();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // ✅ بارگیری تراکنش‌ها با صفحه‌بندی
        $recentTransactions = $this->wallet->transactions()
            ->latest()
            ->paginate($this->perPage); // تغییر از limit به paginate

        return view('livewire.lawyer.wallet', [
            'recentTransactions' => $recentTransactions,
        ])->layout('components.layouts.lawyer', [
            'title' => 'کیف پول'
        ]);
    }
}
