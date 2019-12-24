<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Game;
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

$factory->define(Game::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'year' => rand(2000, 2020),
        'approved_by_admin' => 0,
    ];
});
