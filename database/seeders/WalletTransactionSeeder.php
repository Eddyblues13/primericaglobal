<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WalletTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        $baseDate = Carbon::create(2025, 10, 24, 10, 0, 0, 'UTC');

        $transactions = [
            [
                'title' => 'Investment',
                'type' => 'investment',
                'asset' => 'Wallet',
                'amount' => 19560.00,
                'direction' => 'debit',
                'status' => 'Completed',
                'offsetMinutes' => 0,
            ],
            [
                'title' => 'Deposit',
                'type' => 'deposit',
                'asset' => 'Litecoin',
                'amount' => 50000.00,
                'direction' => 'credit',
                'status' => 'Completed',
                'offsetMinutes' => -60,
            ],
            [
                'title' => 'Deposit',
                'type' => 'deposit',
                'asset' => 'Bitcoin',
                'amount' => 20000.00,
                'direction' => 'credit',
                'status' => 'Completed',
                'offsetMinutes' => -120,
            ],
            [
                'title' => 'Withdrawal',
                'type' => 'withdrawal',
                'asset' => 'Bank',
                'amount' => 500.00,
                'direction' => 'debit',
                'status' => 'Completed',
                'offsetMinutes' => -180,
            ],
        ];

        foreach ($transactions as $tx) {
            WalletTransaction::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => $tx['type'],
                    'asset' => $tx['asset'],
                    'title' => $tx['title'],
                    'amount' => $tx['amount'],
                    'direction' => $tx['direction'],
                ],
                [
                    'status' => $tx['status'],
                    'occurred_at' => $baseDate->copy()->addMinutes($tx['offsetMinutes']),
                ]
            );
        }
    }
}

