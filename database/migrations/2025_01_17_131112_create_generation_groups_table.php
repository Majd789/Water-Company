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
        Schema::create('generation_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // كود المحطة
            $table->enum('operational_status', ['عاملة', 'متوقفة'])->default('عاملة'); // الوضع التشغيلي
            $table->string('generator_name'); // اسم المولدة
            $table->float('generation_capacity'); // استطاعة التوليد (KVA)
            $table->float('actual_operating_capacity'); // استطاعة العمل الفعلية
            $table->decimal('generation_group_readiness_percentage', 5, 2)->nullable(); // نسبة الجاهزية
            $table->float('fuel_consumption'); // استهلاك الوقود (لتر/ساعة)
            $table->integer('oil_usage_duration'); // مدة استخدام الزيت
            $table->float('oil_quantity_for_replacement'); // كمية الزيت في التبديل
            $table->text('notes')->nullable(); // ملاحظات
            $table->string('stop_reason')->nullable(); // سبب التوقف
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generation_groups');
    }
};
