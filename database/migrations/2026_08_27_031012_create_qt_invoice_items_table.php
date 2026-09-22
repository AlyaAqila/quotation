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
        Schema::create('qt_invoice_items', function (Blueprint $table) {
            $table->id('item_id');

            // Foreign Key linking back to parent Quotation Header (qt_invoices)
            $table->foreignId('quotation_id')
                  ->constrained('qt_invoice', 'quotation_id')
                  ->cascadeOnDelete();

            // Foreign Key linking to Module/Service (adjust column/table names if needed)
            $table->foreignId('module_id')
                  ->nullable()
                  ->constrained('modules', 'module_id')
                  ->nullOnDelete();

            // Line Item Quantities & Rates
            $table->integer('unit_qty')->default(1);
            $table->integer('days')->default(1);
            $table->decimal('daily_rate', 12, 2)->default(0.00);
            $table->decimal('mark_up', 8, 2)->default(0.00);
            $table->decimal('line_total', 12, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qt_invoice_items');
    }
};