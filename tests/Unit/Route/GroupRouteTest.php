<?php

namespace Tests\Unit\Route;

use App\Models\Game;
use App\Models\Group;
use App\Models\GroupGame;
use App\Models\User;
use App\Repositories\GroupRepo;
use Tests\TestCase;
use Laravel\Passport\Passport;


class GroupRouteTest extends TestCase
{
    protected $testData;
    protected $recordCount;
    protected $countUserInGroup;

    protected $repo;
    protected $loggedInUser;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new GroupRepo();
        $this->testData = Group::all();
        $this->recordCount = count($this->testData);
        $this->loggedInUser = User::first();

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
    }

    /**
     *  Get authenticated user
     */
    protected function authenticatedUser($role = "Admin"){
        $user = Passport::actingAs(
            $this->loggedInUser,
            ['create-servers']
        );
        $user->role = $role;
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
            'game_id' => $response_data[0]->id,
        ];

        $response = $this->postJson('/api/group/'.$groupId.'/group-game', $data);
        return $response->getData();
    }

    public function test_GroupController_index()
    {
        echo PHP_EOL.PHP_EOL.'[44m Group api Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals(($this->recordCount), count($response_data->data));
        echo PHP_EOL.'[42m OK  [0m GroupController: test index';
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
        echo PHP_EOL.'[42m OK  [0m GroupController: test store';
    }

    public function test_GroupController_show()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group/'.$this->testData[0]->id);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($this->testData[0], $response_data);
        echo PHP_EOL.'[42m OK  [0m GroupController: test show';
    }

    public function test_GroupController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $data = [
            'name' => "A random created Group",
            'description' => "This is a group that is created for php unit testing",
            'admin_id' => $this->loggedInUser->id
        ];

        $response = $this->postJson('/api/group/'. $this->testData[0]->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        $this->dataTests($data, $response_data);
        echo PHP_EOL.'[42m OK  [0m GroupController: test update';
    }

    public function test_GroupController_destroy()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/group/'. $this->testData[0]->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals("Group is deleted", $response_data);
        echo PHP_EOL.'[42m OK  [0m GameController: test delete';
    }

    public function test_UserGroupsController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/user-group');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($this->countUserInGroup, count($response_data));
        echo PHP_EOL.'[42m OK  [0m UserGroupsController: test index';
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
        echo PHP_EOL.'[42m OK  [0m GroupUsersController: test store';
    }

    public function test_GroupUsersController_update()
    {
        $this->be($this->authenticatedUser('Admin'));

        $newGroup = $this->createGroup();
        $newGroupUser = $this->createGroupUser($newGroup->id);

         //update a group user
         $data = [
            'firstname' => "update person",
            'name' => "in this group",
        ];
        $response = $this->postJson('/api/group/'.$newGroup->id.'/user/'.$newGroupUser->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());

        $this->assertEquals($data['firstname'], $response_data->firstname);
        $this->assertEquals($data['name'], $response_data->name);

        echo PHP_EOL.'[42m OK  [0m GroupUsersController: test update';
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

        echo PHP_EOL.'[42m OK  [0m GroupUsersController: test destroy';
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
        $this->assertEquals($response_data->user_id,  $this->loggedInUser->id,);

        echo PHP_EOL.'[42m OK  [0m GroupUsersController: test join group';
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
        echo PHP_EOL.'[42m OK  [0m GroupUsersController: test regenerate group user code';
    }

    public function test_FavoriteUserGroupController_store()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->postJson('/api/group/'.$this->testData[0]->id.'/changeFavoriteGroup');
        $response_data = $response->getData();



        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());

        $this->assertEquals($this->testData[0]->id, $response_data->favorite_group_id);

        echo PHP_EOL.'[42m OK  [0m FavoriteUserGroupController: test update';
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
        $this->assertEquals($countGamesNotInGroup, count($response_data));

        echo PHP_EOL.'[42m OK  [0m GroupGameController: test searchNonGroupGames';
    }

    public function test_GroupGameController_index()
    {
        $this->be($this->authenticatedUser('Admin'));

        $response = $this->get('/api/group/'.$this->testData[0]->id.'/group-game');
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($this->testData[0]->groupGames->count(), count($response_data->data));

        echo PHP_EOL.'[42m OK  [0m GroupGameController: test index';
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
            'game_id' => $response_data[0]->id,
        ];

        $response = $this->postJson('/api/group/'.$newGroup->id.'/group-game', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($data['group_id'], $response_data->group_id);
        $this->assertEquals($data['game_id'], $response_data->game_id);

        echo PHP_EOL.'[42m OK  [0m GroupGameController: test store';
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

        echo PHP_EOL.'[42m OK  [0m GroupGameController: test destroy';
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

        $response = $this->postJson('/api/group/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($data['group_game_id'], $response_data->group_game_id);
        $this->assertEquals($data['name'], $response_data->name);
        $this->assertEquals($data['link'], $response_data->link);
        $this->assertEquals($data['description'], $response_data->description);

        echo PHP_EOL.'[42m OK  [0m GroupGameLinkController: test store';
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

        $response = $this->postJson('/api/group/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $data = [
            'group_game_id' => $response_data->group_game_id,
            'name' => 'An update of a game link',
            'link' => 'www.bing.be',
            'description' => 'a new game description',
        ];

        $response = $this->postJson('/api/group/game/'.$response_data->group_game_id.'/link/'.$response_data->id, $data);
        $response_data = $response->getData();

        $response->assertStatus(201);
        $this->assertEquals(201, $response->status());
        $this->assertEquals($data['group_game_id'], $response_data->group_game_id);
        $this->assertEquals($data['name'], $response_data->name);
        $this->assertEquals($data['link'], $response_data->link);
        $this->assertEquals($data['description'], $response_data->description);

        echo PHP_EOL.'[42m OK  [0m GroupGameLinkController: test update';
    }

    public function test_GroupGameLinkController_destroy()
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

        $response = $this->postJson('/api/group/game/'.$response_data->id.'/link', $data);
        $response_data = $response->getData();

        $data = [
            'group_game_id' => $response_data->group_game_id,
            'name' => 'An update of a game link',
            'link' => 'www.bing.be',
            'description' => 'a new game description',
        ];

        $response = $this->postJson('/api/group/game/'.$response_data->group_game_id.'/link/'.$response_data->id.'/delete');
        $response_data = $response->getData();

        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());
        $this->assertEquals('Link is deleted', $response_data);

        echo PHP_EOL.'[42m OK  [0m GroupGameLinkController: test destroy';
    }
}
