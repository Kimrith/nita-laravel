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
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->id('audit_id');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->foreignId('changed_by')->nullable()->constrained('employees', 'employee_id')->nullOnDelete();
            $table->timestamp('change_timestamp')->useCurrent();
            $table->text('change_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail');
    }
};
