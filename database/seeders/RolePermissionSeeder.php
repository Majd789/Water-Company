<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
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

        // الموديلات التي سنولد لها صلاحيات قياسية (view, create, edit, delete)
        $models = [
            'users' => 'المستخدمين',
            'roles' => 'الأدوار',
            'invoices' => 'الفواتير',
            'units' => 'الوحدات',
            'towns' => 'البلدات',
            'stations' => 'المحطات',
            'wells' => 'الآبار',
            'station_reports' => 'تقارير المحطات',
            'manhole_reports' => 'تقارير المناهل',
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
            'projects' => 'المشاريع',
            'project_activities' => 'أنشطة المشاريع',
            // 'unit_stats' will be handled separately to create custom permissions
        ];

        // --- 1. إنشاء الصلاحيات القياسية ---
        $permissions = [];
        foreach ($models as $key => $displayGroup) {
            $permissions[] = ['name' => $key . '.view', 'group' => $key, 'display_name' => "عرض $displayGroup"];
            $permissions[] = ['name' => $key . '.create', 'group' => $key, 'display_name' => "إنشاء $displayGroup"];
            $permissions[] = ['name' => $key . '.edit', 'group' => $key, 'display_name' => "تعديل $displayGroup"];
            $permissions[] = ['name' => $key . '.delete', 'group' => $key, 'display_name' => "حذف $displayGroup"];
        }

        // --- 2. إضافة الصلاحيات المخصصة لـ Unit Stats ---
        $permissions[] = ['name' => 'unit_stats.view', 'group' => 'unit_stats', 'display_name' => 'عرض الإحصائيات الشهرية'];
        $permissions[] = ['name' => 'unit_stats.create', 'group' => 'unit_stats', 'display_name' => 'إنشاء الإحصائيات الشهرية'];
        $permissions[] = ['name' => 'unit_stats.delete', 'group' => 'unit_stats', 'display_name' => 'حذف الإحصائيات الشهرية'];
        // الصلاحيات المنفصلة للتعديل
        $permissions[] = ['name' => 'unit_stats.edit_technical', 'group' => 'unit_stats', 'display_name' => 'تعديل البيانات التقنية (مياه)'];
        $permissions[] = ['name' => 'unit_stats.edit_subscribers', 'group' => 'unit_stats', 'display_name' => 'تعديل بيانات المشتركين'];

        // --- 3. إدخال كل الصلاحيات في قاعدة البيانات ---
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['group' => $permission['group'], 'display_name' => $permission['display_name']]
            );
        }

        // ===================================================================
        // إنشاء الأدوار وتعيين الصلاحيات
        // ===================================================================

        // --- 4. دور مدير النظام (Admin) - يمتلك كل الصلاحيات ---
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'مدير النظام', 'description' => 'لديه جميع الصلاحيات']
        );
        $adminRole->syncPermissions(Permission::all());

        // --- 5. دور مدير الوحدة (AdminUnit) - مسؤول عن البيانات التقنية ---
        $adminUnitRole = Role::firstOrCreate(
            ['name' => 'AdminUnit'],
            ['display_name' => 'مدير الوحدة', 'description' => 'مسؤول عن البيانات التقنية للمحطات والوحدات']
        );
        $adminUnitRole->syncPermissions([
            // صلاحيات أساسية لعرض البيانات
            'units.view',
            'towns.view',
            'stations.view',
            // صلاحيات الإحصائيات الشهرية الخاصة به
            'unit_stats.view',
            'unit_stats.create',
            'unit_stats.edit_technical', // الصلاحية الرئيسية
        ]);

        // --- 6. دور قسم المشتركين (subscribers) - مسؤول عن بيانات المشتركين والمالية ---
        $subscribersRole = Role::firstOrCreate(
            ['name' => 'subscribers'],
            ['display_name' => 'قسم المشتركين', 'description' => 'مسؤول عن بيانات المشتركين والمالية']
        );
        $subscribersRole->syncPermissions([
            // صلاحيات أساسية لعرض البيانات
            'units.view',
            // صلاحيات الإحصائيات الشهرية الخاصة به
            'unit_stats.view',
            'unit_stats.create',
            'unit_stats.edit_subscribers', // الصلاحية الرئيسية
        ]);

        // --- 7. دور المحاسب (accountant) - كمثال إضافي ---
        $accountantRole = Role::firstOrCreate(
            ['name' => 'accountant'],
            ['display_name' => 'محاسب', 'description' => 'مسؤول عن الفواتير والحسابات']
        );
        $accountantRole->syncPermissions([
            'invoices.view', 'invoices.create', 'invoices.edit'
        ]);
    }
}
