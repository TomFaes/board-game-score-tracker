<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\PlayedGame;
use App\Repositories\PlayedGameRepo;


class PlayedGameRepositoryTest extends TestCase
{

    protected $testData;
    protected $data;
    protected $repo;
    protected $countGroupIdOne;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\PlayedGame::class)->create();
            if($this->testData[$x]['group_id'] == 1){
                $this->countGroupIdOne++;
            }
        }

        $this->repo = new PlayedGameRepo();

        //default dataset
        $this->data = [
            'group_id' => '1',
            'winner_id' => '1',
            'game_id' => '1',
            'remarks' => 'A random remark',
        ];
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(PlayedGame::class, $testData);
        $this->assertEquals($data['group_id'], $testData->group_id);
        $this->assertEquals($data['winner_id'], $testData->winner_id);
        $this->assertEquals($data['game_id'], $testData->game_id);
        $this->assertEquals($data['remarks'], $testData->remarks);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_played_games()
    {
        $found = $this->repo->getPlayedGames();
        $this->assertEquals(10, count($found));
        echo PHP_EOL.'[42m OK  [0m get all played games';
    }

    public function test_get_played_game()
    {
        echo PHP_EOL.PHP_EOL.'[43m Played Game Repository Test:   [0m';
        $found = $this->repo->getPlayedGame($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get played game';
    }

    public function test_get_played_group_games()
    {
        $changeGroup = $this->repo->getPlayedGame(1);
        $changeGroup->group_id = 2;
        $changeGroup->save();

        $found = $this->repo->getPlayedGroupGames(1);

        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m get all played games from a group';
    }

    public function test_create_played_game()
    {
        $testData = $this->repo->create($this->data);
        $this->dataTests($this->data, $testData);
        echo PHP_EOL.'[42m OK  [0m create played game';
    }

    public function test_update_played_game()
    {
        $playedGame = $this->repo->update($this->data, 1);
        $this->dataTests($this->data, $playedGame);
        echo PHP_EOL.'[42m OK  [0m update played game';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getPlayedGames();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete played game';
    }
}
