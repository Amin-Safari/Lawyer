<?php

namespace App\Livewire\Lawyer;

use App\Models\SkillClick;
use App\Models\WalletTransaction;
use Livewire\Component;

class Dashboard extends Component
{
    public $lawyer;
    public $wallet;
    public $timeRange = 'month';

    // آمارها
    public $stats = [];

    public function mount()
    {
        $this->lawyer = auth()->user()->lawyer;
        $this->wallet = auth()->user()->wallet;
        $this->loadStats();
    }

    public function loadStats()
    {
        // آمار کلیک‌ها
        $clicksQuery = SkillClick::where('lawyer_id', $this->lawyer->id);

        // اعمال فیلتر زمانی
        switch ($this->timeRange) {
            case 'day':
                $clicksQuery->whereDate('created_at', today());
                break;
            case 'week':
                $clicksQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $clicksQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $clicksQuery->whereYear('created_at', now()->year);
                break;
        }

        // آمار تراکنش‌ها
        $transactionsQuery = WalletTransaction::where('wallet_id', $this->wallet->id);

        // اعمال فیلتر زمانی مشابه
        switch ($this->timeRange) {
            case 'day':
                $transactionsQuery->whereDate('created_at', today());
                break;
            case 'week':
                $transactionsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $transactionsQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $transactionsQuery->whereYear('created_at', now()->year);
                break;
        }

        $this->stats = [
            // کلیک‌ها
            'total_clicks' => $clicksQuery->count(),
            'total_calls' => (clone $clicksQuery)->where('type', 'call')->count(),
            'total_views' => (clone $clicksQuery)->where('type', 'view')->count(),
            'total_cost' => (clone $clicksQuery)->where('type', 'call')->sum('cost'),

            // تراکنش‌ها
            'total_deposits' => (clone $transactionsQuery)->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => (clone $transactionsQuery)->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),

            // کیف پول
            'wallet_balance' => $this->wallet->balance,
            'pending_balance' => $this->wallet->pending_balance,
        ];
    }

    public function updatedTimeRange()
    {
        $this->loadStats();
    }

    public function render()
    {
        // کلیک‌های اخیر
        $recentClicks = SkillClick::with('skill')
            ->where('lawyer_id', $this->lawyer->id)
            ->latest()
            ->limit(5)
            ->get();

        // تراکنش‌های اخیر
        $recentTransactions = WalletTransaction::where('wallet_id', $this->wallet->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.lawyer.dashboard', [
            'recentClicks' => $recentClicks,
            'recentTransactions' => $recentTransactions,
        ])->layout('components.layouts.lawyer', [
            'title' => 'داشبورد'
        ]);
    }
}
