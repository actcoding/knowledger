<?php

namespace Database\Factories;

use App\Models\Documentation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documentation>
 */
class KBFactory extends Factory
{
    protected $model = Documentation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->jobTitle();
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
