<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\Game;
use App\Repositories\GameRepo;

use Illuminate\Database\Eloquent\Factories\Sequence;

class GameRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $recordCount;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new GameRepo();
        $this->testData  = Game::all();
        $this->recordCount = count($this->testData);
    }

    protected function setApproved(){
        for($x=0;$x<$this->recordCount;$x++){
            $this->repo->approveGame($this->testData[$x]);
        }
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(Game::class, $testData);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['year'], $testData->year);
        $this->assertStringContainsString($data['full_name'], $testData->full_name);
        $this->assertEquals($data['players_min'], $testData->players_min);
        $this->assertEquals($data['players_max'], $testData->players_max);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_game()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m Game Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getGame($this->testData[0]->id);

        $this->dataTests($this->testData[0], $found);
    }

    public function test_get_games()
    {
        $found = $this->repo->getGames();
        $this->assertEquals($this->recordCount, count($found));
    }

    public function test_get_base_games()
    {
        $found = $this->repo->getBaseGames();
        $this->assertEquals(4, count($found));
    }


    public function test_get_expansion_game()
    {
        $found = $this->repo->getExpansionGames($this->testData[0]->id);
        $this->assertEquals(3, count($found));
    }

    public function test_unapproved_games()
    {
        $found = $this->repo->getUnapprovedGames();
        $this->assertEquals(3, count($found));
    }

    public function test_games_not_in_group()
    {
        //set some games to base game to a value
        $data[0] =$this->testData[3]->id;
        $data[1] = $this->testData[4]->id;
        $data[2] = $this->testData[5]->id;

        $found = $this->repo->searchGamesNotInGroup($data);
        $this->assertEquals(($this->recordCount-3), count($found));
    }

    public function test_create_game()
    {
        $data = [
            'name' => "A random created game",
            'year' => "2019",
            'full_name' => "A random created game",
            'players_min' => "3",
            'players_max' => "7",
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);
    }

    public function test_approve_game()
    {
        $this->repo->approveGame($this->repo->getGame($this->testData[8]->id));
        $found = $this->repo->getUnapprovedGames();
        $this->assertEquals(2, count($found));
    }

    public function test_update_game()
    {
        $data = [
            'name' => "A random created game",
            'year' => "2019",
            'full_name' => "A random created game",
            'players_min' => "3",
            'players_max' => "7",
        ];
        $updatedGame = $this->repo->update($data, $this->testData[0]);
        $this->dataTests($data, $updatedGame);
    }

    public function test_update_base_game_id(){
        //first create an expansion game
        $data = [
            'name' => "A random expansion",
            'year' => "2019",
            'full_name' => "A random expansion",
            'players_min' => "3",
            'players_max' => "7",
            'base_game_id' => $this->testData[0]->id
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);

        $success = $this->repo->updateBaseGameId($this->testData[0]->id, $this->testData[1]->id);

        $this->assertEquals($success, 4);
    }

    public function test_update_expansion(){
        //first create an expansion game
        $data = [
            'name' => "A random expansion",
            'year' => "2019",
            'full_name' => "A random expansion",
            'players_min' => "3",
            'players_max' => "7",
            'base_game_id' => $this->testData[0]->id
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);

        $success = $this->repo->updateExpansion($this->testData[0]->id, $this->testData[1]->id);
        $this->assertEquals($success, 1);
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete($this->testData[0]);
        $found = Game::all();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
    }
}
