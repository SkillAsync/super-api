<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Web Development',
                            'slug' => 'web-development',
]);
        Category::create(['name' => 'Mobile Development',
                            'slug' => 'mobile-development',]);
        Category::create(['name' => 'Desktop Development',
                            'slug' => 'desktop-development',]);
        Category::create(['name' => 'Game Development',
                            'slug' => 'game-development',]);
        Category::create(['name' => 'Marketing',
                            'slug' => 'marketing',]);
        Category::create(['name' => 'Design']);
    }
}
