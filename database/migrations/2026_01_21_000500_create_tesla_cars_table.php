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
        Schema::create('tesla_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model'); // Model S, 3, X, Y, etc.
            $table->string('year')->nullable();
            $table->string('variant')->nullable(); // Long Range, Performance, etc.
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('range_miles')->nullable();
            $table->unsignedInteger('top_speed_mph')->nullable();
            $table->decimal('zero_to_sixty', 4, 2)->nullable();
            $table->string('drivetrain')->nullable(); // AWD, RWD, etc.
            $table->string('image_url')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesla_cars');
    }
};

