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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id') // المفتاح الخارجي
                  ->nullable()           // يمكن أن يكون فارغًا إذا لم يتم تعيين وحدة
                  ->constrained('units') // الربط بجدول الوحدات
                  ->onDelete('set null'); // إذا تم حذف الوحدة، يصبح `unit_id` فارغًا
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']); // حذف المفتاح الخارجي
            $table->dropColumn('unit_id');   // حذف العمود
        });
    }    
};
