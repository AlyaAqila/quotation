<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->unsignedBigInteger('source_term_id')->nullable()->after('invoice_Id');
            $table->foreign('source_term_id')->references('id')->on('payment_terms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->dropForeign(['source_term_id']);
            $table->dropColumn('source_term_id');
        });
    }
};