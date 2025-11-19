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
        Schema::table('contractor_tasks', function (Blueprint $table) {
            // حقل لتحديد ما إذا كانت المهمة غير مطابقة للنشاط الرسمي
            $table->boolean('is_discrepant')->default(false)->after('cost');

            // حقل نصي لشرح سبب عدم التطابق
            $table->text('discrepancy_notes')->nullable()->after('is_discrepant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('contractor_tasks', function (Blueprint $table) {
            $table->dropColumn(['is_discrepant', 'discrepancy_notes']);
        });
    }
};
