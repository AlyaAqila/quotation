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
        Schema::table('qt_invoice_items', function (Blueprint $table) {
            $table->foreignId('catalog_item_id')
                ->nullable()
                ->after('module_id')
                ->constrained('items', 'item_id')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('qt_invoice_items', function (Blueprint $table) {
            $table->dropForeign(['catalog_item_id']);
            $table->dropColumn('catalog_item_id');
        });
    }
};
