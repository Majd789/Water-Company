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
        Schema::create('manholes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // Foreign Key to Units
            $table->foreignId('town_id')->constrained()->onDelete('cascade'); // Foreign Key to Towns
            $table->string('manhole_name'); // اسم المنهل
            $table->enum('status', ['يعمل', 'متوقف']); // هل يعمل أو متوقف
            $table->string('stop_reason')->nullable(); // سبب التوقف
            $table->boolean('has_flow_meter')->default(false); // هل يوجد عداد غزارة
            $table->string('chassis_number')->nullable(); // رقم الشاسيه
            $table->float('meter_diameter')->nullable(); // قطر العداد
            $table->enum('meter_status', ['يعمل', 'متوقف'])->nullable(); // هل يعمل أو متوقف
            $table->string('meter_operation_method_in_meter')->nullable(); // طريقة عمل العداد بالمتر
            $table->boolean('has_storage_tank')->default(false); // هل يوجد خزان تجميعي
            $table->float('tank_capacity')->nullable(); // سعة الخزان
            $table->text('general_notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manholes');
    }
};
