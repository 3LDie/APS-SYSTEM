<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Suppliers (Manufacturers)
        Supplier::create([
            'name' => 'Northern Press Distributor',
            'contact_person' => 'Mang Juan',
            'contact_number' => '09123456789',
            'address' => 'Baguio City'
        ]);

        // 2. Seed Products (Newspaper Varieties & Styles)
        $products = [
            // Korean Varieties
            ['variety_name' => 'Korean', 'style_type' => 'Cut', 'stock_quantity' => 100, 'unit_price' => 50.00, 'reorder_level' => 15],
            ['variety_name' => 'Korean', 'style_type' => 'Standard', 'stock_quantity' => 80, 'unit_price' => 45.00, 'reorder_level' => 15],
            
            // Japan Varieties
            ['variety_name' => 'Japan', 'style_type' => 'Cut', 'stock_quantity' => 120, 'unit_price' => 55.00, 'reorder_level' => 20],
            ['variety_name' => 'Japan', 'style_type' => 'Standard', 'stock_quantity' => 90, 'unit_price' => 50.00, 'reorder_level' => 20],

            // Textbook Varieties
            ['variety_name' => 'Textbook', 'style_type' => 'Cut', 'stock_quantity' => 150, 'unit_price' => 40.00, 'reorder_level' => 25],
            ['variety_name' => 'Textbook', 'style_type' => 'Standard', 'stock_quantity' => 130, 'unit_price' => 35.00, 'reorder_level' => 25],

            // White Textbook Varieties
            ['variety_name' => 'White Textbook', 'style_type' => 'Cut', 'stock_quantity' => 110, 'unit_price' => 60.00, 'reorder_level' => 15],
            ['variety_name' => 'White Textbook', 'style_type' => 'Standard', 'stock_quantity' => 95, 'unit_price' => 55.00, 'reorder_level' => 15],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // 3. Seed Sample Farmers / Customers & Delivery Posts
        $customers = [
            ['name' => 'Mang Tasyo', 'contact_number' => '09198765432', 'delivery_post_location' => 'Km. 5 Post A'],
            ['name' => 'Aling Maria', 'contact_number' => '09181112233', 'delivery_post_location' => 'Trading Post Shed 3'],
            ['name' => 'Karding Ramos', 'contact_number' => '09174445566', 'delivery_post_location' => 'Pico Farm Hillside'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}