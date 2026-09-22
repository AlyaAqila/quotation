<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');

            // 1. References 'id' on table 'modules'
            $table->foreignId('module_id')
                  ->constrained('modules', 'module_id')
                  ->onDelete('cascade');

            // 2. References 'id' on table 'category' (singular table name)
            $table->foreignId('category_id')
                  ->constrained('category', 'category_id')
                  ->onDelete('cascade');

            // 3. References 'id' on table 'service' (singular table name)
            $table->foreignId('service_id')
                  ->constrained('service', 'service_id')
                  ->onDelete('cascade');

            $table->string('item_name');
            $table->decimal('internal_rate', 12, 2)->default(0.00);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};