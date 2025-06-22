<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_stations_table.php
public function up()
{
    Schema::create('stations', function (Blueprint $table) {
        $table->id(); // Primary Key
        $table->string('station_code')->unique();
        $table->string('station_name');
        $table->enum('operational_status', ['عاملة', 'متوقفة','خارج الخدمة'])->default('عاملة');
        $table->string('stop_reason')->nullable();
        $table->string('energy_source')->nullable();
        $table->enum('operator_entity', ['تشغيل تشاركي', 'المؤسسة العامة لمياه الشرب'])->nullable();
        $table->string('operator_name')->nullable();
        $table->text('general_notes')->nullable();
        $table->foreignId('town_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // Foreign Key to Towns
        $table->string('water_delivery_method')->nullable();
        $table->decimal('network_readiness_percentage', 5, 2)->nullable();
        $table->string('network_type')->nullable();
        $table->integer('beneficiary_families_count')->nullable();
        $table->boolean('has_disinfection')->default(false);
        $table->string('disinfection_reason')->nullable();
        $table->text('served_locations')->nullable();
        $table->decimal('actual_flow_rate', 10, 2)->nullable();
        $table->string('station_type')->nullable();
        $table->text('detailed_address')->nullable();
        $table->decimal('land_area', 10, 2)->nullable();
        $table->string('soil_type')->nullable();
        $table->text('building_notes')->nullable();
        $table->decimal('latitude', 10, 6)->nullable();
        $table->decimal('longitude', 10, 6)->nullable();
        $table->boolean('is_verified')->default(false);
        $table->timestamps(); // Created at & Updated at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
