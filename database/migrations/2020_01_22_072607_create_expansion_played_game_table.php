<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpansionPlayedGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expansion_played_game', function (Blueprint $table) {
            $table->bigInteger('played_game_id')->unsigned()->index();
            $table->bigInteger('game_id')->unsigned()->index();

            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('played_game_id')->references('id')->on('played_games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expansion_played_game');
    }
}
