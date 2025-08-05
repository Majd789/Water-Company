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
        Schema::create('lab_report_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_report_id')->constrained('lab_reports')->onDelete('cascade');
            $table->enum('recommendation_type', ['lab_materials', 'equipment_support', 'staff_training', 'team_coordination', 'other']);
            $table->text('details')->nullable(); // تفاصيل التوصية (مثل نوع التدريب أو الاقتراح)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_report_recommendations');
    }
};
