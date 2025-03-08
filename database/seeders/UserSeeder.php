<?php

namespace Database\Seeders;

use App\Models\User; // وارد کردن مدل User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // برای هش کردن رمز عبور

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ایجاد کاربر پیش‌فرض
        User::create([
            'n_code' => '4411015056', // یا هر کد ملی مورد نظر
            'password' => Hash::make('12345678'), // رمز عبور را هش کنید (حتماً!)
            // سایر فیلدهای مورد نیاز در جدول users
        ]);
    }
}
