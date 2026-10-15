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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('leave_type_id')->constrained('leave_types', 'leave_type_id')->cascadeOnDelete();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->foreignId('approver_id')->nullable()->constrained('employees', 'employee_id')->nullOnDelete();
            $table->timestamp('submitted_date')->useCurrent();
            $table->json('location_data')->nullable()->comment('Geo-coordinates or IP metadata');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
