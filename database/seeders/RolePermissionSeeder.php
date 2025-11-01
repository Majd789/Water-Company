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
            'units' => 'الوحدات',
            'towns' => 'البلدات',
            'stations' => 'المحطات',
            'wells' => 'الآبار',
            'station_reports' => 'تقارير المحطات',
            'manhole_reports' => 'تقارير المناهل',
            'solar_energies' => 'الطاقة الشمسية',
            'pumping_sectors' => 'قطاعات الضخ',
            'notes' => 'الملاحظات',
            'manholes' => 'المناهل',
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
            'water_quality_tests' => 'اختبارات جودة المياه',
            'unit-stats' => 'الإحصائيات الشهرية للوحدات',
            'well_licenses' => 'تراخيص الآبار',
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
            'wells.view',
            'station_reports.view',
            'manhole_reports.view',
            'solar_energies.view',
            'pumping_sectors.view',
            'notes.view',
            'manholes.view',
            'infiltrators.view',
            'horizontal_pumps.view',
            'ground_tanks.view',
            'governorates.view',
            'generation_groups.view',
            'filters.view',
            'elevated_tanks.view',
            'electricity_transformers.view',
            'electricity_hours.view',
            'disinfection_pumps.view',
            'diesel_tanks.view',
            'daily_station_reports.view',
            'maintenance_tasks.view',
            'projects.view',
            'project_activities.view',
            'water_quality_tests.view',

            'units.create',
            'towns.create',
            'stations.create',
            'wells.create',
            'station_reports.create',
            'manhole_reports.create',
            'solar_energies.create',
            'pumping_sectors.create',
            'notes.create',
            'manholes.create',
            'infiltrators.create',
            'horizontal_pumps.create',
            'ground_tanks.create',
            'governorates.create',
            'generation_groups.create',
            'filters.create',
            'elevated_tanks.create',
            'electricity_transformers.create',
            'electricity_hours.create',
            'disinfection_pumps.create',
            'diesel_tanks.create',
            'daily_station_reports.create',
            'maintenance_tasks.create',
            'projects.create',
            'project_activities.create',
            'water_quality_tests.create',

            // صلاحيات التعديل
            'units.edit',
            'towns.edit',
            'stations.edit',
            'wells.edit',
            'station_reports.edit',
            'manhole_reports.edit',
            'solar_energies.edit',
            'pumping_sectors.edit',
            'notes.edit',
            'manholes.edit',
            'infiltrators.edit',
            'horizontal_pumps.edit',
            'ground_tanks.edit',
            'governorates.edit',
            'generation_groups.edit',
            'filters.edit',
            'elevated_tanks.edit',
            'electricity_transformers.edit',
            'electricity_hours.edit',
            'disinfection_pumps.edit',
            'diesel_tanks.edit',
            'daily_station_reports.edit',
            'maintenance_tasks.edit',
            'projects.edit',
            'project_activities.edit',
            'water_quality_tests.edit',
            // حذف البيانات
            'units.delete',
            'towns.delete',
            'stations.delete',
            'wells.delete',
            'station_reports.delete',
            'manhole_reports.delete',
            'solar_energies.delete',
            'pumping_sectors.delete',
            'notes.delete',
            'manholes.delete',
            'infiltrators.delete',
            'horizontal_pumps.delete',
            'ground_tanks.delete',
            'governorates.delete',
            'generation_groups.delete',
            'filters.delete',
            'elevated_tanks.delete',
            'electricity_transformers.delete',
            'electricity_hours.delete',
            'disinfection_pumps.delete',
            'diesel_tanks.delete',
            'daily_station_reports.delete',
            'maintenance_tasks.delete',
            'projects.delete',
            'project_activities.delete',
            'water_quality_tests.delete',

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

        $wellLicensingRole = Role::updateOrCreate(
            ['name' => 'well_licensing_department'], // اسم برمجي للدور
            [
                'display_name' => 'قسم ترخيص الآبار',
                'description' => 'مسؤول عن أرشفة ومتابعة تراخيص الآبار'
            ]
        );
        // إعطاء الدور جميع الصلاحيات المتعلقة بتراخيص الآبار
        $wellLicensingRole->syncPermissions([
            'well_licenses.view',
            'well_licenses.create',
            'well_licenses.edit',
            'well_licenses.delete',
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
