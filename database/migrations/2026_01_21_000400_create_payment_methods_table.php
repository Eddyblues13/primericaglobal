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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // deposit, withdrawal, both
            $table->string('logo_url')->nullable(); // external logo url
            $table->unsignedInteger('display_order')->default(0);
            $table->string('category')->default('other'); // crypto, bank, wallet, other
            $table->text('details')->nullable(); // wallet address, bank details, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

