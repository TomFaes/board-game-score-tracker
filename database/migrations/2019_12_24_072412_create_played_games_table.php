<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayedGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('played_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('game_id')->unsigned();
            $table->bigInteger('winner_id')->unsigned()->nullable();
            $table->bigInteger('creator_id')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->time('time_played')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('winner_id')->references('id')->on('group_users');
            $table->foreign('creator_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('played_games');
    }
}
