<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use App\Models\Door;

use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Cart::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'quantity' => $this->faker->randomDigit,
      'user_id' => User::pluck('id')->random(),
      'door_id' => Door::pluck('id')->random(),
    ];
  }
}
