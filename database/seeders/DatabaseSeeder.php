<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@warehouse.com',
            'password' => Hash::make('12345678'),
            'role' => 'SYSTEM_ADMIN'
        ]);

        User::create([
            'name' => 'Warehouse Manager',
            'email' => 'manager@warehouse.com',
            'password' => Hash::make('12345678'),
            'role' => 'WAREHOUSE_MANAGER'
        ]);

        User::create([
            'name' => 'Client User',
            'email' => 'client@warehouse.com',
            'password' => Hash::make('12345678'),
            'role' => 'CLIENT'
        ]);

        // Create 50 InventoryItem entries
        InventoryItem::factory(50)->create();
    }
}
