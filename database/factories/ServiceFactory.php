<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
        $categories = Category::all();

        $user = User::get();
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => $user->random()->id,
            'category_id' => $categories->random()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'price' => 10.5,
            
        ];
    }
}
