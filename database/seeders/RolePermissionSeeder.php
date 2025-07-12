<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
       // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //  // إنشاء الصلاحيات
        // $permissions = [
        //     // إدارة المستخدمين
        //     ['name' => 'users.view', 'group' => 'users', 'display_name' => 'عرض المستخدمين'],
        //     ['name' => 'users.create', 'group' => 'users', 'display_name' => 'إنشاء مستخدمين'],
        //     ['name' => 'users.edit', 'group' => 'users', 'display_name' => 'تعديل المستخدمين'],
        //     ['name' => 'users.delete', 'group' => 'users', 'display_name' => 'حذف المستخدمين'],

        //     // إدارة الأدوار
        //     ['name' => 'roles.view', 'group' => 'roles', 'display_name' => 'عرض الأدوار'],
        //     ['name' => 'roles.create', 'group' => 'roles', 'display_name' => 'إنشاء أدوار'],
        //     ['name' => 'roles.edit', 'group' => 'roles', 'display_name' => 'تعديل الأدوار'],
        //     ['name' => 'roles.delete', 'group' => 'roles', 'display_name' => 'حذف الأدوار'],


           // الموديلات التي سنولد لها صلاحيات
        $models = [
            'users' => 'المستخدمين',
            'roles' => 'الأدوار',
            'invoices' => 'الفواتير',
            'units' => 'الوحدات',
            'towns' => 'البلدات',
            'stations' => 'المحطات',
            'station_reports' => 'تقارير المحطات',
            'solar_energies' => 'الطاقة الشمسية',
            'pumping_sectors' => 'قطاعات الضخ',
            'privet_wells' => 'الآبار الخاصة',
            'notes' => 'الملاحظات',
            'manholes' => 'الريغارات',
            'institution_properties' => 'ممتلكات المؤسسة',
            'infiltrators' => 'المتسربين',
            'horizontal_pumps' => 'المضخات الأفقية',
            'ground_tanks' => 'الخزانات الأرضية',
            'governorates' => 'المحافظات',
            'generation_groups' => 'مجموعات التوليد',
            'filters' => 'الفلاتر',
            'elevated_tanks' => 'الخزانات المرتفعة',
            'electricity_transformers' => 'محولات الكهرباء',
            'electricity_hours' => 'ساعات الكهرباء',
            'disinfection_pumps' => 'مضخات التعقيم',
            'diesel_tanks' => 'خزانات المازوت',
            'daily_station_reports' => 'تقارير المحطات اليومية',
            'maintenance_tasks' => 'مهام الصيانة',
        ];

        $permissions = [];

        foreach ($models as $key => $displayGroup) {
            $permissions[] = ['name' => $key . '.view', 'group' => $key, 'display_name' => "عرض $displayGroup"];
            $permissions[] = ['name' => $key . '.create', 'group' => $key, 'display_name' => "إنشاء $displayGroup"];
            $permissions[] = ['name' => $key . '.edit', 'group' => $key, 'display_name' => "تعديل $displayGroup"];
            $permissions[] = ['name' => $key . '.delete', 'group' => $key, 'display_name' => "حذف $displayGroup"];
        }

        // إدخال الصلاحيات
        foreach ($permissions as $permission) {
            if (!\App\Models\Permission::where('name', $permission['name'])->exists()) {
                \App\Models\Permission::create($permission);
            }
        }


        // إنشاء الأدوار وتعيين الصلاحيات
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create([
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'description' => 'لديه جميع الصلاحيات'
            ]);
            $adminRole->givePermissionTo(Permission::all());
        } else {
            $adminRole = Role::where('name', 'admin')->first();
            $adminRole->syncPermissions(Permission::all());
        }

        if (!Role::where('name', 'accountant')->exists()) {
            $accountantRole = Role::create([
                'name' => 'accountant',
                'display_name' => 'محاسب',
                'description' => 'مسؤول عن الفواتير والحسابات'
            ]);
            $accountantRole->givePermissionTo([
                'invoices.view', 'invoices.create', 'invoices.edit'
            ]);
        } else {
            $accountantRole = Role::where('name', 'accountant')->first();
            $accountantRole->syncPermissions([
                'invoices.view', 'invoices.create', 'invoices.edit'
            ]);
        }
    }
}
