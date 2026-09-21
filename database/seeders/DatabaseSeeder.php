<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Suppliers
        Supplier::create([
            'name' => 'ABC Suppliers',
            'email' => 'abc@example.com',
            'phone' => '9876543210',
            'address' => 'Kochi, Kerala',
        ]);

        Supplier::create([
            'name' => 'XYZ Traders',
            'email' => 'xyz@example.com',
            'phone' => '9876543211',
            'address' => 'Ernakulam, Kerala',
        ]);

        Supplier::create([
            'name' => 'Global Supplies',
            'email' => 'global@example.com',
            'phone' => '9876543212',
            'address' => 'Bangalore, Karnataka',
        ]);

        // Products
        $products = [
            [
                'sku' => 'LAP001',
                'name' => 'Dell Laptop',
                'unit_price' => 50000,
                'stock_quantity' => 20,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'MOU001',
                'name' => 'Wireless Mouse',
                'unit_price' => 500,
                'stock_quantity' => 25,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'KEY001',
                'name' => 'Mechanical Keyboard',
                'unit_price' => 1500,
                'stock_quantity' => 8,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'MON001',
                'name' => '24 Inch Monitor',
                'unit_price' => 12000,
                'stock_quantity' => 15,
                'low_stock_threshold' => 5,
                'is_active' => true,
            ],
            [
                'sku' => 'USB001',
                'name' => 'USB Cable',
                'unit_price' => 250,
                'stock_quantity' => 40,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'HDD001',
                'name' => 'External Hard Drive',
                'unit_price' => 6000,
                'stock_quantity' => 7,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'RAM001',
                'name' => '16GB RAM',
                'unit_price' => 4500,
                'stock_quantity' => 12,
                'low_stock_threshold' => 5,
                'is_active' => true,
            ],
            [
                'sku' => 'WEB001',
                'name' => 'Webcam',
                'unit_price' => 2500,
                'stock_quantity' => 6,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
            [
                'sku' => 'HEAD001',
                'name' => 'USB Headset',
                'unit_price' => 1800,
                'stock_quantity' => 18,
                'low_stock_threshold' => 5,
                'is_active' => true,
            ],
            [
                'sku' => 'PAD001',
                'name' => 'Mouse Pad',
                'unit_price' => 300,
                'stock_quantity' => 30,
                'low_stock_threshold' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
