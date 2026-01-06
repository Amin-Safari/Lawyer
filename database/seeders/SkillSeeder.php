<?php

namespace Database\Seeders;

use App\Models\Lawyer;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LawyerSeeder extends Seeder
{
    public function run(): void
    {
        // ایجاد چند مهارت نمونه
        $skills = Skill::factory()->count(10)->create();

        // ایجاد کاربران وکلا
        $users = User::factory()->count(5)->create([
            'role' => 'lawyer', // فرض می‌کنیم فیلد role در جدول users دارید
        ]);

        foreach ($users as $user) {
            $lawyer = Lawyer::create([
                'user_id' => $user->id,
                'avatar' => null,
                'description' => $this->fakerPersian()->paragraph(3),
                'province_id' => rand(1, 31), // فرض می‌کنیم 31 استان داریم
                'city_id' => rand(1, 100), // فرض می‌کنیم 100 شهر داریم
                'address' => $this->fakerPersian()->address(),
                'phone' => '09' . rand(100000000, 999999999),
                'attorneys_license' => 'PL-' . rand(10000, 99999),
            ]);

            // اتصال مهارت‌های تصادفی به وکیل
            $randomSkills = $skills->random(rand(2, 5));
            $lawyer->skills()->attach($randomSkills);
        }
    }

    private function fakerPersian()
    {
        $faker = \Faker\Factory::create('fa_IR');
        return $faker;
    }
}
