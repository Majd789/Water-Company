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
        Schema::table('stations_reports', function (Blueprint $table) {
         $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
         $table->boolean('is_checked')->nullable()->default(false);
         $table->foreignId('checked_by')->nullable()->constrained('users')->cascadeOnDelete();
         $table->boolean('is_archived')->nullable()->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('stations_reports', function (Blueprint $table) {
            $table->dropColumn('updated_by','is_checked','checked_by','is_archived');
        });
    }
};
