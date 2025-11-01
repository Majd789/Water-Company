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
        Schema::create('well_licenses', function (Blueprint $table) {
          $table->id();

            // معلومات الكود والأرشفة الأساسية
            $table->string('archive_code')->unique()->comment('الكود المرجعي الفريد للملف');
            $table->string('property_number')->index()->comment('رقم العقار');
            $table->string('property_zone')->comment('المنطقة العقارية');
            $table->string('applicant_name')->comment('اسم مقدم الطلب');
            $table->string('request_type')->comment('نوع الطلب');

            // التواريخ والأرقام المرجعية
            $table->date('institution_letter_date')->nullable()->comment('تاريخ كتاب ديوان المؤسسة');
            $table->string('directorate_letter_number')->nullable()->comment('رقم كتاب مديرية الموارد');
            $table->date('directorate_letter_date')->nullable()->comment('تاريخ كتاب مديرية الموارد');

            // معلومات فنية وجغرافية
            $table->unsignedInteger('declared_distance_meters')->nullable()->comment('المسافة المصرح بها بالمتر');

            // الربط مع جدول المحطات
            $table->foreignId('station_id')
                  ->nullable()
                  ->constrained('stations')
                  ->onDelete('set null')
                  ->comment('المفتاح الخارجي لأقرب محطة');

            $table->decimal('latitude', 10, 7)->nullable()->comment('إحداثيات خط العرض');
            $table->decimal('longitude', 11, 7)->nullable()->comment('إحداثيات خط الطول');

            // معلومات الأرشفة المادية (الموقع الفعلي للملف الورقي)
            $table->string('physical_cabinet')->nullable()->comment('رقم أو اسم الخزانة');
            $table->string('physical_shelf')->nullable()->comment('رقم أو اسم الرف');
            $table->string('physical_file_id')->nullable()->comment('رقم أو كود الملف داخل الرف');

            // حقول إضافية
            $table->text('notes')->nullable()->comment('ملاحظات عامة');
            $table->text('file_url')->nullable()->comment('رابط الملف الممسوح ضوئياً');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('well_licenses');
    }
};
