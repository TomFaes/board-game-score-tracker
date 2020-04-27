<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\Game;
use App\Repositories\GameRepo;

class GameRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\Game::class)->create();
        }
        $this->repo = new GameRepo();
    }

    protected function setApproved(){
        for($x=1;$x<=10;$x++){
            $this->repo->approveGame($this->repo->getGame($x));
        }
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_game()
    {
        echo PHP_EOL.PHP_EOL.'[43m Game Repository Test:   [0m';

        $found = $this->repo->getGame($this->testData[0]->id);
        $this->assertInstanceOf(Game::class, $found);
        $this->assertEquals($found->name, $this->testData[0]->name);
        $this->assertEquals($found->year, $this->testData[0]->year);
        echo PHP_EOL.'[42m OK  [0m get game';
    }

    public function test_get_games()
    {
        $found = $this->repo->getGames();
        $this->assertEquals(10, count($found));

        echo PHP_EOL.'[42m OK  [0m get all games';
    }


    public function test_get_base_games()
    {
        //set some games to base game to a value
        $data = [
            'base_game_id' => 1,
        ];
        $this->repo->update($data, 4);
        $this->repo->update($data, 5);
        $this->repo->update($data, 6);

        $this->setApproved();
        $found = $this->repo->getBaseGames();
        $this->assertEquals(7, count($found));

        echo PHP_EOL.'[42m OK  [0m get base games';
    }

    public function test_get_expansion_game()
    {
        //set some games to base game to a value
        $data = [
            'base_game_id' => 1,
        ];
        $this->repo->update($data, 4);
        $this->repo->update($data, 5);
        $this->repo->update($data, 6);

        $this->setApproved();

        $found = $this->repo->getExpansionGames(1);
        $this->assertEquals(3, count($found));
        echo PHP_EOL.'[42m OK  [0m get expansion games';
    }

    public function test_approved_games()
    {
        $this->repo->approveGame($this->repo->getGame(4));
        $this->repo->approveGame($this->repo->getGame(5));
        $this->repo->approveGame($this->repo->getGame(6));

        $found = $this->repo->getApprovedGames();
        $this->assertEquals(3, count($found));
        echo PHP_EOL.'[42m OK  [0m get approved games';
    }

    public function test_unapproved_games()
    {
        $this->repo->approveGame($this->repo->getGame(4));
        $this->repo->approveGame($this->repo->getGame(5));
        $this->repo->approveGame($this->repo->getGame(6));

        $found = $this->repo->getUnapprovedGames();
        $this->assertEquals(7, count($found));
        echo PHP_EOL.'[42m OK  [0m get unapproved games';
    }

    public function test_games_not_in_group()
    {
        //set some games to base game to a value
        $data[0] = [1];
        $data[1] = [2];
        $data[2] = [4];

        $found = $this->repo->searchGamesNotInGroup($data);
        $this->assertEquals(7, count($found));
        echo PHP_EOL.'[42m OK  [0m get all games not in an array of games';
    }

    public function test_create_game()
    {
        $data = [
            'name' => "A random created game",
            'year' => "2019",
        ];

        $testData = $this->repo->create($data);
        $this->assertInstanceOf(Game::class, $testData);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['year'], $testData->year);
        echo PHP_EOL.'[42m OK  [0m create game';
    }

    public function test_update_game()
    {
        $data = [
            'name' => "A updated game",
            'year' => "2019",
        ];

        $game = $this->repo->update($data, 1);
        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals($data['name'], $game->name);
        $this->assertEquals($data['year'], $game->year);

        echo PHP_EOL.'[42m OK  [0m update game';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getGames();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete group test';
    }
}
