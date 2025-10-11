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
        Schema::create('safety_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->unique()->constrained()->onDelete('cascade');
            $table->boolean('has_ppe')->default(false);
            $table->text('ppe_types')->nullable();
            $table->boolean('ppe_training_provided')->default(false);
            $table->boolean('has_fire_extinguishers')->default(false);
            $table->boolean('has_evacuation_plan')->default(false);
            $table->boolean('chemical_storage_safe')->default(false);
            $table->boolean('has_warning_signs')->default(false);
            $table->boolean('has_first_aid_kit')->default(false);
            $table->boolean('first_aid_training_provided')->default(false);
            $table->boolean('emergency_numbers_visible')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_profiles');
    }
};
