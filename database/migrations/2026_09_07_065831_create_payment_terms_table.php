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
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            
            // Foreign key referencing quotation_Id on the qt_invoice table
            $table->unsignedBigInteger('quotation_Id');
            $table->foreign('quotation_Id')
                  ->references('quotation_Id')
                  ->on('qt_invoice')
                  ->onDelete('cascade');
            
            $table->string('name');                          // e.g., First Payment, Final Payment
            $table->decimal('percentage', 5, 2);             // e.g., 40.00
            $table->text('condition')->nullable();           // e.g., Upon acceptance of LOA
            $table->decimal('amount', 12, 2)->nullable();     // Calculated RM amount
            
            // Tracking columns for creation & updates
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Foreign keys pointing to users table
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Automatically generates created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};