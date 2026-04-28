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
        Schema::table('events', function (Blueprint $table) {
        $table->string('category')->nullable();
        $table->integer('capacity')->nullable();

        $table->enum('type', ['online', 'in-person'])->after('location');

        $table->string('venue_name')->nullable();
        $table->string('address')->nullable();

        $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
