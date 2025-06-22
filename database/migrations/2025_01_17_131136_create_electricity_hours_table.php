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
        Schema::create('electricity_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->integer('electricity_hours'); // عدد ساعات الكهرباء
            $table->string('electricity_hour_number'); // رقم ساعة الكهرباء
            $table->string('meter_type'); // نوع العداد
            $table->string('operating_entity'); // الجهة المشغلة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electricity_hours');
    }
};
