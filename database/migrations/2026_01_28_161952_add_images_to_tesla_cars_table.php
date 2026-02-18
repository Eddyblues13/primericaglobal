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
        Schema::table('tesla_cars', function (Blueprint $table) {
            // Add JSON field to store array of image paths
            // Images will be stored in public/cars/{car_id}/ directory
            $table->json('images')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tesla_cars', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
