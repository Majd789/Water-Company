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
       Schema::table('station_teams', function (Blueprint $table) {
            // إضافة العمود الجديد 'contact_number' بعد عمود 'admin_team_count'
            $table->string('contact_number')->nullable()->after('admin_team_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('station_teams', function (Blueprint $table) {
            // حذف العمود عند التراجع عن الـ migration
            $table->dropColumn('contact_number');
        });
    }
};
