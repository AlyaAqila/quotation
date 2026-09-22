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
        Schema::create('qt_invoice', function (Blueprint $table) {
            // Primary Key
            $table->id('quotation_Id');

            // Foreign Key - Project
            $table->unsignedBigInteger('project_Id')->nullable();
            $table->foreign('project_Id', 'fk_qt_invoice_project')
                  ->references('project_Id')
                  ->on('projects')
                  ->onDelete('cascade');

            // Header Attributes
            $table->string('quotation_no')->nullable();
            $table->decimal('grand_total', 12, 2)->default(0.00);

            // Audit User Columns & Foreign Keys
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by', 'fk_qt_invoice_created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by', 'fk_qt_invoice_updated_by')
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
        Schema::dropIfExists('qt_invoice');
    }
};