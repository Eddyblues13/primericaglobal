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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // deposit, withdrawal, investment, etc.
            $table->string('asset')->nullable(); // e.g. Bitcoin, Ethereum, Wallet
            $table->string('title'); // short label like "Investment" or "Deposit"
            $table->text('withdrawal_details')->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('direction'); // credit or debit
            $table->string('status')->default('Completed');
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

