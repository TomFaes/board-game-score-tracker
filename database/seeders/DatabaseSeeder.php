<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(GameSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(GroupGameSeeder::class);
        $this->call(GroupGameLinkSeeder::class);
        $this->call(GroupUserSeeder::class);
        $this->call(PlayedGameSeeder::class);
        $this->call(PlayedGameScoreSeeder::class);
    }
}
