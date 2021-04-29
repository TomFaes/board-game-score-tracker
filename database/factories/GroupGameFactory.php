<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\GroupGame;
use App\Models\Group;
use App\Models\Game;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
*/

class GroupGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupGame::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$order->products()->skip(1)->first();//Second row
        return [
            'group_id' => Group::all()->random()->id,
            'game_id' => Game::all()->random()->id,
        ];
    }
}


/*

$factory->define(GroupGame::class, function (Faker $faker) {
    return [
        'group_id' => $faker->numberBetween(1, 10),
        'game_id' => $faker->numberBetween(1, 10),

    ];
});
*/
