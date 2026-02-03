<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'is_active' => true,
        ]);

        // Owner User
        User::create([
            'name' => 'Owner Laundry',
            'email' => 'owner@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'phone' => '081234567891',
            'address' => 'Jl. Owner No. 2, Jakarta',
            'is_active' => true,
        ]);

        // Karyawan Users
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'phone' => '081234567892',
            'address' => 'Jl. Karyawan No. 3, Jakarta',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'phone' => '081234567893',
            'address' => 'Jl. Karyawan No. 4, Jakarta',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Rina Wati',
            'email' => 'rina@laundry.com',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'phone' => '081234567894',
            'address' => 'Jl. Karyawan No. 5, Jakarta',
            'is_active' => false, // Inactive user for testing
        ]);
    }
}
