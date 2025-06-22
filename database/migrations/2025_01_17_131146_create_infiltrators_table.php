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
        Schema::create('infiltrators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->float('infiltrator_capacity'); // استطاعة الانفلتر
            $table->float('readiness_status'); // جاهزية الانفلتر
            $table->string('infiltrator_type'); // نوع الانفلتر
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infiltrators');
    }
};
