<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ahmad Wijaya',
                'phone' => '081234567801',
                'address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                'is_member' => true,
                'member_since' => Carbon::now()->subMonths(6),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Sari Dewi',
                'phone' => '081234567802',
                'address' => 'Jl. Sudirman No. 25, Jakarta Selatan',
                'is_member' => true,
                'member_since' => Carbon::now()->subMonths(3),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Bambang Sutrisno',
                'phone' => '081234567803',
                'address' => 'Jl. Thamrin No. 15, Jakarta Pusat',
                'is_member' => false,
                'member_since' => null,
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Indira Sari',
                'phone' => '081234567804',
                'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                'is_member' => true,
                'member_since' => Carbon::now()->subYear(),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Rudi Hartono',
                'phone' => '081234567805',
                'address' => 'Jl. Kuningan No. 45, Jakarta Selatan',
                'is_member' => false,
                'member_since' => null,
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Maya Sari',
                'phone' => '081234567806',
                'address' => 'Jl. Kemang No. 12, Jakarta Selatan',
                'is_member' => true,
                'member_since' => Carbon::now()->subMonths(8),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Dedi Kurniawan',
                'phone' => '081234567807',
                'address' => 'Jl. Pancoran No. 33, Jakarta Selatan',
                'is_member' => false,
                'member_since' => null,
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Lestari Wulandari',
                'phone' => '081234567808',
                'address' => 'Jl. Cikini No. 77, Jakarta Pusat',
                'is_member' => true,
                'member_since' => Carbon::now()->subMonths(4),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Agus Setiawan',
                'phone' => '081234567809',
                'address' => 'Jl. Menteng No. 21, Jakarta Pusat',
                'is_member' => false,
                'member_since' => null,
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
            [
                'name' => 'Fitri Handayani',
                'phone' => '081234567810',
                'address' => 'Jl. Senayan No. 55, Jakarta Selatan',
                'is_member' => true,
                'member_since' => Carbon::now()->subMonths(2),
                'total_transactions' => 0,
                'total_spent' => 0,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
