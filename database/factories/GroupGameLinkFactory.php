<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\GroupGame;
use App\Models\GroupGameLink;
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

class GroupGameLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupGameLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_game_id' => GroupGame::all()->random()->id,
            'name' => $this->faker->name,
            'link' => 'www.google.com',
            'description' => $this->faker->name,
        ];
    }
}
