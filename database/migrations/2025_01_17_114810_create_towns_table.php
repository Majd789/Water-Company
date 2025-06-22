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
        Schema::create('towns', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('town_name');
            $table->string('town_code')->unique();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // Foreign Key to Units
            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
