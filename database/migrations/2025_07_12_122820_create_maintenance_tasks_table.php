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
        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('technician_name');       // اسم الشخص الذي أجرى الصيانة
            $table->date('maintenance_date');             // تاريخ المهمة
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('location');             // مكان العطل
            $table->text('fault_description');      // وصف العطل
            $table->text('fault_cause')->nullable(); // سبب العطل (يمكن أن يكون فارغًا)
            $table->text('maintenance_actions');    // وصف إجراءات الصيانة
            // --- حالة الإصلاح ---
            $table->boolean('is_fixed')->default(false); // هل تم الإصلاح؟ (نعم/لا)
            $table->text('reason_not_fixed')->nullable(); // لماذا لم يتم الإصلاح؟
            // --- التواريخ والملاحظات ---
            $table->text('notes')->nullable();      // ملاحظات إضافية
            $table->timestamps(); // حقول created_at و updated_at
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tasks');
    }
};
