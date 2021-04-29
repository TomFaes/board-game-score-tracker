<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GroupUser;
use App\Models\PlayedGame;
use App\Models\PlayedGameScore;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayedGameScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlayedGameScore::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'played_game_id' => PlayedGame::all()->random()->id,
            'group_user_id' => GroupUser::all()->random()->id,
            'score' => $this->faker->numberBetween(50, 200),
            'remarks' => $this->faker->text,
        ];
    }
}



/*
$factory->define(PlayedGameScore::class, function (Faker $faker) {
    return [
        'played_game_id' => PlayedGame::all()->random()->id,
        'group_user_id' => GroupUser::all()->random()->id,
        'score' => $this->faker->numberBetween(50, 200),
        'remarks' => $faker->text,
    ];
});
*/
