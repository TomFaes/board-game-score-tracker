<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
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
|
*/

class GameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'year' => rand(2000, 2020),
            'approved_by_admin' => 1,
            'base_game_id' => null,
            'full_name' => $this->faker->name,
            'players_min' =>  rand(2, 3),
            'players_max' =>  rand(4, 5),
        ];
    }
}



/*
$factory->define(Game::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'year' => rand(2000, 2020),
        'approved_by_admin' => 0,
    ];
});
*/
