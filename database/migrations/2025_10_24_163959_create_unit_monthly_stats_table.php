<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_monthly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->year('year');
            $table->tinyInteger('month');

            // === قسم إحصائيات المياه ===
            $table->decimal('produced_water_m3', 15, 2)->default(0)->comment('إجمالي كمية المياه المنتجة في الوحدة (م³)');
            $table->decimal('lost_water_m3', 15, 2)->default(0)->comment('إجمالي كمية الهدر في الوحدة (م³)');
            $table->decimal('distributed_water_m3', 15, 2)->default(0)->comment('كمية المياه التي تم ضخها للمستفيدين مباشرة (م³)');

            // === قسم إحصائيات المشتركين ===
            // -- أعداد المشتركين --
            $table->integer('total_subscribers')->default(0)->comment('عدد المشتركين الكلي');
            $table->integer('metered_subscribers')->default(0)->comment('عدد المشتركين بجباية على العداد');
            $table->integer('flat_rate_subscribers')->default(0)->comment('عدد المشتركين بجباية مقطوعة');
            $table->integer('active_subscribers')->default(0)->comment('عدد الفعالين');
            $table->integer('departed_subscribers')->default(0)->comment('عدد المغادرين');
            $table->integer('canceled_subscribers')->default(0)->comment('عدد الملغى خطوطهم');
            $table->integer('disconnected_subscribers')->default(0)->comment('عدد المقطوع خطوطهم');

            // -- شرائح المشتركين (أعداد ومتخلفين) --
            $table->integer('housing_project_subscribers')->default(0)->comment('عدد مشتركي بناء إسكان');
            $table->integer('housing_project_defaulters')->default(0)->comment('عدد متخلفي بناء إسكان');
            $table->integer('gov_building_subscribers')->default(0)->comment('عدد مشتركي الأبنية الحكومية');
            $table->integer('gov_building_defaulters')->default(0)->comment('عدد متخلفي الأبنية الحكومية');
            $table->integer('owned_property_subscribers')->default(0)->comment('عدد مشتركي بناء ملكية');
            $table->integer('owned_property_defaulters')->default(0)->comment('عدد متخلفي بناء ملكية');
            $table->integer('rented_property_subscribers')->default(0)->comment('عدد مشتركي بناء مستأجر');
            $table->integer('rented_property_defaulters')->default(0)->comment('عدد متخلفي بناء مستأجر');
            $table->integer('domestic_subscription_subscribers')->default(0)->comment('عدد مشتركي اشتراك منزلي');
            $table->integer('domestic_subscription_defaulters')->default(0)->comment('عدد متخلفي اشتراك منزلي');
            $table->integer('commercial_subscription_subscribers')->default(0)->comment('عدد مشتركي اشتراك تجاري');
            $table->integer('commercial_subscription_defaulters')->default(0)->comment('عدد متخلفي اشتراك تجاري');

            // === قسم البيانات المالية ===
            $table->integer('total_paid_count')->default(0)->comment('عدد المسددين الإجمالي');
            $table->decimal('total_paid_amount', 15, 2)->default(0.00)->comment('قيمة التسديد');
            $table->integer('total_defaulters_count')->default(0)->comment('عدد المتخلفين الإجمالي');
            $table->decimal('total_defaulters_amount', 15, 2)->default(0.00)->comment('قيمة التخلف');
            $table->integer('exempted_count')->default(0)->comment('عدد المعفيين');
            $table->decimal('exempted_amount', 15, 2)->default(0.00)->comment('القيمة المعفاة');

            // === حقول عامة ===
            $table->text('notes')->nullable();
            $table->timestamps();

            // -- مفتاح فريد لمنع تكرار الإدخال لنفس الوحدة في نفس الشهر --
            $table->unique(['unit_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_monthly_stats');
    }
};
