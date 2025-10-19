<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {   
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'TVs, phones, laptops and electronic devices'
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Shirts, pants, shoes and apparel'
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Snacks, drinks and food items'
            ],
            [
                'name' => 'Books & Stationery',
                'slug' => 'books-stationery',
                'description' => 'Books, pens, notebooks and office supplies'
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'description' => 'Utensils, appliances and home essentials'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}