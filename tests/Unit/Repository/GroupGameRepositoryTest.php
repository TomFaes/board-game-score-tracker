<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupGame;
use App\Repositories\GroupGameRepo;
use phpseclib\Crypt\Random;

class GroupGameRepositoryTest extends TestCase
{

    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        $this->repo = new GroupGameRepo();

        for($x=0;$x<10;$x++){
            $this->testGames[] = factory(\App\Models\Game::class)->create();
        }

        for($x=0;$x<10;$x++){
            $this->testGroups[] = factory(\App\Models\Group::class)->create();
        }

        //default dataset
        $this->data = [
            'group_id' => 1,
            'game_id' => 1,
        ];
        $this->repo->create($this->data);
        $this->testData = $this->repo->getGroupGames();

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

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_group_game()
    {
        echo PHP_EOL.PHP_EOL.'[43m GroupGame Repository Test:   [0m';
        $found = $this->repo->getGroupGame($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get group game';
    }



}
