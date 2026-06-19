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

    public $stats = [];

    public function mount()
    {
        $this->lawyer = auth()->user()->lawyer;
        $this->wallet = auth()->user()->wallet;
        $this->loadStats();
    }

    public function loadStats()
    {
        $clicksQuery = SkillClick::where('lawyer_id', $this->lawyer->id);
        $transactionsQuery = WalletTransaction::where('wallet_id', $this->wallet->id);

        // اعمال فیلتر زمانی
        match ($this->timeRange) {
            'day' => $clicksQuery->whereDate('created_at', today()),
            'week' => $clicksQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $clicksQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
            'year' => $clicksQuery->whereYear('created_at', now()->year),
        };

        match ($this->timeRange) {
            'day' => $transactionsQuery->whereDate('created_at', today()),
            'week' => $transactionsQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $transactionsQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
            'year' => $transactionsQuery->whereYear('created_at', now()->year),
        };

        $this->stats = [
            'total_calls'      => (clone $clicksQuery)->where('type', 'call')->count(),
            'total_cost'       => (clone $clicksQuery)->where('type', 'call')->sum('cost'),
            'total_deposits'   => (clone $transactionsQuery)->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals'=> (clone $transactionsQuery)->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'wallet_balance'   => $this->wallet->balance,
            'pending_balance'  => $this->wallet->pending_balance,
        ];
    }

    public function updatedTimeRange()
    {
        $this->loadStats();
    }

    public function render()
    {
        $recentClicks = SkillClick::with('skill')
            ->where('lawyer_id', $this->lawyer->id)
            ->where('type', 'call')
            ->latest()
            ->limit(5)
            ->get();

        $recentTransactions = WalletTransaction::where('wallet_id', $this->wallet->id)
            ->latest()
            ->limit(5)
            ->get();

        $chartData = $this->getChartData();

        return view('livewire.lawyer.dashboard', [
            'recentClicks' => $recentClicks,
            'recentTransactions' => $recentTransactions,
            'chartData' => $chartData,
        ])->layout('components.layouts.lawyer', [
            'title' => 'داشبورد'
        ]);
    }

    private function getChartData()
    {
        $query = SkillClick::where('lawyer_id', $this->lawyer->id)
            ->where('type', 'call')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date');

        match ($this->timeRange) {
            'day' => $query->whereDate('created_at', today()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
            'year' => $query->whereYear('created_at', now()->year),
        };

        $data = $query->pluck('count', 'date')->toArray();

        $labels = [];
        $values = [];

        if ($this->timeRange === 'week') {
            $start = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $current = $start->copy()->addDays($i);
                $dateStr = $current->format('Y-m-d');
                $labels[] = $current->format('l'); // شنبه، یکشنبه و ...
                $values[] = $data[$dateStr] ?? 0;
            }
        } elseif ($this->timeRange === 'day') {
            $labels = [now()->format('H:i')];
            $values = [($data[now()->format('Y-m-d')] ?? 0)];
        } else {
            $labels = array_keys($data);
            $values = array_values($data);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
