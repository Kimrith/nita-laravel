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
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name');
            $table->integer('access_level')->default(1);
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['role_name' => 'Admin', 'access_level' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'Department Manager', 'access_level' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'Staff', 'access_level' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
