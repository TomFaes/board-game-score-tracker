<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\User;
use App\Models\PlayedGame;
use App\Repositories\PlayedGameRepo;
use DateTime;

class PlayedGameRepositoryTest extends TestCase
{

    protected $testData;
    protected $repo;
    protected $recordCount;
    protected $countPlayedGamesInGroup;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new PlayedGameRepo();
        $this->testData  = PlayedGame::all();
        $this->recordCount = count($this->testData);

        foreach($this->testData AS $playedGame){
            if($playedGame['group_id'] == $this->testData[0]->group_id){
                $this->countPlayedGamesInGroup++;
            }
        }
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(PlayedGame::class, $testData);
        $this->assertEquals($data['group_id'], $testData->group_id);
        $this->assertEquals($data['game_id'], $testData->game_id);
        $this->assertEquals($data['date'], $testData->date);
        $this->assertEquals($data['time_played'], $testData->time_played);
        $this->assertEquals($data['remarks'], $testData->remarks);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */

    public function test_get_played_games()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m Played Game Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getPlayedGames();
        $this->assertEquals($this->recordCount, count($found));
    }

    public function test_get_played_game()
    {
        $found = $this->repo->getPlayedGame($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
    }

    public function test_get_played_group_games()
    {
        $found = $this->repo->getPlayedGroupGames($this->testData[0]->group_id);

        $this->assertEquals($this->countPlayedGamesInGroup, count($found));
    }

    public function test_get_stats_played_Group_games()
    {
        $playedGames = $this->repo->getPlayedGroupGames($this->testData[0]->group_id);
        $found = $this->repo->getStatPlayedGroupGames($this->testData[0]->group_id);

        $this->assertEquals(count($found), count($playedGames));
    }

    public function test_get_stats_played_group_year_games()
    {
        $year = new DateTime($this->testData[0]->date);
        $year = $year->format('Y');

        $playedGames =  PlayedGame::where('played_games.group_id', $this->testData[0]->group_id)
        ->whereYear('played_games.date', $year)
        ->get();

        $found = $this->repo->getStatPlayedGroupYearGames($this->testData[0]->group_id, $year);
        $this->assertEquals(count($found), count($playedGames));
    }

    public function test_get_stats_played_Group_game()
    {
        $playedGames = PlayedGame::where('played_games.group_id', $this->testData[0]->group_id)
        ->where('played_games.game_id', $this->testData[0]->game_id)
        ->get();

        $found = $this->repo->getStatPlayedGame($this->testData[0]->group_id, $this->testData[0]->game_id);

        $this->assertEquals(count($found), count($playedGames));
    }

    public function test_create_played_game()
    {
        $data = [
            'group_id' => $this->testData[0]->group_id,
            'game_id' => $this->testData[0]->game_id,
            'date' => $this->testData[0]->date,
            'time_played' => $this->testData[0]->time_played,
            'remarks' => $this->testData[0]->remarks,
        ];

        $testData = $this->repo->create($data, User::first()->id);
        $this->dataTests($data, $testData);
    }

    public function test_update_played_game()
    {
        $data = [
            'group_id' => $this->testData[1]->group_id,
            'game_id' => $this->testData[1]->game_id,
            'date' => $this->testData[1]->date,
            'time_played' => $this->testData[1]->time_played,
            'remarks' => $this->testData[1]->remarks,
        ];

        $playedGame = $this->repo->update($data, $this->testData[0]);
        $this->dataTests($data, $playedGame);
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete( $this->testData[0]);
        $found = $this->repo->getPlayedGames();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
    }
}
