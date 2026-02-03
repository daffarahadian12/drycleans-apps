<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'name' => 'Diskon Member 5kg+',
                'min_weight' => 5.00,
                'discount_percentage' => 10.00,
                'is_member_only' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Cuci Besar 10kg+',
                'min_weight' => 10.00,
                'discount_percentage' => 15.00,
                'is_member_only' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Member Premium 20kg+',
                'min_weight' => 20.00,
                'discount_percentage' => 20.00,
                'is_member_only' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Reguler 3kg+',
                'min_weight' => 3.00,
                'discount_percentage' => 5.00,
                'is_member_only' => false,
                'is_active' => true,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }
    }
}
