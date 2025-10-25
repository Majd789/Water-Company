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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->morphs('metricable'); // تنشئ الأعمدة metricable_type و metricable_id
            $table->string('metric_key');
            $table->decimal('value', 12, 2);
            $table->string('unit')->nullable();
            $table->timestamp('measured_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};
