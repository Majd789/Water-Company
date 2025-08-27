<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\StationOperationStatus;
use App\Enum\StationOperatingEntityEum;

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
            $table->string('operating_entity_name')->nullable(); // اسم الجهة المشغلة

            $table->int('number_well')->nullable();// عدد الابار
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
            $table->string('power_source')->nullable(); // مصدر الطاقة الرئيسي
            // ساعات التشغيل لكل مصدر
            $table->decimal('solar_hours', 8, 2)->nullable()->default(0);
            $table->decimal('grid_hours', 8, 2)->nullable()->default(0);
            $table->decimal('generator_hours', 8, 2)->nullable()->default(0);
            // ساعات الدمج
            $table->decimal('solar_grid_hours', 8, 2)->nullable()->default(0);
            $table->decimal('solar_generator_hours', 8, 2)->nullable()->default(0);

            // --- بيانات الاستهلاك ---
            $table->decimal('grid_power_kwh', 10, 2)->nullable()->default(0); // استهلاك الكهرباء
            $table->decimal('diesel_consumed_liters', 10, 2)->nullable()->default(0); // استهلاك الديزل
            $table->text('notes')->nullable(); // ملاحظات عامة
            $table->timestamps(); // تضيف created_at و updated_at

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
