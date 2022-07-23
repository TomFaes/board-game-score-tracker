<?php

namespace Tests\Unit\Route;

use App\Models\Game;
use App\Models\Group;
use App\Models\User;
use App\Repositories\GroupRepo;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class GroupRouteTest extends TestCase
{
    protected $testData;
    protected $recordCount;
    protected $countUserInGroup;

    protected $repo;
    protected $loggedInUser;

    protected $newGroupData;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new GroupRepo();
        $this->testData = Group::all();
        $this->recordCount = count($this->testData);
        $this->loggedInUser = User::first();


        $this->repo->update(['admin_id' =>  $this->loggedInUser->id], $this->testData[0]);

        foreach($this->testData AS $group){
            if( $group->admin_id == $this->loggedInUser->id){
                $this->countUserInGroup++;
                continue;
            }
            foreach($group->groupUsers AS $groupUser){
                if($groupUser->user_id == $this->loggedInUser->id){
                    $this->countUserInGroup++;
                    break;
                }
            }
        }

        $this->newGroupData = [
            'name' => "A random created Group",
            'description' => "This is a group that is created for php unit testing",
            'admin_id' => $this->loggedInUser->id
        ];
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
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['description'], $testData->description);
        $this->assertEquals($data['admin_id'], $testData->admin_id);
    }

    /**
     * reusable functions
     */
    protected function createGroup(){
        $response = $this->postJson('/api/group/', $this->newGroupData);
        $this->assertEquals(200, $response->status());
        return $response->getData();
    }

    protected function createGroupUser($groupId){
        $data = [
            'firstname' => "new person",
            'name' => "in this group",
            'group_id' => $groupId,
            'user_id' => $this->loggedInUser->id,
        ];

        $response = $this->postJson('/api/group/'.$groupId.'/user', $data);
        $this->assertEquals(200, $response->status());
        return $response->getData();
    }

    protected function createGroupGame($groupId){
        $response = $this->get('/api/group/'.$groupId.'/search-non-group-games');
        $response_data = $response->getData();

        $data = [
            'group_id' => $groupId,
            'game_id' => $response_data->data[0]->id,
        ];
        $response = $this->postJson('/api/group/'.$groupId.'/group-game', $data);
        return $response->getData();
    }

    public function test_GroupController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $data = [
            'name' => "A random created Group",
            'description' => "This is a group that is created for php unit testing",
            'admin_id' => $this->loggedInUser->id
        ];

        $response = $this->postJson('/api/group/', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($data, $response_data);
    }

    public function test_GroupController_show()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $this->createGroupUser($newGroup->id);

        $response = $this->get('/api/group/'.$newGroup->id);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($this->newGroupData, $response_data);
    }

    public function test_GroupController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $this->createGroupUser($newGroup->id);

        $data = [
            'name' => "An updated Group",
            'description' => "This is a group has been updated with phpunit",
            'admin_id' => $this->loggedInUser->id
        ];

        $response = $this->postJson('/api/group/'. $newGroup->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        $this->dataTests($data, $response_data);
    }

    public function test_GroupController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $this->createGroupUser($newGroup->id);

        $response = $this->postJson('/api/group/'. $newGroup->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals("Group is deleted", $response_data);
    }

    public function test_GroupsOfUserController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/user-group');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($this->countUserInGroup, count($response_data->data));
    }

    public function test_GroupUsersController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();

        //create a new group user
        $data = [
            'firstname' => "new person",
            'name' => "in this group",
            'email' => $this->loggedInUser->email,
            'group_id' => $newGroup->id,
            'user_id' => $this->loggedInUser->id,
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/user', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $this->assertEquals($data['firstname'], $response_data->firstname);
        $this->assertEquals($data['name'], $response_data->name);
    }

    public function test_GroupUsersController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $newGroupUser = $this->createGroupUser($newGroup->id);

         //update a group user
         $data = [
             'group_id' => $newGroup->id,
            'firstname' => "update person",
            'name' => "in this group",
        ];
        $response = $this->postJson('/api/group/'.$newGroup->id.'/user/'.$newGroupUser->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());

        $this->assertEquals($data['firstname'], $response_data->firstname);
        $this->assertEquals($data['name'], $response_data->name);
    }

    public function test_GroupUsersController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $newGroupUser = $this->createGroupUser($newGroup->id);

        $response = $this->postJson('/api/group/'.$newGroup->id.'/user/'.$newGroupUser->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals("Group user is deleted", $response_data);
    }

    public function test_GroupUsersController_joinGroup()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();

        //create a new group user
        $data = [
            'firstname' => "new person",
            'name' => "in this group",
            'group_id' => $newGroup->id,
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/user', $data);
        $response_data = $response->getData();

        $data = [
            'code' => $response_data->code,
        ];
        $response = $this->postJson('/api/join_group', $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());

        $this->assertEquals($response_data->code, null);
        $this->assertEquals($response_data->user_id,  $this->loggedInUser->id);
    }


    public function test_GroupUsersController_regenerateGroupUserCode()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();

        //create a new group user
        $data = [
            'firstname' => "new person",
            'name' => "in this group",
            'group_id' => $newGroup->id,
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/user', $data);
        $response_data = $response->getData();

        $originalCode = $response_data->code;

        $response = $this->postJson('/api/group/'.$newGroup->id.'/user/'.$response_data->id.'/regenerate_code');
        $response_data = $response->getData();

        $this->assertNotEquals($response_data->code,$originalCode);
    }

    public function test_FavoriteUserGroupController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/group/'.$this->testData[0]->id.'/changeFavoriteGroup');
        $response_data = $response->getData();



        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());

        $this->assertEquals($this->testData[0]->id, $response_data->favorite_group_id);
    }

    public function test_GroupGameController_searchNonGroupGames()
    {
        //first get the games in a group
        $this->be($this->authenticatedUser('Admin'));

        $games = Game::all();

        $response = $this->get('/api/group/'.$this->testData[0]->id.'/group-game');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($this->testData[0]->groupGames->count(), count($response_data->data));

        $countGamesNotInGroup = count($games) - count($response_data->data);

        //test the search for non group games
        $response = $this->get('/api/group/'.$this->testData[0]->id.'/search-non-group-games');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($countGamesNotInGroup, count($response_data->data));
    }

    public function test_GroupGameController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group/'.$this->testData[0]->id.'/group-game');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($this->testData[0]->groupGames->count(), count($response_data->data));
    }

    public function test_GroupGameController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        //create a new group where the logged in user is admin of
        $newGroup = $this->createGroup();

        $response = $this->get('/api/group/'.$newGroup->id.'/search-non-group-games');
        $response_data = $response->getData();

        $data = [
            'group_id' => $newGroup->id,
            'game_id' => $response_data->data[0]->id,
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/group-game', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($data['group_id'], $response_data->group_id);
        $this->assertEquals($data['game_id'], $response_data->game_id);
    }

    public function test_GroupGameController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));
        //create a new group where the logged in user is admin of
        $newGroup = $this->createGroup();

        $response_data = $this->createGroupGame($newGroup->id);

        $response = $this->postJson('/api/group/'.$newGroup->id.'/group-game/'.$response_data->id.'/delete');
        $response_data = $response->getData();

        $this->assertEquals($response_data, "Group game is deleted");
    }

    public function test_GroupGameLinkController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        //create a new group where the logged in user is admin of
        $newGroup = $this->createGroup();
        $response_data = $this->createGroupGame($newGroup->id);

        $data = [
            'group_game_id' => $response_data->id,
            'name' => 'A new game link',
            'link' => 'www.google.be',
            'description' => 'a game description',
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($data['group_game_id'], $response_data->group_game_id);
        $this->assertEquals($data['name'], $response_data->name);
        $this->assertEquals($data['link'], $response_data->link);
        $this->assertEquals($data['description'], $response_data->description);
    }

    public function test_GroupGameLinkController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        //create a new group where the logged in user is admin of
        $newGroup = $this->createGroup();
        $response_data = $this->createGroupGame($newGroup->id);

        $data = [
            'group_game_id' => $response_data->id,
            'name' => 'A new game link',
            'link' => 'www.google.be',
            'description' => 'a game description',
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $data = [
            'group_game_id' => $response_data->group_game_id,
            'name' => 'An update of a game link',
            'link' => 'www.bing.be',
            'description' => 'a new game description',
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/game/'.$response_data->group_game_id.'/link/'.$response_data->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        $this->assertEquals($data['group_game_id'], $response_data->group_game_id);
        $this->assertEquals($data['name'], $response_data->name);
        $this->assertEquals($data['link'], $response_data->link);
        $this->assertEquals($data['description'], $response_data->description);
    }

    public function test_GroupGameLinkController_destroy()
    {
        $this->markTestIncomplete(
            'This test is unstable.'
          );
        $this->be($this->authenticatedUser('Admin'));

        //create a new group where the logged in user is admin of
        $newGroup = $this->createGroup();
        $response_data = $this->createGroupGame($newGroup->id);

        $data = [
            'group_game_id' => $response_data->id,
            'name' => 'A new game link',
            'link' => 'www.google.be',
            'description' => 'a game description',
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $data = [
            'group_game_id' => $response_data->group_game_id,
            'name' => 'An update of a game link',
            'link' => 'www.bing.be',
            'description' => 'a new game description',
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/game/'.$response_data->group_game_id.'/link/'.$response_data->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals('Link is deleted', $response_data);
    }
}
