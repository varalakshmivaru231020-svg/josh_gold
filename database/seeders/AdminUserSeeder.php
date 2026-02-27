<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@blrlawhouse.com',
            'password' => bcrypt('admin123'),
        ]);

        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@blrlawhouse.com',
            'password' => bcrypt('superadmin123'),
        ]);
    }
}