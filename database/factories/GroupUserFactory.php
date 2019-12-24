<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\GroupUser;
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

$factory->define(GroupUser::class, function (Faker $faker) {
    return [
        'firstname' => $faker->name,
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'group_id' => 1,
    ];
});
