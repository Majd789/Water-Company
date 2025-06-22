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
        Schema::create('institution_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->string('department_name'); // اسم القسم
            $table->string('property_type'); // نوع العقار
            $table->string('property_use'); // عمل العقار
            $table->string('property_nature'); // طبيعة العقار
            $table->decimal('rental_value', 10, 2); // قيمة الإيجار
            $table->text('general_notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_properties');
    }
};
