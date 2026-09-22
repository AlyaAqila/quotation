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
        // 1. Create the new units table
        Schema::create('units', function (Blueprint $table) {
            $table->id('unit_id'); // Primary key matching primaryKey in Unit model
            $table->string('unit_name', 50)->unique();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();

            // Foreign keys pointing to users table
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the units table
        Schema::dropIfExists('units');
    }
};