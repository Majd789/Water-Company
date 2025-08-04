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
       Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // معلومات الكتاب المرجعي للمشروع
            $table->string('institution_ref_number')->unique(); // رقم الكتاب الصادر من المؤسسة
            $table->date('institution_ref_date'); // تاريخ الكتاب الصادر من المؤسسة
            $table->string('hac_ref_number')->nullable(); // رقم كتاب مكتب العمل الانساني
            $table->date('hac_ref_date')->nullable(); // تاريخ كتاب مكتب العمل الانساني
            
            // بيانات المشروع الأساسية
            $table->string('name'); // اسم المشروع
            $table->enum('type', ['تقييم احتياج', 'تنفيذ', 'أخرى'])->default('تنفيذ'); // نوع المشروع
            $table->string('organization'); // المنظمة
            $table->string('donor'); // الجهة المانحة
            $table->decimal('total_cost', 15, 2)->default(0); // الكلفة الاجمالية للمشروع
            $table->integer('duration_days'); // المدة / اليوم
            $table->date('start_date'); // تاريخ بداية المشروع
            $table->date('end_date'); // تاريخ نهاية المشروع

            // بيانات إدارية
            $table->string('supervisor_name'); // اسم المشرف
            $table->string('supervisor_contact')->nullable(); // رقم التواصل
            $table->string('status'); // حالة المشروع (كانت حالة المشروع2)
            $table->integer('phases_count')->default(1); // عدد مراحل المشروع
            $table->integer('sites_count')->nullable(); // عدد مواقع العمل ضمن المشروع
            $table->integer('stations_count')->nullable(); // عد المحطات التي سيتم العمل فيها ضمن المشروع
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};