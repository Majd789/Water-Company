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
       Schema::create('lab_report_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_report_id')->constrained('lab_reports')->onDelete('cascade');
            $table->enum('need_type', ['lab_material', 'maintenance']); // نوع الاحتياج
            $table->text('description'); // وصف الاحتياج (اسم المادة أو تفاصيل الصيانة)
            $table->string('maintenance_type')->nullable(); // نوع الصيانة (فقط إذا كان النوع صيانة)
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_report_needs');
    }
};
