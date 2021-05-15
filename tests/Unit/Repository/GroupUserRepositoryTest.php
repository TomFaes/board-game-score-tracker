<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupUser;
use App\Models\User;
use App\Repositories\GroupUserRepo;


class GroupUserRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $recordCount;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new GroupUserRepo();
        $this->testData  = GroupUser::all();
        $this->recordCount = count($this->testData);
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(GroupUser::class, $testData);
        $this->assertEquals($data['firstname'], $testData->firstname);
        $this->assertEquals($data['name'], $testData->name);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */

    public function test_get_all_group_users()
    {
        echo PHP_EOL.PHP_EOL.'[44m GroupUser Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;
        $found = $this->repo->getAllGroupUsers();
        $this->assertEquals($this->recordCount, count($found));
        //$this->assertEquals(10, 10);
        echo PHP_EOL.'[42m OK  [0m get all group users';
    }

    public function test_get_group_user()
    {
        $found = $this->repo->getGroupUser($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get group user';
    }

    public function test_get_user_with_list_of_created_games()
    {
        $found = $this->repo->getCreatedGames($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get user with list of created games';
    }

    public function test_create_group_user()
    {
        $data = [
            'firstname' => 'Jane',
            'name' => 'Doe',
            'email' => 'test@test.be',
            'group_id' => $this->testData[0]->group_id,
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);

        $found = $this->repo->getAllGroupUsers();
        $this->assertEquals($this->recordCount + 1, count($found));

        echo PHP_EOL.'[42m OK  [0m create group user';
    }

    public function test_update_group_user()
    {
        $data = [
            'firstname' => 'Jane',
            'name' => 'Doe',
            'group_id' => $this->testData[0]->group_id,
        ];

        $updatedGame = $this->repo->update($data, $this->testData[0]->id);
        $this->dataTests($data, $updatedGame);

        echo PHP_EOL.'[42m OK  [0m update group user';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete($this->testData[0]->id);
        $found = $this->repo->getAllGroupUsers();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount - 1), count($found));

        echo PHP_EOL.'[42m OK  [0m delete group user ';
    }

    public function test_create_code_group_user(){
        $code = $this->repo->createCode();

        $this->assertEquals(strlen ($code), 60);

        echo PHP_EOL.'[42m OK  [0m create code group user ';
    }

    public function test_join_group(){
        //create user with code
        $data = [
            'firstname' => 'Jane',
            'name' => 'Doe',
            'email' => 'test@test.be',
            'group_id' => $this->testData[0]->group_id,
        ];
        $newUser = $this->repo->create($data);

        $user = User::all()->random(1)->first();
        $testUser = $this->repo->joinGroup($newUser->code, $user->id);

        $this->assertEquals($testUser->code, null);
        $this->assertEquals($testUser->user_id, $user->id);

        echo PHP_EOL.'[42m OK  [0m Join group ';
    }

    public function test_regenerate_code_for_non_user(){
         //create user with code
         $data = [
            'firstname' => 'Jane',
            'name' => 'Doe',
            'email' => 'test@test.be',
            'group_id' => $this->testData[0]->group_id,
        ];
        $newUser = $this->repo->create($data);

        $regenerateCode = $this->repo->regenerateGroupUserCode($newUser->id);

        $this->assertNotEquals($regenerateCode->code,$newUser->code);

        echo PHP_EOL.'[42m OK  [0m Regenerate group user code ';
    }

    public function test_regenerate_code_with_user_id(){
        //create user with code
        $data = [
           'firstname' => 'Jane',
           'name' => 'Doe',
           'email' => 'test@test.be',
           'group_id' => $this->testData[0]->group_id,
       ];
       $newUser = $this->repo->create($data);

       //join group
       $user = User::all()->random(1)->first();
       $newUser = $this->repo->joinGroup($newUser->code, $user->id);

       $regenerateCode = $this->repo->regenerateGroupUserCode($newUser->id);

       $this->assertEquals($regenerateCode, 'There is still a user connected to this group user');

       echo PHP_EOL.'[42m OK  [0m Regenerate group user code for group user connected to a user ';
   }
}
