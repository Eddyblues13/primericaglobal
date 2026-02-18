<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvestmentPlan;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tesla Investment Plans (exactly 4).
     * WARNING: This removes ALL existing investment plans and user investments.
     * Backup your data if you need to keep history.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily for clean truncate
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear all data (truncate is faster and resets auto-increment)
        \DB::table('user_investments')->truncate();
        \DB::table('investment_plans')->truncate();
        
        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $plans = [
            [
                'name' => 'STARTER PLAN',
                'slug' => 'starter',
                'category' => 'Tesla',
                'strategy' => 'Tesla Investment',
                'risk_level' => 'medium',
                'nav' => 0,
                'one_year_return' => 20,
                'min_investment' => 2000,
                'max_investment' => 9999,
                'profit_margin' => 20,
                'duration_days' => 2,
                'duration_label' => '2 days',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'MEGA PLAN',
                'slug' => 'mega',
                'category' => 'Tesla',
                'strategy' => 'Tesla Investment',
                'risk_level' => 'medium',
                'nav' => 0,
                'one_year_return' => 20,
                'min_investment' => 10000,
                'max_investment' => 39999,
                'profit_margin' => 20,
                'duration_days' => 4,
                'duration_label' => '4 days',
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'GRAND PLAN',
                'slug' => 'grand',
                'category' => 'Tesla',
                'strategy' => 'Tesla Investment',
                'risk_level' => 'high',
                'nav' => 0,
                'one_year_return' => 30,
                'min_investment' => 40000,
                'max_investment' => 99999,
                'profit_margin' => 30,
                'duration_days' => 7,
                'duration_label' => '7 days',
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'VIP PLAN',
                'slug' => 'vip',
                'category' => 'Tesla',
                'strategy' => 'Tesla Investment',
                'risk_level' => 'high',
                'nav' => 0,
                'one_year_return' => 50,
                'min_investment' => 999000,
                'max_investment' => null,
                'profit_margin' => 50,
                'duration_days' => 30,
                'duration_label' => '1 month',
                'is_featured' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            InvestmentPlan::create($plan);
        }
    }
}
