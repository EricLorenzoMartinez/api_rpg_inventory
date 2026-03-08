<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Character;

class CharacterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Character::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Define the default state for the Character model using fake data
        return [
            'name' => $this->faker->firstName() . ' el ' . $this->faker->word(),
            'level' => $this->faker->numberBetween(1, 50),
            // The 'user_id' will be provided directly from the Seeder
        ];
    }
}
