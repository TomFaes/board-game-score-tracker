<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\GroupUser;
use App\Models\Group;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class GroupUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->name,
            'name' => $this->faker->name,
            'group_id' => Group::all()->random()->id,
            'user_id' => null
        ];
    }
}
/*
$factory->define(GroupUser::class, function (Faker $faker) {
    return [
        'firstname' => $faker->name,
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'group_id' => 1,
    ];
});
*/
