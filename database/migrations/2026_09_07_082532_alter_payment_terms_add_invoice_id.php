<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            // drop the old strict FK so quotation_Id can become nullable
            $table->dropForeign(['quotation_Id']);
            $table->unsignedBigInteger('quotation_Id')->nullable()->change();
            $table->foreign('quotation_Id')
                  ->references('quotation_Id')
                  ->on('qt_invoice')
                  ->onDelete('cascade');

            // new nullable link to invoices
            $table->unsignedBigInteger('invoice_Id')->nullable()->after('quotation_Id');
            $table->foreign('invoice_Id')
                  ->references('invoice_Id')
                  ->on('invoices')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->dropForeign(['invoice_Id']);
            $table->dropColumn('invoice_Id');

            $table->dropForeign(['quotation_Id']);
            $table->unsignedBigInteger('quotation_Id')->nullable(false)->change();
            $table->foreign('quotation_Id')
                  ->references('quotation_Id')
                  ->on('qt_invoice')
                  ->onDelete('cascade');
        });
    }
};