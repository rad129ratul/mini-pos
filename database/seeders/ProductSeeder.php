<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs by name
        $electronicsId = Category::where('slug', 'electronics')->first()->id;
        $clothingId = Category::where('slug', 'clothing')->first()->id;
        $foodId = Category::where('slug', 'food-beverages')->first()->id;
        $booksId = Category::where('slug', 'books-stationery')->first()->id;
        $homeId = Category::where('slug', 'home-kitchen')->first()->id;

        $products = [
            ['category_id' => $electronicsId, 'name' => 'Samsung 55" Smart TV', 'price' => 45000, 'cost_price' => 38000],
            ['category_id' => $electronicsId, 'name' => 'iPhone 14 Pro', 'price' => 120000, 'cost_price' => 105000],
            ['category_id' => $electronicsId, 'name' => 'Dell Laptop i5 8GB', 'price' => 55000, 'cost_price' => 48000],
            ['category_id' => $electronicsId, 'name' => 'Sony Headphones WH-1000XM4', 'price' => 28000, 'cost_price' => 24000],
            ['category_id' => $electronicsId, 'name' => 'Canon DSLR Camera', 'price' => 65000, 'cost_price' => 58000],
            ['category_id' => $electronicsId, 'name' => 'Apple Watch Series 8', 'price' => 42000, 'cost_price' => 38000],
            
            ['category_id' => $clothingId, 'name' => 'Levis Jeans Blue', 'price' => 3500, 'cost_price' => 2800],
            ['category_id' => $clothingId, 'name' => 'Nike Air Max Shoes', 'price' => 8500, 'cost_price' => 7000],
            ['category_id' => $clothingId, 'name' => 'Polo T-Shirt', 'price' => 1200, 'cost_price' => 900],
            ['category_id' => $clothingId, 'name' => 'Formal Shirt White', 'price' => 1800, 'cost_price' => 1400],
            ['category_id' => $clothingId, 'name' => 'Adidas Track Pants', 'price' => 2500, 'cost_price' => 2000],
            ['category_id' => $clothingId, 'name' => 'Winter Jacket', 'price' => 4500, 'cost_price' => 3600],
            
            ['category_id' => $foodId, 'name' => 'Coca Cola 2L', 'price' => 120, 'cost_price' => 95],
            ['category_id' => $foodId, 'name' => 'Lays Chips 100g', 'price' => 50, 'cost_price' => 38],
            ['category_id' => $foodId, 'name' => 'Nescafe Coffee 200g', 'price' => 450, 'cost_price' => 380],
            ['category_id' => $foodId, 'name' => 'Kit Kat Chocolate', 'price' => 80, 'cost_price' => 65],
            ['category_id' => $foodId, 'name' => 'Britannia Biscuits', 'price' => 60, 'cost_price' => 48],
            ['category_id' => $foodId, 'name' => 'Mineral Water 1L', 'price' => 30, 'cost_price' => 22],
            
            ['category_id' => $booksId, 'name' => 'Harry Potter Book Set', 'price' => 2500, 'cost_price' => 2000],
            ['category_id' => $booksId, 'name' => 'Parker Pen Blue', 'price' => 350, 'cost_price' => 280],
            ['category_id' => $booksId, 'name' => 'A4 Notebook 200 Pages', 'price' => 150, 'cost_price' => 120],
            ['category_id' => $booksId, 'name' => 'Calculator Scientific', 'price' => 800, 'cost_price' => 650],
            ['category_id' => $booksId, 'name' => 'Stapler Heavy Duty', 'price' => 250, 'cost_price' => 200],
            ['category_id' => $booksId, 'name' => 'Pencil Box Set', 'price' => 180, 'cost_price' => 145],
            
            ['category_id' => $homeId, 'name' => 'Blender 500W', 'price' => 3500, 'cost_price' => 2900],
            ['category_id' => $homeId, 'name' => 'Non-Stick Pan Set', 'price' => 2200, 'cost_price' => 1800],
            ['category_id' => $homeId, 'name' => 'Rice Cooker 1.8L', 'price' => 2800, 'cost_price' => 2300],
            ['category_id' => $homeId, 'name' => 'Dinner Set 24 Pieces', 'price' => 4500, 'cost_price' => 3700],
            ['category_id' => $homeId, 'name' => 'Kettle Electric 1.7L', 'price' => 1800, 'cost_price' => 1500],
            ['category_id' => $homeId, 'name' => 'Microwave Oven 20L', 'price' => 8500, 'cost_price' => 7200],
        ];

        foreach ($products as $index => $product) {
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'price' => $product['price'],
                'cost_price' => $product['cost_price'],
                'stock_quantity' => rand(10, 100),
                'image' => "https://picsum.photos/300/300?random=" . ($index + 1),
                'barcode' => str_pad(rand(1000000000000, 9999999999999), 13, '0', STR_PAD_LEFT),
                'description' => 'High quality ' . strtolower($product['name'])
            ]);
        }
    }
}