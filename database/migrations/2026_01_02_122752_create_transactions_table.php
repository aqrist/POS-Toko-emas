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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('branch_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('type');
            $table->string('payment_method');
            $table->timestamp('occurred_at');
            $table->decimal('subtotal', 14, 2);
            $table->decimal('additional_fee', 14, 2)->nullable();
            $table->decimal('total', 14, 2);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
