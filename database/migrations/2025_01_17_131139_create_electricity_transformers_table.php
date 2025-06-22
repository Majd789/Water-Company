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
        Schema::create('electricity_transformers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->enum('operational_status', ['تعمل', 'متوقفة'])->default('تعمل'); // الوضع التشغيلي للمحولة
            $table->float('transformer_capacity'); // استطاعة المحولة
            $table->float('distance_from_station'); // بعد المحولة عن المحطة
            $table->boolean('is_station_transformer')->default(false); // هل المحولة خاصة بالمحطة
            $table->text('talk_about_station_transformer')->nullable(); // تحدث سردا
            $table->boolean('is_capacity_sufficient')->default(true); // هل الاستطاعة كافية 
            $table->float('how_mush_capacity_need'); // كم الاستطاعة المحتاجة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electricity_transformers');
    }
};
