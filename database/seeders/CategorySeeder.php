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
        Category::create(['name' => 'Web Development']);
        Category::create(['name' => 'Mobile Development']);
        Category::create(['name' => 'Desktop Development']);
        Category::create(['name' => 'Game Development']);
        Category::create(['name' => 'Marketing']);
        Category::create(['name' => 'Design']);
        Category::create(['name' => 'Video & Animation']);
        Category::create(['name' => 'Music & Audio']);
        Category::create(['name' => 'Writing']);
        Category::create(['name' => 'Translation']);
        Category::create(['name' => 'Legal']);
        Category::create(['name' => 'Financial']);
        Category::create(['name' => 'Engineering']);
        Category::create(['name' => 'Architecture']);
        Category::create(['name' => 'Data Entry']);
        Category::create(['name' => 'Customer Service']);
        Category::create(['name' => 'Data Science']);
        Category::create(['name' => 'Artificial Intelligence']);
        Category::create(['name' => 'Cyber Security']);
        Category::create(['name' => 'DevOps']);
        Category::create(['name' => 'Cloud Computing']);

    }
}
