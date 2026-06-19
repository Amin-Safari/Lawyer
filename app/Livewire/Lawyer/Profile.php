<?php

namespace App\Livewire\Lawyer;

use App\Models\City;
use App\Models\Lawyer;
use App\Models\Province;
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
        $this->provinces = Province::all();
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $this->lawyer->phone;
        $this->province_id = $this->lawyer->province_id;
        $this->city_id = $this->lawyer->city_id;
        $this->address = $this->lawyer->address;
        $this->description = $this->lawyer->description;
        $this->attorneys_license = $this->lawyer->attorneys_license;

        // بارگذاری شهرها بر اساس استان
        if ($this->province_id) {
            $this->cities = City::where('province_id', $this->province_id)->get();
        }

        // نمایش آواتار
        if ($this->lawyer->avatar && Storage::disk('public')->exists($this->lawyer->avatar)) {
            $this->avatarPreview = Storage::url($this->lawyer->avatar);
        } else {
            $this->avatarPreview = null;
        }
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $this->cities = City::where('province_id', $value)->get();
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

        try {
            // به‌روزرسانی کاربر
            auth()->user()->update([
                'name' => $this->name,
            ]);

            // اطلاعات وکیل
            $data = [
                'phone' => $this->phone,
                'province_id' => $this->province_id,
                'city_id' => $this->city_id,
                'address' => $this->address,
                'description' => $this->description,
                'attorneys_license' => $this->attorneys_license,
            ];

            // آپلود آواتار
            if ($this->avatar) {
                // حذف آواتار قدیمی
                if ($this->lawyer->avatar) {
                    Storage::disk('public')->delete($this->lawyer->avatar);
                }

                $path = $this->avatar->store('lawyers/avatars', 'public');
                $data['avatar'] = $path;
            }

            $this->lawyer->update($data);

            session()->flash('message', 'پروفایل با موفقیت به‌روزرسانی شد.');
            session()->flash('type', 'success');

            $this->loadData();

        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ذخیره اطلاعات: ' . $e->getMessage());
            session()->flash('type', 'error');
        }
    }

    public function getCityName()
    {
        return $this->lawyer->city->name ?? 'ثبت نشده';
    }

    public function render()
    {
        return view('livewire.lawyer.profile')
            ->layout('components.layouts.lawyer', [
                'title' => 'پروفایل'
            ]);
    }
}
