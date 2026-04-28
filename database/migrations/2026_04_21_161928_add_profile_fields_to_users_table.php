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
        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->nullable();
        $table->string('company_name')->nullable();
        $table->string('company_number')->nullable();
        $table->text('address')->nullable();
        $table->string('profile_picture')->nullable();
        $table->string('cv')->nullable();
        $table->string('country')->nullable();
        $table->string('city')->nullable();
        $table->string('linkedin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
            'job_title',
            'company_name',
            'company_number',
            'address',
            'profile_picture',
            'cv',
            'country',
            'city',
            'linkedin',
        ]);
        });
    }
};
