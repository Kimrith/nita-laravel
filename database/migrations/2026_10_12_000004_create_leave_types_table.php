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
            $table->id('leave_type_id');
            $table->string('type_name');
            $table->boolean('is_paid')->default(true);
            $table->timestamps();
        });

        DB::table('leave_types')->insert([
            ['type_name' => 'Sick', 'is_paid' => true, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Vacation', 'is_paid' => true, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Personal', 'is_paid' => false, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Maternity / Paternity', 'is_paid' => true, 'created_at' => now(), 'updated_at' => now()],
            ['type_name' => 'Unpaid Leave', 'is_paid' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
