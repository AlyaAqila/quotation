<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qt_invoice', function (Blueprint $table) {
            $table->text('payment_terms')->nullable()->after('grand_total');
            $table->text('additional_notes')->nullable()->after('payment_terms');
        });
    }

    public function down(): void
    {
        Schema::table('qt_invoice', function (Blueprint $table) {
            $table->dropColumn(['payment_terms', 'additional_notes']);
        });
    }
};