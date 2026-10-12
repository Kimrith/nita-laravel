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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id')->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles', 'role_id')->nullOnDelete();
            $table->foreignId('shift_pattern_id')->nullable()->constrained('shift_patterns', 'shift_pattern_id')->nullOnDelete();
            $table->date('hire_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('employee_id')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });

        Schema::dropIfExists('employees');
    }
};
