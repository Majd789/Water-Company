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
        Schema::create('contractor_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code', 100)->unique();
            $table->foreignId('project_activity_id')->constrained('project_activities')->onDelete('cascade');
            $table->foreignId('project_contractor_id')->constrained('project_contractors')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit_measure', 50)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_tasks');
    }
};
