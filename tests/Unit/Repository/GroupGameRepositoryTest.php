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
    protected $countGroupIdOne;

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
        $this->countGroupIdOne = 0;
        for($x=0;$x<10;$x++) {
            $this->testGroupsGames[] = factory(\App\Models\GroupGame::class)->create();

            if($this->testGroupsGames[$x]['group_id'] == 1){
                $this->countGroupIdOne++;
            }
        }
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
    public function test_get_group_games()
    {
        echo PHP_EOL.PHP_EOL.'[43m GroupGame Repository Test:   [0m';

        $found = $this->repo->getGroupGames();
        $this->assertEquals(10, count($found));

        echo PHP_EOL.'[42m OK  [0m get all group games';
    }

    public function test_get_group_game()
    {
        $found = $this->repo->getGroupGame($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get group game';
    }

    public function test_get_group_game_ids()
    {
        $found = $this->repo->getGroupGameIds(1);
        $this->assertEquals($this->countGroupIdOne, count($found));
        echo PHP_EOL.'[42m OK  [0m get group game ids';
    }

    public function test_get_games_of_group()
    {
        $found = $this->repo->getGamesOfGroup(1);
        $this->assertEquals($this->countGroupIdOne, count($found));
        echo PHP_EOL.'[42m OK  [0m get games of group';
    }

    public function test_create_group_game()
    {
        $data = [
            'group_id' =>  1,
            'game_id' => 1,
        ];

        $testData = $this->repo->create($data);
        $this->assertInstanceOf(GroupGame::class, $testData);
        $this->assertEquals($data['group_id'], $testData->group_id);
        $this->assertEquals($data['game_id'], $testData->game_id);
        echo PHP_EOL.'[42m OK  [0m create group game';
    }

    public function test_update_game()
    {
        $found = $this->repo->getGroupGame($this->testData[0]->id);
        $data = [
            'group_id' =>  1,
            'game_id' => 2,
        ];
        $return = $this->repo->updateGroupGameIds($found->id, $data['game_id']);
        $this->assertEquals($return , "group games updated");

        echo PHP_EOL.'[42m OK  [0m update group game';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getGroupGames();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete group test';
    }
}
