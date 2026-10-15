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
        Schema::create('shift_patterns', function (Blueprint $table) {
            $table->id('shift_pattern_id');
            $table->string('shift_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_flexible')->default(false);
            $table->integer('grace_period_mins')->default(0);
            $table->timestamps();
        });

        DB::table('shift_patterns')->insert([
            [
                'shift_name' => 'Standard Morning Shift',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'is_flexible' => false,
                'grace_period_mins' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_patterns');
    }
};
