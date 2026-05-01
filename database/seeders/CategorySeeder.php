<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Art', 'slug' => 'art'],
            ['name' => 'Tech', 'slug' => 'tech'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Sport', 'slug' => 'sport'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
            ['slug' => $category['slug']],
            ['name' => $category['name']]
        );        }
    }
}