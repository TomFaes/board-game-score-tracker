<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\GroupUser;
use App\Models\Group;
use App\Models\User;

use Faker\Generator as Faker;

class GroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Eloquent::unguard();

        // Disable Foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        GroupUser::factory()
            ->count(10)
            ->create();
        //$group = Group::all();
        //$user = User::all();

        /*
        GroupUser::factory()
            ->count(10)
            ->state(new Sequence(
                ['group_id' => Group::all()->random()],
                ['group_id' => Group::all()->random()],
            ))
            ->create();
        /*
         [
                    'group_game_id' => GroupGame::all()->random(),
                ]


        GroupGameLink::factory()
            ->count(10)
            ->create();
            */
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
