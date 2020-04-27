<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlayedGame;
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

$factory->define(PlayedGame::class, function (Faker $faker) {
    return [
        'group_id' => '1',
        'winner_id' => '1',
        'game_id' => '1',
        'remarks' => $faker->name,
    ];
});
