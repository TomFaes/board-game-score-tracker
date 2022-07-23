<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Game;

class GameSeeder extends Seeder
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

        Game::factory(4)
            ->create();

        $firstGame = Game::first();

        Game::factory(3)
            ->create([
                'base_game_id' => $firstGame->id
            ]);

        Game::factory(3)
            ->create([
                'approved_by_admin' => 0
            ]);

//'approved_by_admin' => 1,



        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
