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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // كود المحطة
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->onDelete('cascade')->onUpdate('cascade'); // نوع الصيانة
            $table->integer('total_quantity'); // العدد الإجمالي للقطع المستبدلة
            $table->string('execution_sites'); // مواقع التنفيذ
            $table->decimal('total_cost', 10, 2); // الكلفة الإجمالية بالدولار
            $table->date('maintenance_date'); // تاريخ الصيانة
            $table->text('maintenance_details')->nullable(); // تفاصيل الصيانة
            $table->string('contractor_name')->nullable(); // الشركة المنفذة
            $table->string('technician_name')->nullable(); // الفني المسؤول
            $table->enum('status', ['تمت', 'قيد التنفيذ', 'فشلت'])->default('قيد التنفيذ'); // حالة الصيانة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
