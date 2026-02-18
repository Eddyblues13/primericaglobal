<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\StockSeeder;
use Database\Seeders\InvestmentPlanSeeder;
use Database\Seeders\WalletTransactionSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\TeslaCarSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            StockSeeder::class,
            InvestmentPlanSeeder::class,
            WalletTransactionSeeder::class,
            PaymentMethodSeeder::class,
            TeslaCarSeeder::class,
        ]);
    }
}
