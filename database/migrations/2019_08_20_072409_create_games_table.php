<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index()->unique();
            $table->integer('year')->nullable()->default(0);
            $table->integer('players_min')->nullable()->default(0);
            $table->integer('players_max')->nullable()->default(0);
            $table->bigInteger('base_game_id')->nullable()->unsigned()->index();
            $table->integer('approved_by_admin')->nullable()->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('base_game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
