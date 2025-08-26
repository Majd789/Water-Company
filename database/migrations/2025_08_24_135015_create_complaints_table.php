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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            
            // الربط بالجداول الأخرى
            $table->foreignId('complaint_type_id')->constrained('complaint_types')->comment('نوع الشكوى');
            $table->foreignId('town_id')->constrained('towns')->comment('البلدة');

            // بيانات الشكوى
            $table->string('complainant_name')->comment('اسم المشتكي');
            $table->string('building_code')->nullable()->comment('رمز البناء');
            $table->text('details')->comment('تفاصيل الشكوى');
            
            $table->enum('location_type', ['inside', 'outside'])->comment('موقع الشكوى: داخل المنزل أو خارجه');
            $table->boolean('is_repeated')->default(false)->comment('هل هي شكوى مكررة؟');
            $table->string('image_path')->nullable()->comment('مسار الصورة الإثباتية');
            
            // حالة متابعة الشكوى
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new')->comment('حالة الشكوى');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
