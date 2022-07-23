<?php

namespace Tests\Unit\Route;

use App\Models\Game;
use App\Models\User;
use App\Repositories\GameRepo;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

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
    protected function authenticatedUser(){
        $user = Sanctum::actingAs(
            $this->loggedInUser ,
        );
        return $user;
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

        $this->assertEquals($this->recordCount, count($response_data->data));
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

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($data, $response_data);
    }

    public function test_GameController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));

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

        $response = $this->postJson('/api/game/'. $response_data->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());

        $this->assertEquals(true, $response_data);
    }


    public function test_BaseGameController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/basegame');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(($this->recordCount - 6), count($response_data->data));
    }

    public function test_UnapprovedGameController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/unapprovedgames/'. $this->testData[0]->id);

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
    }

    public function test_UnapprovedGameController_index()
    {
        $this->be($this->authenticatedUser());

        $response = $this->get('/api/unapprovedgames');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $this->assertEquals(3, count($response_data->data));
    }

    public function test_MergeGameController_update()
    {
        $this->be($this->authenticatedUser('Admin'));
        $response = $this->postJson('/api/merge/'.$this->testData[0]->id.'/game/'.$this->testData[1]->id);

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
    }
}
