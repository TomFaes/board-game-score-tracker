<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupGame;
use App\Models\Game;
use App\Repositories\GroupGameRepo;
use phpseclib\Crypt\Random;

class GroupGameRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $recordCount;
    protected $countGamesInGroup;
    protected $listOfGames;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
        $this->repo = new GroupGameRepo();

        $this->testData  = GroupGame::all();
        $this->recordCount = count($this->testData);

        $this->listOfGames = Game::all();

        foreach($this->testData AS $groupgame){
            if($groupgame['group_id'] == $this->testData[0]->group_id){
                $this->countGamesInGroup++;
            }
        }
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(GroupGame::class, $testData);
        $this->assertEquals($data['group_id'], $testData->group_id);
        $this->assertEquals($data['game_id'], $testData->game_id);
    }

    public function test_get_group_games()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m GroupGame Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = GroupGame::all();
        $this->assertEquals($this->recordCount, count($found));
    }

    public function test_get_group_game()
    {
        $found = $this->repo->getGroupGame($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
    }

    public function test_get_group_game_ids()
    {
        $found = $this->repo->getIdsOfGamesOfGroup($this->testData[0]->group_id);
        $this->assertEquals($this->countGamesInGroup, count($found));
    }

    public function test_get_games_of_group()
    {
        $found = $this->repo->getGamesOfGroup($this->testData[0]->group_id);
        $this->assertEquals($this->countGamesInGroup, count($found));
    }

    public function test_create_group_game()
    {
        $data = [
            'group_id' =>  $this->testData[0]->group_id,
            'game_id' => $this->listOfGames[0]->id,
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);
    }

    public function test_update_group_game_ids()
    {
        $return = $this->repo->updateGroupGameIds($this->testData[0]->game_id, $this->listOfGames[0]->id);
        $this->assertEquals($return , "group games updated");
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete($this->testData[0]);
        $found = GroupGame::all();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
    }
}
