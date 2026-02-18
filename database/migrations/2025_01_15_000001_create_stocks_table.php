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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 10)->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('change', 10, 2);
            $table->decimal('change_percent', 5, 2);
            $table->string('volume', 20);
            $table->string('market_cap', 20);
            $table->string('domain')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('ticker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
