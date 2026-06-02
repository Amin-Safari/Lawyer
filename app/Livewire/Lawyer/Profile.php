<?php

namespace App\Livewire\Lawyer;

use App\Models\Lawyer;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $lawyer;
    public $name;
    public $email;
    public $phone;
    public $province_id;
    public $city_id;
    public $address;
    public $description;
    public $attorneys_license;
    public $avatar;
    public $avatarPreview;

    public $provinces = [];
    public $cities = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|regex:/^09[0-9]{9}$/',
        'province_id' => 'required|exists:provinces,id',
        'city_id' => 'required|exists:cities,id',
        'address' => 'required|string|max:500',
        'description' => 'required|string|min:100',
        'attorneys_license' => 'required|string|max:50',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->lawyer = auth()->user()->lawyer;
        $this->loadData();
        $this->provinces = \App\Models\Province::all();
        $this->cities = \App\Models\City::where('province_id', $this->province_id)->get();
    }

    public function loadData()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->phone = $this->lawyer->phone;
        $this->province_id = $this->lawyer->province_id;
        $this->city_id = $this->lawyer->city_id;
        $this->address = $this->lawyer->address;
        $this->description = $this->lawyer->description;
        $this->attorneys_license = $this->lawyer->attorneys_license;
        $this->avatarPreview = $this->lawyer->avatar ? Storage::url($this->lawyer->avatar) : null;
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $this->cities = \App\Models\City::where('province_id', $value)->get();
            if (!$this->cities->contains('id', $this->city_id)) {
                $this->city_id = null;
            }
        } else {
            $this->cities = [];
            $this->city_id = null;
        }
    }

    public function updatedAvatar()
    {
        $this->validateOnly('avatar');
        $this->avatarPreview = $this->avatar->temporaryUrl();
    }

    public function save()
    {
        $this->validate();

        // Update user
        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Update lawyer
        $data = [
            'phone' => $this->phone,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'description' => $this->description,
            'attorneys_license' => $this->attorneys_license,
        ];

        // Handle avatar upload
        if ($this->avatar) {
            // Delete old avatar if exists
            if ($this->lawyer->avatar) {
                Storage::delete($this->lawyer->avatar);
            }

            $path = $this->avatar->store('lawyers/avatars', 'public');
            $data['avatar'] = $path;
        }

        $this->lawyer->update($data);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'پروفایل با موفقیت به‌روزرسانی شد.'
        ]);

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.lawyer.profile')
            ->layout('components.layouts.lawyer', [
                'title' => 'پروفایل'
            ]);
    }
}
