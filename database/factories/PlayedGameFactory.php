<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Game;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\PlayedGame;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class PlayedGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlayedGame::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_id' => Group::all()->random()->id,
            'game_id' => Game::all()->random()->id,
            'winner_id' => GroupUser::all()->random()->id,
            'creator_id' => User::all()->random()->id,
            'date' => $this->faker->dateTimeThisCentury->format('Y-m-d'),
            'time_played' => '01:00:00',
            'remarks' => $this->faker->text,
        ];
    }
}











/*

$factory->define(PlayedGame::class, function (Faker $faker) {
    return [
        'group_id' => '1',
        'winner_id' => '1',
        'game_id' => '1',
        'remarks' => $faker->name,
    ];
});
*/
