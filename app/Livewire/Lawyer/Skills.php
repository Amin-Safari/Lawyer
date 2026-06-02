<?php

namespace App\Livewire\Lawyer;

use App\Models\Skill;
use Livewire\Component;

class Skills extends Component
{
    public $lawyer;
    public $skills = [];
    public $allSkills = [];
    public $selectedSkills = [];
    public $prices = [];
    public $isActive = [];

    protected $rules = [
        'selectedSkills' => 'required|array|min:1',
        'prices.*' => 'nullable|numeric|min:0',
        'isActive.*' => 'boolean',
    ];

    public function mount()
    {
        $this->lawyer = auth()->user()->lawyer;
        $this->loadSkills();
    }

    public function loadSkills()
    {
        $this->allSkills = Skill::where('is_active', true)->get();

        $lawyerSkills = $this->lawyer->skills()->get();

        foreach ($lawyerSkills as $skill) {
            $this->selectedSkills[] = $skill->id;
            $this->prices[$skill->id] = $skill->pivot->click_price ?? $skill->click_price;
            $this->isActive[$skill->id] = $skill->pivot->is_active ?? true;
        }
    }

    public function updateSkills()
    {
        $this->validate();

        // آماده‌سازی داده‌ها برای sync
        $syncData = [];
        foreach ($this->selectedSkills as $skillId) {
            $syncData[$skillId] = [
                'click_price' => $this->prices[$skillId] ?? Skill::find($skillId)->click_price,
                'is_active' => $this->isActive[$skillId] ?? true,
            ];
        }

        // sync مهارت‌ها
        $this->lawyer->skills()->sync($syncData);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'مهارت‌ها با موفقیت به‌روزرسانی شدند.'
        ]);
    }

    public function render()
    {
        return view('livewire.lawyer.skills')
            ->layout('components.layouts.lawyer', [
                'title' => 'مدیریت مهارت‌ها'
            ]);
    }
}
