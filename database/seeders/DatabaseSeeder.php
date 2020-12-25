<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Material;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Models\Brand;
use App\Models\Door;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Country::factory(4)->create();
        Brand::factory(4)->create();
        Door::factory(15)->create();
        Role::factory(2)->create();
        User::factory(3)->create();
        Cart::factory(12)->create();
        Color::factory(6)->create();
//        Material::factory(4)->create();
    }
}
