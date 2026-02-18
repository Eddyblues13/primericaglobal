<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This creates the full `investment_plans` table, including
     * Tesla‑specific fields like max_investment, profit_margin,
     * duration_days, and duration_label used across the app.
     */
    public function up(): void
    {
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();

            // Core identity / display
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();      // e.g. "Tesla"
            $table->string('strategy')->nullable();      // e.g. "Tesla Investment"
            $table->string('risk_level');                // low / medium / high

            // Basic fund metrics
            $table->decimal('nav', 16, 4)->default(0);   // not heavily used, but kept for flexibility
            $table->decimal('one_year_return', 6, 2)->default(0);

            // Investment limits
            $table->decimal('min_investment', 16, 2);
            $table->decimal('max_investment', 16, 2)->nullable(); // NULL = Unlimited

            // Tesla plan profit terms
            // e.g. 20% after 2 days, 30% after 7 days, 50% after 1 month
            $table->decimal('profit_margin', 6, 2)->default(0);
            $table->unsignedInteger('duration_days')->default(0);
            $table->string('duration_label', 64)->nullable(); // human label like "2 days", "1 month"

            // Display / feature flags
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('display_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};

