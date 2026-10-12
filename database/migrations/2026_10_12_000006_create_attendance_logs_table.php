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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id')->cascadeOnDelete();
            $table->timestamp('event_timestamp');
            $table->enum('event_type', ['Clock-In', 'Clock-Out', 'Break-Start', 'Break-End']);
            $table->string('source_device', 50)->comment('Kiosk, Mobile, Web');
            $table->json('location_data')->nullable()->comment('Geo-coordinates or geo-fencing metadata');
            $table->enum('status', ['Pending', 'Verified', 'Invalid'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
