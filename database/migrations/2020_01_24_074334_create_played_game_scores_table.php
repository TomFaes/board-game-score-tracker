<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayedGameScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('played_game_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('played_game_id')->unsigned();
            $table->bigInteger('group_user_id')->unsigned();
            $table->bigInteger('score')->nullable();
            $table->integer('place')->unsigned()->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();


            $table->foreign('played_game_id')->references('id')->on('played_games');
            $table->foreign('group_user_id')->references('id')->on('group_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('played_game_scores');
    }
}
