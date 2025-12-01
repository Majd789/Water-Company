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
        Schema::create('project_activities', function (Blueprint $table) {
        $table->id();
        $table->string('activity_code', 100)->unique();
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        $table->foreignId('master_activity_id')->constrained('master_activities');

        // التعديل هنا: الربط مع القرية بدلاً من الوحدة والمحطة
        $table->foreignId('town_id')->constrained('towns');

        // اسم المحطة أصبح نصياً (حر) بدلاً من ربط بجدول
        $table->string('station_name')->nullable()->comment('اسم المحطة - نص حر');

        $table->decimal('quantity', 10, 2)->nullable();
        $table->string('unit_measure', 50)->nullable()->comment('الواحدة');
        $table->decimal('unit_capacity', 10, 2)->nullable()->comment('كمية/حجم/استطاعة الواحدة');
        $table->decimal('cost', 15, 2)->nullable();
        $table->string('status')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
