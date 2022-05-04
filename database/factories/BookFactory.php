<?php

namespace Database\Factories;

use App\Models\Books;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Books::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'ISBN' => $this->faker->text(),
            'Author' => $this->faker->name(),
            'Publisher' => $this->faker->name(),
            'Year Published' => $this->faker->year(),
            'Category' => $this->faker->name()
        ];
    }
}
