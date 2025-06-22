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
        Schema::create('horizontal_pumps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->enum('pump_status', ['يعمل', 'متوقفة'])->nullable(); // الوضع التشغيلي للمضخة
            $table->string('pump_name')->nullable(); // اسم المضخة
            $table->float('pump_capacity_hp'); // استطاعة المضخة (حصان)
            $table->float('pump_flow_rate_m3h'); // تدفق المضخة (متر مكعب/ساعة)
            $table->float('pump_head'); // ارتفاع الضخ
            $table->string('pump_brand_model')->nullable(); // ماركة وطراز المضخة
            $table->string('technical_condition')->nullable(); // الحالة الفنية
            $table->string('energy_source')->nullable(); // مصدر الطاقة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps(); // Created at & Updated at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horizontal_pumps');
    }
};
