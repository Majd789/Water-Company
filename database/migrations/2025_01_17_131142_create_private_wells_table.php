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
        Schema::create('private_wells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->string('well_name'); // اسم البئر الخاص
            $table->integer('well_count'); // عدد الآبار
            $table->float('distance_from_nearest_well'); // بعده عن أقرب بئر
            $table->string('well_type'); // نوع عمل البئر
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_wells');
    }
};
