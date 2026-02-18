<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Bitcoin',
                'slug' => 'bitcoin',
                'type' => 'both',
                'category' => 'crypto',
                'logo_url' => 'https://cryptologos.cc/logos/bitcoin-btc-logo.png',
                'details' => "Network: Bitcoin (BTC)\nWallet address:\nbc1qnz33pkla58pmytcpqefa435frlzasqxv26cx5m",
                'display_order' => 1,
            ],
            [
                'name' => 'Ethereum',
                'slug' => 'ethereum',
                'type' => 'both',
                'category' => 'crypto',
                'logo_url' => 'https://cryptologos.cc/logos/ethereum-eth-logo.png',
                'details' => "Network: Ethereum (ERC-20)\nWallet address:\n0xcD990f9B80cddF7f0B1091f1947b4Ba5EB24Dfc8",
                'display_order' => 2,
            ],
            [
                'name' => 'Litecoin',
                'slug' => 'litecoin',
                'type' => 'both',
                'category' => 'crypto',
                'logo_url' => 'https://cryptologos.cc/logos/litecoin-ltc-logo.png',
                'details' => "Network: Litecoin (LTC)\nWallet address:\nltc1qa90pwnmtca604pm85e7908cyaa2qd7mlynxgsg",
                'display_order' => 3,
            ],
            [
                'name' => 'Solana',
                'slug' => 'solana',
                'type' => 'both',
                'category' => 'crypto',
                'logo_url' => 'https://cryptologos.cc/logos/solana-sol-logo.png',
                'details' => "Network: Solana (SOL)\nWallet address:\n5tiPYnVEDuqjGMhUXYwSNKY8rBaYBgQbyp9MUEzfadds",
                'display_order' => 4,
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'type' => 'both',
                'category' => 'bank',
                'logo_url' => 'https://cdn-icons-png.flaticon.com/512/84/84578.png',
                'details' => "Bank name: Tesla Global Bank\nAccount name: Tesla Investments Ltd\nAccount number: 1234567890\nIBAN: GB00TESL00000012345678\nSWIFT/BIC: TESLGB2L",
                'display_order' => 5,
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'type' => 'deposit',
                'category' => 'wallet',
                'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg',
                'details' => "PayPal email: payments@tesla-investments.com",
                'display_order' => 6,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }
    }
}

