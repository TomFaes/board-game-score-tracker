<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\GroupGameLink;
use App\Models\GroupGame;
use Faker\Generator as Faker;

class GroupGameLinkSeeder extends Seeder
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

        GroupGameLink::factory()
            ->count(10)
            ->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
