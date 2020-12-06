<?php

namespace Database\Factories;

use App\External;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExternalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = External::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
        ];
    }
}
