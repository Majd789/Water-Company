<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('station_reports', function (Blueprint $table) {
            $table->id();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('date')->nullable(); 
            $table->string('إسم المُشغل المناوب في المنهل')->nullable(); 
            $table->string('وحدة المياه')->nullable(); 
            $table->string('البلدة')->nullable(); 
            $table->string('المحطات')->nullable(); 
            $table->string('station_code'); // كود المحطة
            $table->string('الوضع التشغيلي')->nullable(); 
            $table->string('سبب التوقف')->nullable(); 
            $table->string('operator_entity'); // الجهة المشغلة
            $table->string('operator_company')->nullable(); // اسم الجهة المشغلة
            $table->double('operating_wells_count')->default(0); // عدد الآبار المشغلة أثناء الضخ
            
            // ساعات تشغيل كل بئر
            for ($i = 1; $i <= 7; $i++) {
                $table->double("well_{$i}_hours")->nullable();
            }

            $table->double('total_well_hours')->nullable(); // عدد ساعات التشغيل الكلي
            $table->boolean('has_horizontal_pump')->default(false)->nullable(); // هل يوجد مضخة أفقية
            $table->double('horizontal_pump_hours')->nullable(); // عدد ساعات تشغيل المضخة الأفقية
            $table->string('station_operation_method')->nullable(); // طريقة عمل المحطة
            $table->string('target_sector')->nullable(); // اسم القطاع المستهدف ضمن جدول الضخ
            $table->boolean('has_disinfection')->default(false)->nullable(); // هل يوجد تعقيم؟
            $table->string('no_disinfection_reason')->nullable(); // سبب عدم وجود تعقيم
            $table->string('energy_source')->nullable(); // مصدر الطاقة التشغيلية
            
            // عدد ساعات التشغيل لكل مصدر طاقة
            $table->double('solar_electricity_hours')->nullable();
            $table->double('solar_generator_hours')->nullable();
            $table->double('solar_only_hours')->nullable();
            $table->double('electricity_hours')->nullable();
            $table->double('electricity_consumption_kwh')->nullable();
            
            // عداد الكهرباء
            $table->double('electric_meter_before')->nullable();
            $table->double('electric_meter_after')->nullable();

            // التشغيل بالمولدة والديزل
            $table->double('generator_hours')->nullable();
            $table->double('diesel_consumption')->nullable();
            $table->boolean('oil_replacement')->default(false)->nullable();
            $table->double('oil_quantity')->nullable();

            // كمية المياه والديزل
            $table->double('water_pumped_m3')->nullable();
            $table->double('total_diesel_stock')->nullable();
            $table->boolean('diesel_received')->default(false)->nullable();
            $table->double('new_diesel_quantity')->nullable();
            $table->string('diesel_provider')->nullable();

            // التعديلات والتجهيزات
            $table->boolean('station_modification')->default(false)->nullable();
            $table->string('modification_location')->nullable();
            $table->text('modification_details')->nullable();
            $table->string('transfer_destination')->nullable();

            // شحن عداد الكهرباء
            $table->boolean('electric_meter_charged')->default(false)->nullable();
            $table->double('charged_electricity_kwh')->nullable();

            $table->text('operator_notes')->nullable(); // ملاحظات المشغل المناوب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_reports');
    }
};
