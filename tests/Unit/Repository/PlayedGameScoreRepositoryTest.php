<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;


use App\Models\PlayedGameScore;
use App\Repositories\PlayedGameScoreRepo;


class PlayedGameScoreRepositoryTest extends TestCase
{

    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\PlayedGameScore::class)->create();
        }

        $this->repo = new PlayedGameScoreRepo();

        //default dataset
        $this->data = [
            'played_game_id' => '1',
            'group_user_id' => '1',
            'score' => '100',
            'remarks' => 'A random remark',
        ];
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
        $this->assertEquals($data['remarks'], $testData->remarks);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_played_game_scores()
    {
        echo PHP_EOL.PHP_EOL.'[43m Played Game Score Repository Test:   [0m';
        $found = $this->repo->getPlayedGameScores();
        $this->assertEquals(10, count($found));
        echo PHP_EOL.'[42m OK  [0m get all played games scores';
    }

    public function test_get_played_game_score()
    {
        $found = $this->repo->getPlayedGameScore($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get played game score';
    }

    public function test_get_scores_of_a_played_game()
    {
        $changePlayedGame = $this->repo->getPlayedGameScore(1);
        $changePlayedGame->played_game_id = 2;
        $changePlayedGame->save();

        $found = $this->repo->getScorePlayedGame(1);

        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m get all scores from a played game';
    }

    public function test_get_user_scores()
    {
        $changePlayedGameUser = $this->repo->getPlayedGameScore(1);
        $changePlayedGameUser->group_user_id = 2;
        $changePlayedGameUser->save();

        $found = $this->repo->getUserPlayedGameScores(1);

        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m get all scores from a user';
    }



    public function test_create_played_game_score()
    {
        $testData = $this->repo->create($this->data);
        $this->dataTests($this->data, $testData);
        echo PHP_EOL.'[42m OK  [0m create played game score';
    }

    public function test_create_set_of_played_gamescores()
    {
        $score[] = array( 'group_user_id' => '1', 'score' => '100', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'group_user_id' => '2', 'score' => '102', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'group_user_id' => '3', 'score' => '103', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'group_user_id' => '4', 'score' => '99', 'place' => '0', 'remarks' => 'A random remark');

        $winnerId = $this->repo->createSetScores($score, 1);

        $this->assertEquals(3, $winnerId);

        echo PHP_EOL.'[42m OK  [0m create a set of played game score';
    }

    public function test_update_played_game_score()
    {
        $playedGame = $this->repo->update($this->data, 1);
        $this->dataTests($this->data, $playedGame);
        echo PHP_EOL.'[42m OK  [0m update played game score';
    }

    public function test_update_set_of_played_gamescores()
    {
        $score[] = array( 'id'=> 1, 'group_user_id' => '1', 'score' => '100', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'id'=> 1, 'group_user_id' => '2', 'score' => '102', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'id'=> 1, 'group_user_id' => '3', 'score' => '103', 'place' => '0', 'remarks' => 'A random remark');
        $score[] = array( 'id'=> 1, 'group_user_id' => '4', 'score' => '99', 'place' => '0', 'remarks' => 'A random remark');

        $winnerId = $this->repo->updateSetScore($score, 1);

        $this->assertEquals(3, $winnerId);

        echo PHP_EOL.'[42m OK  [0m update a set of played game score';
    }

    public function test_delete_game_score()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getPlayedGameScores();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete played game score';
    }
}
