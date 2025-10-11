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
       Schema::create('station_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('maintenance_team_count')->nullable();
            $table->integer('water_quality_team_count')->nullable();
            $table->integer('admin_team_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_teams');
    }
};
