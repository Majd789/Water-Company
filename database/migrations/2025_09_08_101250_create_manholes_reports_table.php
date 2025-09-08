<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\StationOperationStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manholes_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnDelete(); // رقم الوحدة
            $table->foreignId('station_id')->nullable()->constrained('stations')->cascadeOnDelete();
            $table->foreignId('manhole_id')->nullable()->constrained('manholes')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->date('report_date')->nullable();
            $table->enum('status', allowed: StationOperationStatus::getValues())->nullable();
            $table->text('stop_reason')->nullable(); // سبب التوقف (في حال كانت متوقفة)
            $table->boolean('has_flow_meter')->default(false); // هل يوجد عداد غزارة
            $table->decimal('flow_meter_counter_number_before', 10, 2)->nullable()->default(0);
            $table->decimal('flow_meter_counter_number_after', 10, 2)->nullable()->default(0);
            $table->decimal('water_flow_m3', 10, 2)->nullable()->default(0); // كمية المياه المارة بالمفتاح
            $table->decimal('water_m3_price', 10, 2)->nullable()->default(0); // سعر المتر المكعب الواحد من المياه
            $table->decimal('total_water_price', 10, 2)->nullable()->default(0);
            $table->boolean('has_water_refill_for_tankers')->default(false); //تعبئة مياه للصهاريج الخاصة بالمؤسسة
            $table->decimal('water_refill_for_tankers_m3', 10, 2)->nullable()->default(0);//كمية المياه المعبئة للصهاريج
            $table->boolean('has_free_water_distribution')->default(false); //هل يتم توزيع مياه مجانية للمواطنين
            $table->decimal('free_water_distribution_m3', 10, 2)->nullable()->default(0);  //كمية المياه الموزعة للمواطنين
            $table->text('book_number')->nullable(); //رقم الكتاب
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manholes_reports');
    }
};
