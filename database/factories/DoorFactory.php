<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Country;
use App\Models\Door;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Door::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->text(350),
            'price' => $this->faker->numberBetween(10000, 150000),
            'quantity' => $this->faker->randomDigit,
            'garant' => $this->faker->randomDigit,
            'size' => $this->faker->randomDigit,
            'brand_id' => Brand::pluck('id')->random(),
            'country_id' => Country::pluck('id')->random(),
            'image' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg'])
        ];
    }
}
