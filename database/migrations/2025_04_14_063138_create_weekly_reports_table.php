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
            Schema::create('weekly_reports', function (Blueprint $table) {
                $table->id();

                // 1. الوحدة
                $table->foreignId('unit_id')
                    ->constrained()
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                // 2. تاريخ التقرير
                $table->date('report_date');

                // 3. اسم الشخص المرسِل
                $table->string('sender_name')->nullable();

                // 4. الوضع التشغيلي
                //    (مثلاً: \"تعمل بشكل كامل\" أو \"تعمل باستثناء محطة X\")
                $table->text('operational_status');

                // 5. سبب توقف المحطة
                $table->text('stop_reason')->nullable();

                // 6. أعمال الصيانة (سردي)
                $table->text('maintenance_works')->nullable();

                // 7. اسم الجهة المنفّذة للعمل
                $table->string('maintenance_entity')->nullable();

                // 8. صورة إثباتية لأعمال الصيانة (مسار الملف)
                $table->string('maintenance_image')->nullable();

                // 9. أعمال إدارية (سردي)
                $table->text('administrative_works')->nullable();

                // 10. صورة للأعمال الإدارية (مسار الملف)
                $table->string('administrative_image')->nullable();

                // 11. ملاحظات إضافية
                $table->text('additional_notes')->nullable();

                // تواريخ الإنشاء والتحديث
                $table->timestamps();
            });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
