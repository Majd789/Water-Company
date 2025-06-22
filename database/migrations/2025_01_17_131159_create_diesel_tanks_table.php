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
        Schema::create('diesel_tanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->string('tank_name'); // اسم الخزان
            $table->float('tank_capacity'); // سعة الخزان
            $table->decimal('readiness_percentage', 5, 2); // نسبة الجاهزية
            $table->string('type'); // نوع الخزان
            $table->text('general_notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diesel_tanks');
    }
};
