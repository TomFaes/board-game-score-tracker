<?php

namespace Tests\Unit\Route;

use App\Models\PlayedGame;
use App\Models\User;
use App\Repositories\PlayedGameRepo;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PlayedRouteTest extends TestCase
{
    protected $testData;
    protected $recordCount;
    protected $countPlayedGamesInGroup;

    protected $repo;
    protected $loggedInUser;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new PlayedGameRepo();
        $this->testData = PlayedGame::all();
        $this->recordCount = count($this->testData);
        $this->loggedInUser = User::first();

        foreach($this->testData AS $playedGame){
            if($this->testData[0]->group_id == $playedGame->group_id){
                $this->countPlayedGamesInGroup++;
            }
        }
    }

    /**
     *  Get authenticated user
     */
    protected function authenticatedUser(){
        $user = Sanctum::actingAs(
            $this->loggedInUser
        );
        return $user;
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertEquals($data['group_id'], $testData->group_id);
        $this->assertEquals($data['winner_id'], $testData->winner_id);
        $this->assertEquals($data['game_id'], $testData->game_id);
        $this->assertEquals($data['date'], $testData->date);
        $this->assertEquals($data['time_played'], $testData->time_played);
        $this->assertEquals($data['remarks'], $testData->remarks);
    }

    /**
     * reusable functions
     */
    protected function createGroup(){
        //create a new group where the logged in user is admin of
        $data = [
            'name' => "A random created Group",
            'description' => "This is a group that is created for php unit testing",
            'admin_id' => $this->loggedInUser->id
        ];

        $response = $this->postJson('/api/group/', $data);
        $this->assertEquals(200, $response->status());
        return $response->getData();
    }

    protected function createSetOfGroupUsers($groupId, $amountOfUsers = 4){
        $dataSet = array('One', 'Two', 'Three', 'Four', 'Five', 'Six');
        for($x = 0; $x < $amountOfUsers; $x++){
            $data = [
                'firstname' => "Person",
                'name' => "Number ".$dataSet[$x],
                'group_id' => $groupId,
            ];
            $this->postJson('/api/group/'.$groupId.'/user', $data);
        }
    }

    protected function createPlayedGameRequest($group, $groupUsers){
        $data['group_id'] = $group->id;
        $data['game_id'] = $this->testData[0]->game_id;
        $data['date'] = '2021-04-26';
        $data['time_played'] = '01:01:00';
        $data['remarks'] = 'AAA';
        //$scoreData['expansion'] = null;

        $groupUserId = $groupUsers->data[1]->id;
        $data['players'][$groupUserId]['group_user_id'] = $groupUserId;
        $data['players'][$groupUserId]['place'] = "";
        $data['players'][$groupUserId]['score'] = 101;
        $data['players'][$groupUserId]['remarks'] = "Group user id: ".$groupUsers->data[1]->full_name;

        $groupUserId = $groupUsers->data[2]->id;
        $data['players'][$groupUserId]['group_user_id'] = $groupUserId;
        $data['players'][$groupUserId]['place'] = "";
        $data['players'][$groupUserId]['score'] = 130;
        $data['players'][$groupUserId]['remarks'] = "Group user id: ".$groupUsers->data[2]->full_name;

        $groupUserId = $groupUsers->data[3]->id;
        $data['players'][$groupUserId]['group_user_id'] = $groupUserId;
        $data['players'][$groupUserId]['place'] = "";
        $data['players'][$groupUserId]['score'] = 114;
        $data['players'][$groupUserId]['remarks'] = "Group user id: ".$groupUsers->data[3]->full_name;

        $groupUserId = $groupUsers->data[4]->id;
        $data['players'][$groupUserId]['group_user_id'] = $groupUserId;
        $data['players'][$groupUserId]['place'] = "";
        $data['players'][$groupUserId]['score'] = 99;
        $data['players'][$groupUserId]['remarks'] = "Group user id: ".$groupUsers->data[4]->full_name;
        return $data;
    }

    public function test_PlayedGamesController_index()
    {
        echo "\n\n---------------------------------------------------------------------------------";
        echo PHP_EOL.PHP_EOL.'[44m Played api Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group/'.$this->testData[0]->group_id.'/played');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(($this->countPlayedGamesInGroup), count($response_data->data));
    }

    public function test_PlayedGameController_show(){
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group/'.$this->testData[0]->group_id.'/played/'.$this->testData[0]->id);
        //$response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
    }

    public function test_PlayedGamesController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $this->createSetOfGroupUsers($newGroup->id);

        $response = $this->get('/api/group/'.$newGroup->id);
        $group = $response->getData();

        $groupUsers =  $this->get('/api/group/'.$newGroup->id.'/users');
        $groupUsers = $groupUsers->getData();

        $data = $this->createPlayedGameRequest($group, $groupUsers);

        $response = $this->postJson('/api/group/'.$group->id.'/played', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($groupUsers->data[2]->id, $response_data->winner_id);
    }

    public function test_PlayedGamesController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();


        $this->createSetOfGroupUsers($newGroup->id);

        $response = $this->get('/api/group/'.$newGroup->id);

        $group = $response->getData();

        $groupUsers =  $this->get('/api/group/'.$newGroup->id.'/users');
        $groupUsers = $groupUsers->getData();

        $data = $this->createPlayedGameRequest($group, $groupUsers);

        $response = $this->postJson('/api/group/'.$group->id.'/played', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($groupUsers->data[2]->id, $response_data->winner_id);

        //write update test
        $nextWinner = $groupUsers->data[3]->id;

        $playedGame = $this->repo->getPlayedGame($response_data->id);

        //dd($playedGame->scores);

        $dataUpdate['group_id'] = $playedGame->group_id;
        $dataUpdate['game_id'] = $playedGame->game_id;
        $dataUpdate['date'] = $playedGame->date;
        $dataUpdate['time_played'] = '01:05:00';
        $dataUpdate['remarks'] = 'BBBB';
        //$dataUpdate['expansion'] = null;

        foreach($playedGame->scores AS $playerScore){
            $groupUserId = $playerScore->group_user_id;
            $dataUpdate['players'][$groupUserId]['id'] = $playerScore->id;
            $dataUpdate['players'][$groupUserId]['group_user_id'] = $groupUserId;
           $dataUpdate['players'][$groupUserId]['place'] = 0;
            $dataUpdate['players'][$groupUserId]['score'] = $playerScore->score;
            $dataUpdate['players'][$groupUserId]['remarks'] = $playerScore->remarks;
        }

        $dataUpdate['players'][$nextWinner]['score']  = 302;

        $response = $this->postJson('/api/group/'.$group->id.'/played/'.$playedGame->id, $dataUpdate);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($nextWinner, $response_data->winner_id);
    }

    public function test_PlayedGamesController_delete()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $this->createSetOfGroupUsers($newGroup->id);

        $response = $this->get('/api/group/'.$newGroup->id);
        $group = $response->getData();

        $groupUsers =  $this->get('/api/group/'.$newGroup->id.'/users');
        $groupUsers = $groupUsers->getData();

        $data = $this->createPlayedGameRequest($group, $groupUsers);

        $response = $this->postJson('/api/group/'.$group->id.'/played', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $response = $this->postJson('/api/group/'.$group->id.'/played/'.$response_data->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals('The played game is removed' , $response_data);
    }
}
