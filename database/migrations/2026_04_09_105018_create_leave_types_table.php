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
    Schema::create('leave_types', function (Blueprint $table) {
        $table->id();
        $table->string('type_name'); // مثل: إجازة إدارية، بلا راتب، مهمة رسمية
        $table->boolean('affects_balance')->default(true); // هل تخصم من الرصيد أم لا
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
