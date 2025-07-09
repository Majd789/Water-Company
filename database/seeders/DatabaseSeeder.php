<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // الخطوة 1: تشغيل seeder الأدوار والصلاحيات أولاً
        // هذا سيقوم بإنشاء دور 'admin' وجميع الصلاحيات.
        $this->call(RolePermissionSeeder::class);

        // الخطوة 2: إنشاء المستخدم المدير وتعيين الدور له
        // ابحث عن المستخدم إذا كان موجوداً، أو قم بإنشائه
        $user = User::firstOrCreate(
            ['email' => 'majdadmin@gmail.com'], // ابحث بهذا البريد
            [
                'name' => 'Majd Admin',
                'password' => Hash::make('12345'),
                // لا تضع role_id هنا
            ]
        );

        // الخطوة 3: تعيين دور 'admin' للمستخدم
        // هذه هي الطريقة الصحيحة باستخدام Spatie
        $user->assignRole('admin');
    }
}