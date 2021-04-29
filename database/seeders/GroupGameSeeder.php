<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GroupGame;
use App\Models\Game;
use App\Models\Group;

class GroupGameSeeder extends Seeder
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


        GroupGame::factory()
            ->count(10)
            ->create();
        /*
        $groups = Group::all()->random(5);

        foreach ($groups as $group) {
            $games = Game::all()->random(4);
            foreach ($games as $game) {
                GroupGame::firstOrCreate([
                    'group_id' => $group->id,
                    'game_id' => $game->id,
                ]);
            }
        }
        */




        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
