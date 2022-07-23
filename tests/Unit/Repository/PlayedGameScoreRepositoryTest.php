<?php

namespace Tests\Unit\Repository;

use App\Models\PlayedGame;
use Tests\TestCase;


use App\Models\PlayedGameScore;
use App\Repositories\PlayedGameScoreRepo;


class PlayedGameScoreRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $recordCount;
    protected $countPlayerGameScores;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new PlayedGameScoreRepo();
        $this->testData  = PlayedGameScore::all();
        $this->recordCount = count($this->testData);

        foreach($this->testData AS $playedGame){
            if($playedGame['group_user_id'] == $this->testData[0]->group_user_id){
                $this->countPlayerGameScores++;
            }
        }
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(PlayedGameScore::class, $testData);
        $this->assertEquals($data['played_game_id'], $testData->played_game_id);
        $this->assertEquals($data['group_user_id'], $testData->group_user_id);
        $this->assertEquals($data['score'], $testData->score);
        $this->assertEquals($data['place'], $testData->place);
        $this->assertEquals($data['remarks'], $testData->remarks);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */

    public function test_get_played_game_scores()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m GroupUser Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getPlayedGameScores();
        $this->assertEquals($this->recordCount, count($found));
    }

    public function test_get_played_game_score()
    {
        $found = $this->repo->getPlayedGameScore($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
    }

    public function test_get_user_scores()
    {
        $found = $this->repo->getUserPlayedGameScores($this->testData[0]->group_user_id);
        $this->assertEquals($this->countPlayerGameScores, count($found));
    }

    public function test_create_played_game_score()
    {
        $data = [
            'played_game_id' => $this->testData[0]->played_game_id,
            'group_user_id' => $this->testData[0]->group_user_id,
            'score' => $this->testData[0]->score,
            'place' => $this->testData[0]->place,
            'remarks' => $this->testData[0]->remarks,
        ];

        $testData = $this->repo->create($data);
        $this->dataTests($data, $testData);
    }

    public function test_create_set_of_played_gamescores()
    {
        $playedGame = PlayedGame::factory()->create();

        $score = array();
        for($x=0; $x<4; $x++){
            $score[] = array(
                'group_user_id' => $this->testData[$x]->group_user_id,
                'score' => 100 + $x,
                'place' => '0',
                'remarks' => 'A random remark'
            );
        }

        $winnerId = $this->repo->createSetScores($score, $playedGame->id);
        //$x - 1 because the for loops stops when $x is 4
        $this->assertEquals($this->testData[$x-1]->group_user_id, $winnerId);
    }

    public function test_update_played_game_score()
    {
        $data = [
            'played_game_id' => $this->testData[0]->played_game_id,
            'group_user_id' => $this->testData[0]->group_user_id,
            'score' => $this->testData[0]->score,
            'place' => $this->testData[0]->place,
            'remarks' => $this->testData[0]->remarks,
        ];

        $playedGame = $this->repo->update($data, $this->testData[1]->id);
        $this->dataTests($data, $playedGame);
    }

    public function test_update_set_of_played_gamescores()
    {
        $score = array();
        for($x=0; $x<4; $x++){
            $score[] = array(
                'id' => $this->testData[$x]->id,
                'group_user_id' => $this->testData[$x]->group_user_id,
                'score' => 100 + $x,
                'place' => '0',
                'remarks' => 'A random remark'
            );
        }

        $winnerId = $this->repo->updateSetScore($score, $this->testData[0]->played_game_id);
        //$x - 1 because the for loops stops when $x is 4
        $this->assertEquals($this->testData[$x-1]->group_user_id, $winnerId);
    }

    public function test_delete_game_score()
    {
        $delete = $this->repo->delete($this->testData[0]->id);
        $found = $this->repo->getPlayedGameScores();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
    }

}
