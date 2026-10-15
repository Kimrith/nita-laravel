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
        Schema::table('leave_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_requests', 'last_edited_by')) {
                $table->foreignId('last_edited_by')->nullable()->after('approver_id')->constrained('employees', 'employee_id')->nullOnDelete();
            }
            if (!Schema::hasColumn('leave_requests', 'last_edited_at')) {
                $table->timestamp('last_edited_at')->nullable()->after('last_edited_by');
            }
            if (!Schema::hasColumn('leave_requests', 'edit_reason')) {
                $table->string('edit_reason', 255)->nullable()->after('last_edited_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'last_edited_by')) {
                $table->dropForeign(['last_edited_by']);
                $table->dropColumn('last_edited_by');
            }
            if (Schema::hasColumn('leave_requests', 'last_edited_at')) {
                $table->dropColumn('last_edited_at');
            }
            if (Schema::hasColumn('leave_requests', 'edit_reason')) {
                $table->dropColumn('edit_reason');
            }
        });
    }
};
