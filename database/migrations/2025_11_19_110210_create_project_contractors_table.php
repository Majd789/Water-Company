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
       Schema::create('project_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('contract_code', 100)->unique();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('contractors')->nullable()->onDelete('set null');
            $table->date('contract_date')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->integer('execution_phases')->default(1);
            $table->integer('duration_days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->string('contract_status')->nullable()->comment('مثل: موافقة');
            $table->foreignId('contractor_status_id')->constrained('contractor_statuses');
            $table->string('org_approval_number', 100)->nullable();
            $table->date('org_approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_contractors');
    }
};
