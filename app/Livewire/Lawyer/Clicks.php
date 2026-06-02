<?php

namespace App\Livewire\Lawyer;

use App\Models\SkillClick;
use Livewire\Component;
use Livewire\WithPagination;

class Clicks extends Component
{
    use WithPagination;

    public $lawyer;
    public $timeRange = 'month'; // day, week, month, year
    public $typeFilter = 'all'; // all, view, click, call
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
                switch ($this->timeRange) {
                    case 'day': return $q->today();
                    case 'week': return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    case 'month': return $q->thisMonth();
                    case 'year': return $q->whereYear('created_at', now()->year);
                }
            });

        $this->stats = [
            'total' => $query->count(),
            'views' => $query->clone()->where('type', 'view')->count(),
            'clicks' => $query->clone()->where('type', 'click')->count(),
            'calls' => $query->clone()->where('type', 'call')->count(),
            'cost' => $query->clone()->where('type', 'call')->sum('cost'),
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['timeRange', 'typeFilter', 'skillFilter'])) {
            $this->resetPage();
            $this->loadStats();
        }
    }

    public function render()
    {
        $clicks = SkillClick::with('skill')
            ->where('lawyer_id', $this->lawyer->id)
            ->when($this->timeRange, function ($q) {
                switch ($this->timeRange) {
                    case 'day': return $q->today();
                    case 'week': return $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    case 'month': return $q->thisMonth();
                    case 'year': return $q->whereYear('created_at', now()->year);
                }
            })
            ->when($this->typeFilter && $this->typeFilter !== 'all', function ($q) {
                return $q->where('type', $this->typeFilter);
            })
            ->when($this->skillFilter, function ($q) {
                return $q->where('skill_id', $this->skillFilter);
            })
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->whereHas('skill', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('ip_address', 'like', '%' . $this->search . '%');
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
}
