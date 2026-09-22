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
        Schema::table('modules', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('category', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('service', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('service', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};