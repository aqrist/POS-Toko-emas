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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('gold_level_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('product_type');
            $table->decimal('weight', 8, 3);
            $table->decimal('price_per_gram', 14, 2);
            $table->decimal('total', 14, 2);
            $table->boolean('is_synced')->default(true);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
