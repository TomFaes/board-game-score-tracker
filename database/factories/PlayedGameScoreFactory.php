<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PlayedGameScore;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use phpseclib\Crypt\Random;

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

$factory->define(PlayedGameScore::class, function (Faker $faker) {
    return [
        'played_game_id' => '1',
        'group_user_id' => '1',
        'score' => "100",
        'remarks' => $faker->name,
    ];
});
