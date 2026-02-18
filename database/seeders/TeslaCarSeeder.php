<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeslaCar;

class TeslaCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'name' => 'Model S Plaid',
                'model' => 'Model S',
                'year' => '2025',
                'variant' => 'Plaid',
                'price' => 94990.00,
                'range_miles' => 396,
                'top_speed_mph' => 200,
                'zero_to_sixty' => 1.99,
                'drivetrain' => 'Tri Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/6e3b08c5-6f91-4f27-8b42-1be14fdb8658/bvlatuR/std/0x0/ms-main-hero-desktop',
                'display_order' => 1,
            ],
            [
                'name' => 'Model 3 Long Range',
                'model' => 'Model 3',
                'year' => '2025',
                'variant' => 'Long Range',
                'price' => 45990.00,
                'range_miles' => 358,
                'top_speed_mph' => 125,
                'zero_to_sixty' => 4.2,
                'drivetrain' => 'Dual Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/0f3f57e5-09a8-4be0-8b2e-3f0ca14f991b/bvlatuR/std/0x0/m3-homepage-desktop',
                'display_order' => 2,
            ],
            [
                'name' => 'Model X',
                'model' => 'Model X',
                'year' => '2025',
                'variant' => 'Long Range',
                'price' => 89990.00,
                'range_miles' => 351,
                'top_speed_mph' => 155,
                'zero_to_sixty' => 3.8,
                'drivetrain' => 'Dual Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/8c2c5e8e-8c93-4f1d-8fb4-9b3b5a793b77/bvlatuR/std/0x0/mx-main-hero-desktop',
                'display_order' => 3,
            ],
            [
                'name' => 'Model Y Performance',
                'model' => 'Model Y',
                'year' => '2025',
                'variant' => 'Performance',
                'price' => 52990.00,
                'range_miles' => 303,
                'top_speed_mph' => 155,
                'zero_to_sixty' => 3.5,
                'drivetrain' => 'Dual Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/19cf94dc-e89b-4f5f-9e42-b4b5d4798365/bvlatuR/std/0x0/my-homepage-desktop',
                'display_order' => 4,
            ],
            [
                'name' => 'Cybertruck',
                'model' => 'Cybertruck',
                'year' => '2025',
                'variant' => 'All-Wheel Drive',
                'price' => 79990.00,
                'range_miles' => 340,
                'top_speed_mph' => 112,
                'zero_to_sixty' => 4.1,
                'drivetrain' => 'Dual Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/2c9b5e44-2e7b-4e9b-9c9b-384b921fe2f5/bvlatuR/std/0x0/ct-main-hero-desktop',
                'display_order' => 5,
            ],
            [
                'name' => 'Roadster',
                'model' => 'Roadster',
                'year' => '2025',
                'variant' => 'Founders Series',
                'price' => 200000.00,
                'range_miles' => 620,
                'top_speed_mph' => 250,
                'zero_to_sixty' => 1.90,
                'drivetrain' => 'Tri Motor AWD',
                'image_url' => 'https://tesla-cdn.thron.com/delivery/public/image/tesla/0a08bbf9-98c0-4f99-a9b5-707952cec663/bvlatuR/std/0x0/roadster-social',
                'display_order' => 6,
            ],
        ];

        foreach ($cars as $car) {
            TeslaCar::updateOrCreate(
                ['name' => $car['name']],
                $car
            );
        }
    }
}

