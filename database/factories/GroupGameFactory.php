<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\GroupGame;
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

$factory->define(GroupGame::class, function (Faker $faker) {
    return [
        'group_id' => $faker->numberBetween(1, 10),
        'game_id' => $faker->numberBetween(1, 10),

    ];
});
