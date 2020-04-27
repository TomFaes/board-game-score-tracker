<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupUser;
use App\Repositories\GroupUserRepo;


class GroupUserRepositoryTest extends TestCase
{

    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\GroupUser::class)->create();
        }

        $this->repo = new GroupUserRepo();

        //default dataset
        $this->data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
        ];
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(GroupUser::class, $testData);
        $this->assertEquals($data['firstname'], $testData->firstname);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['email'], $testData->email);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_all_group_users()
    {
        $found = $this->repo->getAllGroupUsers();
        $this->assertEquals(10, count($found));
        echo PHP_EOL.'[42m OK  [0m get all group users';
    }

    public function test_get_group_user()
    {
        echo PHP_EOL.PHP_EOL.'[43m GroupUser Repository Test:   [0m';
        $found = $this->repo->getGroupUser($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get group user';
    }

    //getUsersOfGroup
    public function test_get_users_of_group()
    {
        //set some games to base game to a value
        $data = [
            'group_id' => 2,
        ];
        $this->repo->update($data, 4);
        $this->repo->update($data, 5);
        $this->repo->update($data, 6);
        $this->repo->update($data, 7);

        $found = $this->repo->getUsersOfGroup(2);
        $this->assertEquals(4, count($found));
        echo PHP_EOL.'[42m OK  [0m get all users of a group';
    }

    public function test_get_groups_of_user()
    {
        //set some games to base game to a value
        $data = [
            'user_id' => 2,
        ];
        $this->repo->update($data, 4);
        $this->repo->update($data, 6);
        $this->repo->update($data, 7);

        $this->repo->verifyUser(4);
        $this->repo->verifyUser(6);
        $this->repo->verifyUser(7);

        $found = $this->repo->getGroupsOfUser(2);

        $this->assertEquals(3, count($found));
        echo PHP_EOL.'[42m OK  [0m get all groups of a user';
    }

    public function test_get_unverified_users(){
        $this->data = [
            'user_id' => 1
        ];
        $this->repo->update($this->data, 1);
        $groupUser = $this->repo->getUnverifiedGroupUsers(1);
        $this->assertEquals(1, count($groupUser));

        echo PHP_EOL.'[42m OK  [0m get unverified group users ';
    }

    public function test_get_group_based_on_email(){
        $data = [
            'email' => 'test@test.be',
        ];
        $this->repo->update($data, 4);
        $this->repo->update($data, 6);
        $this->repo->update($data, 7);

        $found = $this->repo->getGroupsBasedOnEmail('test@test.be');
        $this->assertEquals(3, count($found));

        echo PHP_EOL.'[42m OK  [0m get groups based on email ';
    }

    public function test_get_created_games()
    {
        echo PHP_EOL.PHP_EOL.'[43m GroupUser Repository Test:   [0m';
        $found = $this->repo->getCreatedGames($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get created games';
    }

    public function test_create_group_user()
    {
        $this->data['group_id'] = 2;

        $testData = $this->repo->create($this->data);
        $this->dataTests($this->data, $testData);
        echo PHP_EOL.'[42m OK  [0m create group user';
    }

    public function test_update_group_user()
    {
        $groupUser = $this->repo->update($this->data, 1);
        $this->dataTests($this->data, $groupUser);
        echo PHP_EOL.'[42m OK  [0m update group user';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getAllGroupUsers();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete group user ';
    }

    public function test_verify_user(){
        $groupUser = $this->repo->getGroupUser(1);
        $this->assertEquals($groupUser->verified, 0);
        $groupUser = $this->repo->verifyUser(1);
        $this->assertEquals($groupUser->verified, 1);

        echo PHP_EOL.'[42m OK  [0m verify group user ';
    }

    public function test_unverify_user(){
        $groupUser = $this->repo->getGroupUser(1);
        $groupUser = $this->repo->unverifyUser($groupUser->id);

        $this->assertEquals($groupUser->user_id , null);
        $this->assertEquals($groupUser->email, null);
        $this->assertEquals($groupUser->verified, 0);

        echo PHP_EOL.'[42m OK  [0m unverify group user ';
    }
}
