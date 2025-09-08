<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\StationOperationStatus;
use App\Enum\StationOperatingEntityEum;
use App\Enum\EnergyResource;
use App\Enum\OperatingEntityName;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stations_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnDelete(); // رقم الوحدة
            $table->foreignId('station_id')->nullable()->constrained('stations')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->cascadeOnDelete();

            // --- بيانات التقرير الأساسية ---
            $table->date('report_date')->nullable(); // تاريخ التقرير
            $table->enum('status', allowed: StationOperationStatus::getValues())->nullable(); // الوضع التشغيلي
            $table->text('stop_reason')->nullable(); // سبب التوقف (في حال كانت متوقفة)
            $table->enum('operating_entity', StationOperatingEntityEum::getValues())->nullable(); // الجهة المشغلة
            $table->enum('operating_entity_name',OperatingEntityName::getValues())->nullable(); // اسم الجهة المشغلة

            $table->integer('number_well')->nullable();// عدد الابار
            $table->decimal('well1_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل البئر الاول
            $table->decimal('well2_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل الثاني
            $table->decimal('well3_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل الثالث
            $table->decimal('well4_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل الرباع
            $table->decimal('well5_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل الخا مس
            $table->decimal('well6_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل السادس
            $table->decimal('well7_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل السابع
            // --- بيانات التشغيل والضخ ---
            $table->decimal('operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل الكلي
            $table->boolean('is_horizontal_pump')->nullable();
            $table->decimal('horizontal_pump_operating_hours', 8, 2)->nullable()->default(0); // عدد ساعات التشغيل المضخة الافقية

            $table->foreignId('pumping_sector_id')->nullable()->constrained('pumping_sectors')->cascadeOnDelete();
            $table->boolean('is_sterile')->nullable();// يوجد تعقيم

            $table->decimal('water_pumped_m3', 10, 2)->nullable()->default(0); // كمية المياه المضخوخة

            // --- بيانات مصادر الطاقة ---
            $table->enum('power_source', EnergyResource::getValues())->nullable(); // مصدر الطاقة الرئيسي
            // بيانات الكهرباء
            $table->decimal('electricity_hours', 10, 2)->nullable()->default(0);
            $table->decimal('electricity_power_kwh', 10, 2)->nullable()->default(0); // استهلاك الكهرباء
            $table->decimal('electricity_Counter_number_before', 10, 2)->nullable()->default(0);
            $table->decimal('electricity_Counter_number_after', 10, 2)->nullable()->default(0);
            // بيانات الطاقة الشمسية
            $table->decimal('solar_hours', 10, 2)->nullable()->default(0);
            // بيانات المولدة
            $table->decimal('generator_hours', 8, 2)->nullable()->default(0);
            $table->decimal('diesel_consumed_liters', 10, 2)->nullable()->default(0); // استهلاك الديزل

            // ساعات الدمج
            $table->decimal('electricity_solar_hours', 10, 2)->nullable()->default(0);
            $table->decimal('solar_generator_hours', 10, 2)->nullable()->default(0);

            $table->decimal('Total_desil_liters', 10, 2)->nullable()->default(0); //كمية الديزل الموجودة في المحطة
            $table->boolean('is_diesel_received')->nullable();// هل تم استلام  الديزل
            $table->decimal('quantity_of_diesel_received_liters', 10, 2)->nullable()->default(0); // كمية الديزل المخزنة
            $table->string("diesel_source")->nullable();// مصدر الديزل

             $table->boolean('is_there_an_oil_change')->nullable();// هل يوجد استبدال زيت للمولدة
            $table->decimal('quantity_of_oil_added', 10, 2)->nullable(); // كمية الزيت المضافة
            $table->boolean('has_station_been_modified')->nullable();// هل تم التعديل على المحطة
            $table->text('station_modification_type')->nullable();// نوع التعديلات
            $table->text('station_modification_notes')->nullable();// ملاحظات التعديلات

            $table->timestamps(); // تضيف created_at و updated_at

            $table->boolean('is_the_electricity_meter_charged')->nullable();// هل تم شحن العداد الكهربائي
            $table->decimal('quantity_of_electricity_meter_charged_kwh', 10, 2)->nullable()->default(0);// كمية الكهرباء المشحونة

            $table->text('notes')->nullable(); // ملاحظات عامة
            // --- إضافة الفهارس (Indexes) لتحسين أداء الاستعلامات ---
            $table->index('report_date'); // لتسريع البحث والفلترة حسب نطاق زمني
            $table->index('status'); // لتسريع فلترة التقارير حسب الحالة (تعمل / متوقفة)
            $table->index('station_id');
            $table->index('operator_id');
            $table->index('power_source');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations_reports');
    }
};
