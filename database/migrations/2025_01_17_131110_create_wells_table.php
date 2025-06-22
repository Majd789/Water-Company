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
        Schema::create('wells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // كود المحطة
            $table->string('town_code'); // كود البلدة
            $table->string('well_name'); // اسم البئر
            $table->enum('well_status', ['يعمل', 'متوقف'])->nullable(); // الوضع التشغيلي للبئر
            $table->string('stop_reason')->nullable(); // سبب التوقف
            $table->float('distance_from_station')->nullable(); // بعده عن المحطة
            $table->enum('well_type', ['جوفي', 'سطحي'])->nullable(); // نوع البئر (جوفي / سطحي)
            $table->float('well_flow')->nullable(); // تدفق البئر (متر مكعب / ساعة)
            $table->float('static_depth')->nullable(); // العمق الستاتيكي
            $table->float('dynamic_depth')->nullable(); // العمق الديناميكي
            $table->float('drilling_depth')->nullable(); // العمق الحفر
            $table->float('well_diameter')->nullable(); // قطر البئر
            $table->float('pump_installation_depth')->nullable(); // عمق تركيب المضخة
            $table->float('pump_capacity')->nullable(); // استطاعة المضخة
            $table->float('actual_pump_flow')->nullable(); // تدفق المضخة الفعلي
            $table->float('pump_lifting')->nullable(); // رفع المضخة
            $table->string('pump_brand_model')->nullable(); // ماركة وموديل المضخة
            $table->string('energy_source')->nullable(); // مصدر الطاقة
            $table->string('well_address')->nullable(); // عنوان البئر
            $table->text('general_notes')->nullable(); // ملاحظات عامة
            $table->string('well_location')->nullable(); // موقع البئر (latitude, longitude, altitude, precision)
            $table->timestamps();
        });
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wells');
    }
};
