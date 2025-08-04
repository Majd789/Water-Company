<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_project_activities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            
            // الربط مع جدول المشاريع الرئيسي (إجباري)
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            // == التعديل الرئيسي هنا: الربط مع الجداول الهيكلية ==
            // هذه الحقول اختيارية (nullable) لأن بعض الأنشطة قد لا ترتبط بموقع محدد (مثل التوريدات للمستودع)
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->foreignId('town_id')->nullable()->constrained('towns')->onDelete('set null');
            $table->foreignId('station_id')->nullable()->constrained('stations')->onDelete('set null');
            
            // تفاصيل النشاط
            $table->decimal('value', 15, 2)->default(0);
            $table->string('activity_name');
            $table->integer('activity_count')->nullable();
            $table->string('activity_unit')->nullable();
            $table->string('activity_quantity')->nullable();
            
            // معلومات التنفيذ
            $table->string('execution_status');
            $table->string('contractor_name')->nullable();
            $table->string('contractor_contact')->nullable();
            $table->date('work_start_date')->nullable();
            $table->integer('work_duration_days')->nullable();
            
            // الكميات المنفذة
            $table->integer('executed_count')->nullable();
            $table->string('executed_unit')->nullable();
            $table->string('executed_quantity')->nullable();
            
            // الاستلام والملاحظات
            $table->string('final_acceptance_status')->nullable();
            $table->date('actual_end_date')->nullable();
            
            // بنود العمل
            $table->string('work_item_activity')->nullable();
            $table->integer('work_item_count')->nullable();
            $table->string('work_item_unit')->nullable();
            $table->string('work_item_quantity')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_activities');
    }
};