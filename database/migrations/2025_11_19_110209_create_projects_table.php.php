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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code', 100)->unique();
            $table->string('name');
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('donor_name')->nullable()->comment('الجهة المانحة');
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_phone', 50)->nullable();
            $table->foreignId('project_type_id')->constrained('project_types');
            $table->foreignId('main_status_id')->constrained('project_main_statuses');
            $table->foreignId('general_status_id')->constrained('project_general_statuses');
            $table->foreignId('handover_status_id')->constrained('handover_statuses');
            $table->decimal('total_value', 15, 2)->nullable()->comment('قيمة العقد الإجمالية');
            $table->string('currency', 10)->default('USD');
            $table->date('contract_date')->nullable();
            $table->integer('total_duration_days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('hac_issue_number', 100)->nullable()->comment('رقم كتاب HAC');
            $table->date('hac_issue_date')->nullable();
            $table->date('hac_received_date')->nullable()->comment('تاريخ ورود كتاب HAC للديوان');
            $table->string('approval_number', 100)->nullable();
            $table->date('approval_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
