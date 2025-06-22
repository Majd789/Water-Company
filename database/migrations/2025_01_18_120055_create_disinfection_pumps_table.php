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
        Schema::create('disinfection_pumps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
        $table->enum('disinfection_pump_status', ['يعمل', 'متوقف'])->nullable(); // الوضع التشغيلي لمضخة التعقيم
        $table->string('pump_brand_model')->nullable(); // ماركة وطراز المضخة
        $table->float('pump_flow_rate')->nullable(); // غزارة المضخة (لتر/ساعة)
        $table->float('operating_pressure')->nullable(); // ضغط العمل
        $table->string('technical_condition')->nullable(); // الحالة الفنية
        $table->text('notes')->nullable(); // ملاحظات
        $table->timestamps(); // Created at & Updated at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disinfection_pumps');
    }
};
