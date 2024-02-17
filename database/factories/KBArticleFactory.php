<?php

namespace Database\Factories;

use App\Models\KBArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KBArticle>
 */
class KBArticleFactory extends Factory
{
    protected $model = KBArticle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->jobTitle();
        return [
            'title' => $name,
            'subtitle' => $this->faker->sentence(10),
            'slug' => str($name)->slug(),
            'content' => <<<MD
            ## About Laravel

            > The PHP Framework for Web Artisans

            Laravel is a web application framework with expressive, elegant syntax.
            We've already laid the foundation — freeing you to create without sweating the small things.
            MD
        ];
    }
}
