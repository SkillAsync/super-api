<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Freelancer>
 */
class FreelancerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all();
        $service = Service::all();
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => $user->random()->id,
            'description' => $this->faker->paragraph,
            'service_id' => $service->random()->id,
        ];
    }
}
