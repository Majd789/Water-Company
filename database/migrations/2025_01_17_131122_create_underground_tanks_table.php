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
        Schema::create('ground_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->string('tank_name');
            $table->string('building_entity');
            $table->string('construction_type'); // إضافة نوع البناء (قديم أو جديد)
            $table->float('capacity');
            $table->decimal('readiness_percentage', 5, 2);
            $table->string('feeding_station');
            $table->string('town_supply');
            $table->float('pipe_diameter_inside');
            $table->float('pipe_diameter_outside');
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('precision', 5, 2)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('underground_tanks');
    }
};
