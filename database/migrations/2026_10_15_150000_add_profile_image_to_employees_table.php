<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaultImage = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaC_2saS2_XH6OGHmktJs0U5Es2-6jVi_KY3h8RDjVncK0Jrcf3X8MsUw&s=10';

        Schema::table('employees', function (Blueprint $table) use ($defaultImage) {
            $table->string('profile_image', 500)
                ->default($defaultImage)
                ->after('email');
        });

        // Update any existing rows to have the default image if null or empty
        DB::table('employees')->whereNull('profile_image')->orWhere('profile_image', '')->update([
            'profile_image' => $defaultImage,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
