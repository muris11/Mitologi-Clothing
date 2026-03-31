<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@mitologiclothing.com'],
            [
                'name' => 'Admin Mitologi',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Demo Customers
        $customers = [
            [
                'name' => 'Rifqy Customer',
                'email' => 'customer@demo.com',
                'phone' => '08123456789',
                'address' => 'Jl. Malioboro No. 123',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55271',
            ],
            [
                'name' => 'Anisa Rahmawati',
                'email' => 'anisa@demo.com',
                'phone' => '08567891234',
                'address' => 'Jl. Kaliurang KM 12 No. 45',
                'city' => 'Sleman',
                'province' => 'DI Yogyakarta',
                'postal_code' => '55581',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@demo.com',
                'phone' => '08198765432',
                'address' => 'Jl. Sudirman No. 88',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10210',
            ],
            [
                'name' => 'Denny Kurniawan',
                'email' => 'denny@demo.com',
                'phone' => '08211234567',
                'address' => 'Jl. Diponegoro No. 32',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40115',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@demo.com',
                'phone' => '08534567890',
                'address' => 'Jl. Pemuda No. 15',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60271',
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                    'city' => $customer['city'],
                    'province' => $customer['province'],
                    'postal_code' => $customer['postal_code'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
