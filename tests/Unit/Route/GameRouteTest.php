<?php

namespace Tests\Unit\Route;

use App\Models\Game;
use App\Models\User;
use App\Repositories\GameRepo;
use Tests\TestCase;
use Laravel\Passport\Passport;


class GameRouteTest extends TestCase
{
    protected $testData;
    protected $recordCount;

    protected $repo;

    protected $loggedInUser;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
        $this->repo = new GameRepo();
        $this->testData = Game::all();
        $this->recordCount = count($this->testData);
        $this->loggedInUser = User::first();
    }

    /**
     *  Get authenticated user
     */
    protected function authenticatedUser($role = "Admin"){
        $user = Passport::actingAs(
            $this->loggedInUser ,
            ['create-servers']
        );
        $user->role = $role;
        return $user;
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
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['year'], $testData->year);
        $this->assertStringContainsString($data['full_name'], $testData->full_name);
        $this->assertEquals($data['players_min'], $testData->players_min);
        $this->assertEquals($data['players_max'], $testData->players_max);
    }

    public function test_GameController_index()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m Game api Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $this->be($this->authenticatedUser('Admin'));
        $response = $this->get('/api/game');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $response_data = $response->getData();

        $this->assertEquals($this->recordCount, count($response_data));
        echo PHP_EOL.'[42m OK  [0m GameController: test index';
    }

    public function test_GameController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $data = [
            'name' => "A random created game",
            'year' => "2019",
            'full_name' => "A random created game",
            'players_min' => "3",
            'players_max' => "7",
        ];

        $response = $this->postJson('/api/game/', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($data, $response_data);
        echo PHP_EOL.'[42m OK  [0m GameController: test store';
    }

    public function test_GameController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $data = [
            'name' => "A random created game",
            'year' => "2019",
            'full_name' => "A random created game",
            'players_min' => "3",
            'players_max' => "7",
        ];

        $response = $this->postJson('/api/game/'. $this->testData[0]->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        $this->dataTests($data, $response_data);
        echo PHP_EOL.'[42m OK  [0m GameController: test update';
    }

    public function test_GameController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/game/'. $this->testData[0]->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals("Game is deleted", $response_data);
        echo PHP_EOL.'[42m OK  [0m GameController: test delete';
    }


    public function test_BaseGameController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        //set some games to base game to a value
        $data = [
            'base_game_id' => $this->testData[0]->id,
        ];
        $this->repo->update($data,  $this->testData[3]->id);
        $this->repo->update($data,  $this->testData[4]->id);
        $this->repo->update($data,  $this->testData[5]->id);
        $this->setApproved();

        $response = $this->get('/api/basegame');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(($this->recordCount - 3), count($response_data));
        echo PHP_EOL.'[42m OK  [0m BaseGameController: test index';
    }

    public function test_UnapprovedGameController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/unapprovedgames/'. $this->testData[0]->id);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        echo PHP_EOL.'[42m OK  [0m UnapprovedGameController: test update';
    }

    public function test_UnapprovedGameController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $this->postJson('/api/unapprovedgames/'. $this->testData[0]->id);
        $this->postJson('/api/unapprovedgames/'. $this->testData[1]->id);
        $this->postJson('/api/unapprovedgames/'. $this->testData[2]->id);

        $response = $this->get('/api/unapprovedgames');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $this->assertEquals(($this->recordCount - 3), count($response_data->data));
        echo PHP_EOL.'[42m OK  [0m UnapprovedGameController: test index';
    }

    public function test_MergeGameController_update()
    {
        $this->be($this->authenticatedUser('Admin'));
        $response = $this->postJson('/api/merge/'.$this->testData[0]->id.'/game/'.$this->testData[1]->id);

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        echo PHP_EOL.'[42m OK  [0m MergeGameController: test update';
    }
}
