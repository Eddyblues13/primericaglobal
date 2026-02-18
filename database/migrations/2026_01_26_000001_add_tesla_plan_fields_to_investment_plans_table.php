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
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->decimal('max_investment', 16, 2)->nullable()->after('min_investment');
            $table->decimal('profit_margin', 6, 2)->default(0)->after('max_investment');
            $table->unsignedInteger('duration_days')->default(0)->after('profit_margin');
            $table->string('duration_label', 64)->nullable()->after('duration_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_plans', function (Blueprint $table) {
            $table->dropColumn(['max_investment', 'profit_margin', 'duration_days', 'duration_label']);
        });
    }
};
