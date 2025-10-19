<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {   
        $products = [
            ['category_id' => 1, 'name' => 'Samsung 55" Smart TV', 'price' => 45000, 'cost_price' => 38000],
            ['category_id' => 1, 'name' => 'iPhone 14 Pro', 'price' => 120000, 'cost_price' => 105000],
            ['category_id' => 1, 'name' => 'Dell Laptop i5 8GB', 'price' => 55000, 'cost_price' => 48000],
            ['category_id' => 1, 'name' => 'Sony Headphones WH-1000XM4', 'price' => 28000, 'cost_price' => 24000],
            ['category_id' => 1, 'name' => 'Canon DSLR Camera', 'price' => 65000, 'cost_price' => 58000],
            ['category_id' => 1, 'name' => 'Apple Watch Series 8', 'price' => 42000, 'cost_price' => 38000],
            
            ['category_id' => 2, 'name' => 'Levis Jeans Blue', 'price' => 3500, 'cost_price' => 2800],
            ['category_id' => 2, 'name' => 'Nike Air Max Shoes', 'price' => 8500, 'cost_price' => 7000],
            ['category_id' => 2, 'name' => 'Polo T-Shirt', 'price' => 1200, 'cost_price' => 900],
            ['category_id' => 2, 'name' => 'Formal Shirt White', 'price' => 1800, 'cost_price' => 1400],
            ['category_id' => 2, 'name' => 'Adidas Track Pants', 'price' => 2500, 'cost_price' => 2000],
            ['category_id' => 2, 'name' => 'Winter Jacket', 'price' => 4500, 'cost_price' => 3600],
            
            ['category_id' => 3, 'name' => 'Coca Cola 2L', 'price' => 120, 'cost_price' => 95],
            ['category_id' => 3, 'name' => 'Lays Chips 100g', 'price' => 50, 'cost_price' => 38],
            ['category_id' => 3, 'name' => 'Nescafe Coffee 200g', 'price' => 450, 'cost_price' => 380],
            ['category_id' => 3, 'name' => 'Kit Kat Chocolate', 'price' => 80, 'cost_price' => 65],
            ['category_id' => 3, 'name' => 'Britannia Biscuits', 'price' => 60, 'cost_price' => 48],
            ['category_id' => 3, 'name' => 'Mineral Water 1L', 'price' => 30, 'cost_price' => 22],
            
            ['category_id' => 4, 'name' => 'Harry Potter Book Set', 'price' => 2500, 'cost_price' => 2000],
            ['category_id' => 4, 'name' => 'Parker Pen Blue', 'price' => 350, 'cost_price' => 280],
            ['category_id' => 4, 'name' => 'A4 Notebook 200 Pages', 'price' => 150, 'cost_price' => 120],
            ['category_id' => 4, 'name' => 'Calculator Scientific', 'price' => 800, 'cost_price' => 650],
            ['category_id' => 4, 'name' => 'Stapler Heavy Duty', 'price' => 250, 'cost_price' => 200],
            ['category_id' => 4, 'name' => 'Pencil Box Set', 'price' => 180, 'cost_price' => 145],
            
            ['category_id' => 5, 'name' => 'Blender 500W', 'price' => 3500, 'cost_price' => 2900],
            ['category_id' => 5, 'name' => 'Non-Stick Pan Set', 'price' => 2200, 'cost_price' => 1800],
            ['category_id' => 5, 'name' => 'Rice Cooker 1.8L', 'price' => 2800, 'cost_price' => 2300],
            ['category_id' => 5, 'name' => 'Dinner Set 24 Pieces', 'price' => 4500, 'cost_price' => 3700],
            ['category_id' => 5, 'name' => 'Kettle Electric 1.7L', 'price' => 1800, 'cost_price' => 1500],
            ['category_id' => 5, 'name' => 'Microwave Oven 20L', 'price' => 8500, 'cost_price' => 7200],
        ];

        foreach ($products as $index => $product) {
            Product::firstOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'category_id' => $product['category_id'],
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'price' => $product['price'],
                    'cost_price' => $product['cost_price'],
                    'stock_quantity' => rand(10, 100),
                    'image' => "https://picsum.photos/300/300?random=" . ($index + 1),
                    'barcode' => str_pad(rand(1000000000000, 9999999999999), 13, '0', STR_PAD_LEFT),
                    'description' => 'High quality ' . strtolower($product['name'])
                ]
            );
        }
    }
}