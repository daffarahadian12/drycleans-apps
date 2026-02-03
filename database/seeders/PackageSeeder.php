<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Cuci Kering',
                'description' => 'Layanan cuci dan kering standar untuk pakaian sehari-hari',
                'price_per_kg' => 5000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Layanan cuci, kering, dan setrika untuk pakaian rapi',
                'price_per_kg' => 7000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Express',
                'description' => 'Layanan cuci cepat selesai dalam 1 hari',
                'price_per_kg' => 10000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Premium',
                'description' => 'Layanan cuci premium dengan deterjen khusus dan pewangi',
                'price_per_kg' => 12000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Layanan dry cleaning untuk pakaian berbahan khusus',
                'price_per_kg' => 15000,
                'estimated_days' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Sepatu',
                'description' => 'Layanan khusus cuci sepatu dan sandal',
                'price_per_kg' => 20000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Karpet',
                'description' => 'Layanan cuci karpet dan permadani',
                'price_per_kg' => 8000,
                'estimated_days' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Ekonomis',
                'description' => 'Paket hemat untuk cuci dalam jumlah banyak',
                'price_per_kg' => 4000,
                'estimated_days' => 3,
                'is_active' => false, // Inactive package for testing
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
