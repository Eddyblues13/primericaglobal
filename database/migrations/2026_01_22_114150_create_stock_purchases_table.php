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
        Schema::create('stock_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity'); // number of shares
            $table->decimal('price_per_share', 10, 2); // price at time of purchase
            $table->decimal('total_amount', 14, 2); // quantity * price_per_share
            $table->string('order_type')->default('market'); // market, limit
            $table->decimal('limit_price', 10, 2)->nullable(); // if limit order
            $table->string('status')->default('Completed'); // Pending, Completed, Cancelled
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_purchases');
    }
};
