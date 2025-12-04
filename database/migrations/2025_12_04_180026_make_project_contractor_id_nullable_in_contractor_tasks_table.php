<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contractor_tasks', function (Blueprint $table) {
            // تعديل العمود ليقبل قيمة فارغة (NULL)
            $table->foreignId('project_contractor_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('contractor_tasks', function (Blueprint $table) {
            $table->foreignId('project_contractor_id')->nullable(false)->change();
        });
    }
};
