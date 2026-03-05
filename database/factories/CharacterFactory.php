<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Character;

class CharacterFactory extends Factory
{
    protected $model = Character::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName() . ' el ' . $this->faker->word(),
            'level' => $this->faker->numberBetween(1, 50),
            // El 'user_id' se lo pasaremos directamente desde el Seeder
        ];
    }
}