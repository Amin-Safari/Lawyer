<?php

namespace App\Livewire\Lawyer;

use App\Models\SkillClick;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Clicks extends Component
{
    use WithPagination;

    public $lawyer;
    public $timeRange = 'month';
    public $typeFilter = 'all';
    public $skillFilter = null;
    public $search = '';

    public $stats = [];

    public function mount()
    {
        $this->lawyer = auth()->user()->lawyer;
        $this->loadStats();
    }

    public function loadStats()
    {
        $query = SkillClick::where('lawyer_id', $this->lawyer->id)
            ->when($this->timeRange, function ($q) {
                return match ($this->timeRange) {
                    'day' => $q->whereDate('created_at', today()),
                    'week' => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
                    'year' => $q->whereYear('created_at', now()->year),
                    default => $q,
                };
            });

        $this->stats = [
            'total' => $query->count(),
            'views' => (clone $query)->where('type', 'view')->count(),
            'clicks' => (clone $query)->where('type', 'click')->count(),
            'calls' => (clone $query)->where('type', 'call')->count(),
            'cost'  => (clone $query)->where('type', 'call')->sum('cost') ?? 0,
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['timeRange', 'typeFilter', 'skillFilter', 'search'])) {
            $this->resetPage();
            $this->loadStats();
        }
    }

    public function render()
    {
        $clicks = SkillClick::with('skill')
            ->where('lawyer_id', $this->lawyer->id)
            ->when($this->timeRange, function ($q) {
                return match ($this->timeRange) {
                    'day' => $q->whereDate('created_at', today()),
                    'week' => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
                    'year' => $q->whereYear('created_at', now()->year),
                    default => $q,
                };
            })
            ->when($this->typeFilter && $this->typeFilter !== 'all', fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->skillFilter, fn($q) => $q->where('skill_id', $this->skillFilter))
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->whereHas('skill', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                        ->orWhere('user_agent', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $skills = $this->lawyer->skills()->get();

        return view('livewire.lawyer.clicks', [
            'clicks' => $clicks,
            'skills' => $skills,
        ])->layout('components.layouts.lawyer', [
            'title' => 'تاریخچه کلیک‌ها'
        ]);
    }

    // Helper methods
    public function getClickColor($type)
    {
        return match($type) {
            'view' => 'rgba(67, 97, 238, 0.2)',
            'click' => 'rgba(76, 201, 240, 0.2)',
            'call' => 'rgba(248, 150, 30, 0.2)',
            default => '#6c757d',
        };
    }

    public function getClickIcon($type)
    {
        return match($type) {
            'view' => 'bi-eye',
            'click' => 'bi-mouse',
            'call' => 'bi-telephone',
            default => 'bi-info-circle',
        };
    }

    public function getClickBadgeClass($type)
    {
        return match($type) {
            'view' => 'bg-info',
            'click' => 'bg-success',
            'call' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    public function getClickTypeText($type)
    {
        return match($type) {
            'view' => 'بازدید',
            'click' => 'کلیک',
            'call' => 'تماس',
            default => 'نامشخص',
        };
    }

    public function exportExcel()
    {
        $clicks = SkillClick::with('skill')
            ->where('lawyer_id', $this->lawyer->id)
            ->when($this->timeRange, function ($q) {
                return match ($this->timeRange) {
                    'day' => $q->whereDate('created_at', today()),
                    'week' => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
                    'year' => $q->whereYear('created_at', now()->year),
                    default => $q,
                };
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = ['تاریخ', 'مهارت', 'نوع', 'آی‌پی', 'هزینه (ریال)'];

        $filename = 'clicks_report_' . now()->format('Y-m-d_H-i') . '.csv';

        $callback = function () use ($clicks, $headers) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM برای پشتیبانی فارسی
            fputcsv($file, $headers);

            foreach ($clicks as $click) {
                fputcsv($file, [
                    $click->created_at->format('Y/m/d H:i'),
                    $click->skill->name ?? 'نامشخص',
                    $this->getClickTypeText($click->type),
                    $click->ip_address,
                    $click->cost ?? 0,
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}
