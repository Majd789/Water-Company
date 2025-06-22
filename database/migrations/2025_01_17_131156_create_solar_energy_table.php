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
        Schema::create('solar_energies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade'); // Foreign Key to Stations
            $table->float('panel_size'); // قياس اللوح
            $table->integer('panel_count'); // عدد الألواح
            $table->string('manufacturer'); // الجهة المنشئة
            $table->string('base_type'); // نوع القاعدة
            $table->string('technical_condition'); // الحالة الفنية
            $table->integer('wells_supplied_count'); // عدد الآبار المغذاة
            $table->text('general_notes')->nullable(); // ملاحظات
            $table->decimal('latitude', 10, 6)->nullable(); // موقع الطاقة الشمسية (latitude)
            $table->decimal('longitude', 10, 6)->nullable(); // موقع الطاقة الشمسية (longitude)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solar_energies');
    }
};
