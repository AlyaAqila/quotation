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
        Schema::create('projects', function (Blueprint $table) {
            // Primary Key
            $table->id('project_Id');

            // Foreign Key - Client
            $table->unsignedBigInteger('client_Id')->nullable();
            $table->foreign('client_Id', 'fk_projects_client_id')
                  ->references('client_Id')
                  ->on('clients')
                  ->onDelete('set null');

            // Basic Project Info
            $table->string('number')->nullable();
            $table->string('name');
            
            // Explicit dates for calculations
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('period')->nullable();

            // Person in Charge details
            $table->string('pic_name')->nullable();
            $table->string('pic_no')->nullable();
        
            // Audit User Columns & Foreign Keys
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by', 'fk_projects_created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by', 'fk_projects_updated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};