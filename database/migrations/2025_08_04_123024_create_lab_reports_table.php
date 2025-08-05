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
         Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();

            // --- معلومات العينة الأساسية ---
            $table->string('sample_number')->unique(); // رقم العينة
            $table->integer('samples_count'); // عدد العينات
            $table->date('analysis_date'); // تاريخ التحليل
            $table->string('water_source'); // مصدر المياه (شبكة، خزان، ...)
            
            // --- التحليل الكيميائي ---
            $table->decimal('temperature', 5, 2)->nullable(); // درجة الحرارة
            $table->decimal('turbidity', 8, 2)->nullable(); // العكارة (NTU)
            $table->decimal('ph_level', 4, 2)->nullable(); // الرقم الهيدروجيني (pH)
            $table->decimal('free_chlorine', 8, 2)->nullable(); // الكلور الحر
            $table->decimal('residual_chlorine', 8, 2)->nullable(); // الكلور المتبقي
            $table->decimal('nitrates', 8, 2)->nullable(); // النترات (mg/L)
            $table->decimal('total_hardness', 8, 2)->nullable(); // العسر الكلي
            $table->decimal('chlorides', 8, 2)->nullable(); // الكلوريدات
            $table->decimal('sulfates', 8, 2)->nullable(); // الكبريتات
            $table->decimal('iron', 8, 4)->nullable(); // الحديد
            $table->decimal('manganese', 8, 4)->nullable(); // المنغنيز
            $table->string('chemical_sample_status'); // حالة العينة (مطابقة/غير مطابقة)
            $table->text('chemical_notes')->nullable(); // ملاحظات التحليل الكيميائي

            // --- التحليل الجرثومي ---
            $table->string('total_coliforms')->nullable(); // الكوليفورم الكلية (قد تكون رقم أو نص)
            $table->string('e_coli')->nullable(); // الاشريكية القولونية
            $table->string('total_germ_count')->nullable(); // العدد الجرثومي العام
            $table->string('bacterial_sample_status'); // حالة العينة (مطابقة/غير مطابقة)
            $table->text('bacterial_notes')->nullable(); // ملاحظات التحليل الجرثومي

            // --- الخلاصة النهائية ---
            $table->string('overall_water_quality'); // جودة المياه العامة
            $table->boolean('is_syrian_spec_compliant')->default(true); // مطابقة المواصفة السورية
            $table->boolean('has_bacterial_contamination')->default(false); // وجود تلوث جرثومي
            $table->boolean('has_chemical_contamination')->default(false); // وجود تلوث كيميائي
            $table->boolean('has_serious_contamination')->default(false); // حالات تلوث خطير
            $table->string('reporter_name'); // معد التقرير
            $table->date('report_date'); // تاريخ التقرير

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};
