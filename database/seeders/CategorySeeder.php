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
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}