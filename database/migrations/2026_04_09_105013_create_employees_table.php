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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('full_name'); // اسم الموظف
        $table->string('employee_code')->unique(); // الرقم الوظيفي أو الكود
        // الربط مع جدول الوحدات الموجود لديك مسبقاً
        $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
        $table->integer('total_allowed_days')->default(30); // الرصيد السنوي المتاح
        $table->integer('remaining_days')->default(30); // الرصيد المتبقي الحالي
        $table->boolean('is_active')->default(true); // حالة الموظف (على رأس عمله أم لا)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
