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
        Schema::create('water_wells2', function (Blueprint $table) {
            $table->id();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('date')->nullable(); 
            $table->string('إسم المُشغل المناوب في المنهل')->nullable(); 
            $table->string('وحدة المياه')->nullable(); 
            $table->string('البلدة')->nullable(); 
            $table->string('المحطات')->nullable(); 
            $table->string('station_code'); // كود المحطة
            $table->string('well_name'); // اسم المنهل
            $table->string('الوضع التشغيلي')->nullable(); 
            $table->string('سبب التوقف')->nullable(); 
            $table->enum('has_flow_meter', ['نعم', 'لا']); // هل يوجد عداد غزارة على المنهل
            $table->integer('flow_meter_start')->nullable(); // رقم بداية عداد الغزارة
            $table->integer('flow_meter_end')->nullable(); // رقم نهاية عداد الغزارة
            $table->float('water_sold_quantity'); // كمية المياه المباعة
            $table->float('water_price'); // سعر المتر
            $table->float('total_amount'); // المبلغ من المياه المباعة
            $table->float('المبلغ ( $ )من المياه المباعة على المنهل')->nullable();
            $table->enum('has_vehicle_filling', ['نعم', 'لا']); // هل يوجد تعبئة ماء لأليات المؤسسة
            $table->float('vehicle_filling_quantity')->nullable(); // كمية المياه التي تم تعبئتها لأليات المؤسسة
            $table->enum('has_free_filling', ['نعم', 'لا']); // هل يوجد تعبئة ماء مجانية
            $table->float('free_filling_quantity')->nullable(); // كمية المياه المجانية التي تم تعبئتها
            $table->string('entity_for_free_filling')->nullable(); // اسم الجهة التي تم تعبئة الماء المجاني لها
            $table->string('document_number')->nullable(); // رقم الكتاب
            $table->text('notes')->nullable(); // ملاحظات مدخل البيانات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_wells2');
    }
};
