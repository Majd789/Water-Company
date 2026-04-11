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
    Schema::create('leaves', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        $table->foreignId('leave_type_id')->constrained('leave_types');
        // ربط العملية بمراقب الدوام (User) الذي قام بالإدخال
        $table->foreignId('created_by')->constrained('users');
        $table->date('start_date'); // تاريخ البداية
        $table->date('end_date');   // تاريخ النهاية
        $table->integer('duration'); // عدد الأيام المحسوبة
        $table->text('reason')->nullable(); // سبب أو ملاحظات
        $table->string('attachment_path')->nullable(); // في حال وجود صورة لتقرير طبي أو طلب ورقي
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
