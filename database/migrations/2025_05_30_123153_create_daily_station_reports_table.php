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
        Schema::create('daily_station_reports', function (Blueprint $table) {
            $table->id(); // المفتاح الأساسي

            // معلومات التقرير الأساسية
            $table->date('report_date'); // التاريخ
            $table->time('report_time')->nullable(); // التوقيت (إذا كان مختلفًا عن وقت إنشاء السجل)

            // العلاقات مع الجداول الأخرى
            $table->foreignId('operator_id')->nullable()->comment('إسم المُشغل المناوب')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('unit_id')->nullable()->comment('وحدة المياه')->constrained('units')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('town_id')->nullable()->comment('البلدة')->constrained('towns')->onDelete('set null')->onUpdate('cascade'); // يمكن أن تكون البلدة مرتبطة بالقطاع أو المحطة مباشرة
            $table->foreignId('station_id')->comment('المحطة')->constrained('stations')->onDelete('cascade')->onUpdate('cascade');

            // معلومات المحطة (قد تكون نسخة وقت التقرير أو معلومات إضافية)
            $table->string('station_code_snapshot')->nullable()->comment('كود المحطة (نسخة وقت التقرير)');
            $table->enum('daily_operational_status', ['عاملة', 'متوقفة', 'خارج الخدمة'])->default('عاملة')->comment('الوضع التشغيلي اليومي');
            $table->string('daily_stop_reason')->nullable()->comment('سبب التوقف اليومي');
            $table->enum('daily_operator_entity', ['تشغيل تشاركي', 'المؤسسة العامة لمياه الشرب'])->nullable()->comment('الجهة المشغلة اليومية');
            $table->string('daily_operator_entity_name')->nullable()->comment('اسم الجهة المشغلة اليومية');

            // معلومات تشغيل الآبار
            $table->integer('active_wells_during_pumping_count')->nullable()->comment('عدد الآبار المُشغَلة أثناء ساعات الضخ');
            $table->decimal('well_1_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر الأول');
            $table->decimal('well_2_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر الثاني');
            $table->decimal('well_3_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر الثالث');
            $table->decimal('well_4_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر الرابع');
            $table->decimal('well_5_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر الخامس');
            $table->decimal('well_6_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر السادس');
            $table->decimal('well_7_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل البئر السابع');
            $table->decimal('total_station_pumping_hours', 5, 2)->nullable()->comment('إجمالي عدد ساعات التشغيل/الضخ للمحطة');

            // المضخة الأفقية
            $table->boolean('has_horizontal_pump')->default(false)->comment('هل يوجد مضخة أفقية');
            $table->decimal('horizontal_pump_operating_hours', 5, 2)->nullable()->comment('عدد ساعات تشغيل المضخة الأفقية');

            // معلومات التشغيل والضخ
            $table->string('station_operation_method_notes')->nullable()->comment('طريقة عمل المحطة');
            // -- تعديل هنا --
            $table->foreignId('pumping_sector_id')->nullable()->comment('القطاع المستهدف ضمن جدول الضخ (FK إلى pumping_sectors)')->constrained('pumping_sectors')->onDelete('set null')->onUpdate('cascade');

            // التعقيم
            $table->boolean('daily_has_disinfection')->default(false)->comment('هل يوجد تعقيم اليوم');
            $table->string('daily_no_disinfection_reason')->nullable()->comment('سبب عدم وجود تعقيم اليوم');

            // مصدر الطاقة وساعات التشغيل
            $table->string('daily_energy_source')->nullable()->comment('مصدر الطاقة التشغيلية اليومي');
            $table->decimal('hours_electric_solar_blend', 5, 2)->nullable()->comment('عدد ساعات (دمج) كهرباء وطاقة شمسية');
            $table->decimal('hours_generator_solar_blend', 5, 2)->nullable()->comment('عدد ساعات (دمج) مولدة وطاقة شمسية');
            $table->decimal('hours_on_solar', 5, 2)->nullable()->comment('عدد ساعات التشغيل على الطاقة الشمسية');
            $table->decimal('hours_on_electricity', 5, 2)->nullable()->comment('عدد ساعات التشغيل على الكهرباء');
            $table->decimal('hours_on_generator', 5, 2)->nullable()->comment('عدد ساعات التشغيل على المولدة');

            // استهلاك الكهرباء
            $table->decimal('electricity_consumed_kwh', 10, 2)->nullable()->comment('كمية الكهرباء المُستهلكة (كيلوواط/ساعة)');
            $table->string('electric_meter_reading_start')->nullable()->comment('رقم عداد ساعة الكهرباء قبل التشغيل');
            $table->string('electric_meter_reading_end')->nullable()->comment('رقم عداد ساعة الكهرباء بعد الانتهاء من التشغيل');

            // استهلاك الديزل وصيانة المولدة
            $table->decimal('diesel_consumed_liters_during_operation', 10, 2)->nullable()->comment('كمية الديزل المُستهلكة خلال ساعات التشغيل (لتر)');
            $table->boolean('generator_oil_changed')->default(false)->comment('هل يوجد استبدال زيت للمولدة');
            $table->decimal('oil_added_to_generator_liters', 8, 2)->nullable()->comment('كمية الزيت المضافة للمولدة (لتر)');

            // كميات المياه والديزل
            $table->decimal('water_pumped_to_network_m3', 10, 2)->nullable()->comment('كمية المياه التي تم ضخها على الشبكة (متر مكعب)');
            $table->decimal('diesel_in_station_total_liters', 10, 2)->nullable()->comment('كامل كمية الديزل الموجودة في المحطة (لتر)');
            $table->boolean('new_diesel_shipment_received')->default(false)->comment('هل يوجد استلام لكمية ديزل جديدة');
            $table->decimal('new_diesel_shipment_quantity_liters', 10, 2)->nullable()->comment('الكمية الجديدة المستلمة من الديزل (لتر)');
            $table->string('diesel_shipment_supplier')->nullable()->comment('الجهة المُسلِمة للديزل');

            // تعديلات التجهيزات
            $table->boolean('station_equipment_modified_today')->default(false)->comment('هل تم التعديل على تجهيزات المحطة اليوم');
            $table->string('equipment_modification_location_type')->nullable()->comment('موقع التغيير ونوعه في التجهيزات');
            $table->text('equipment_modification_description_reason')->nullable()->comment('أسرد طبيعة التغيير وسببه في التجهيزات');
            $table->string('equipment_transferred_to_entity')->nullable()->comment('الجهة التي تم نقل التجهيزات إليها');

            // شحن عداد الكهرباء
            $table->boolean('electricity_meter_recharged_today')->default(false)->comment('هل تم شحن عداد الكهرباء اليوم');
            $table->decimal('electricity_recharged_amount_kwh', 10, 2)->nullable()->comment('كمية الكهرباء المشحونة (كيلوواط/ساعة)');

            // ملاحظات إضافية
            $table->text('shift_operator_notes')->nullable()->comment('ملاحظات المُشغل المناوب في المحطة');

            $table->timestamps(); // حقول created_at و updated_at

            // الفهارس لتحسين أداء الاستعلامات
            $table->index('report_date');
            $table->index(['station_id', 'report_date']);
            $table->index('pumping_sector_id'); // إضافة فهرس للمفتاح الخارجي الجديد
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_station_reports');
    }
};