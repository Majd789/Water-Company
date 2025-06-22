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
        Schema::create('elevated_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->string('tank_name');
            $table->string('building_entity'); // الجهة المنشئة
            $table->enum('construction_date', ['جديد', 'قديم'])->default('جديد'); // تاريخ البناء
            $table->float('capacity'); // سعة الخزان
            $table->decimal('readiness_percentage', 5, 2); // نسبة الجاهزية
            $table->float('height'); // ارتفاع الخزان
            $table->string('tank_shape'); // شكل الخزان
            $table->string('feeding_station'); // المحطة التي تعبئه
            $table->string('town_supply'); // البلدة التي تشرب منه
            $table->float('in_pipe_diameter'); // قطر البوري
            $table->float('out_pipe_diameter'); // قطر البوري
            $table->decimal('latitude', 10, 6)->nullable(); // موقع الخزان (latitude)
            $table->decimal('longitude', 10, 6)->nullable(); // موقع الخزان (longitude)
            $table->decimal('altitude', 10, 2)->nullable(); // موقع الخزان (altitude)
            $table->decimal('precision', 5, 2)->nullable(); // دقة الموقع
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elevated_tanks');
    }
};
