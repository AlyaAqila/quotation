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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_Id');
            $table->string('invoice_number')->unique();

            $table->unsignedBigInteger('quotation_Id');
            $table->foreign('quotation_Id')
                ->references('quotation_Id')
                ->on('qt_invoice')
                ->onDelete('cascade');

            $table->date('invoice_date')->nullable();
            $table->timestamp('printed_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();   // <-- added here

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
