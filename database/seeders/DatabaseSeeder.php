<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        Agent::create([
            'name' => 'Jane Smith',
            'mobile' => '9876543211',
            'email' => 'jane@example.com',
            'password' => Hash::make('12345678'),
            'referral_code' => 'EFGH5678',
            'referred_by' => 'ABCD1234',
            'total_earnings' => 3000,
            'settled_amount' => 2000,
            'pending_amount' => 1000,
            'mobile_verified_at' => now()
        ]);

        // Create Sample Products
        Product::create([
            'name' => 'Premium Smartphone',
            'description' => 'Latest smartphone with advanced features, high-resolution camera, and long-lasting battery.',
            'price' => 29999.99,
            'stock' => 50,
            'images' => ['products/sample1.jpg', 'products/sample2.jpg'],
            'is_active' => true
        ]);
    }
}