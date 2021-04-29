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
/*
        //default dataset
        $this->data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
        ];
        */
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

    public function test_get_unverified_users(){
        $user = User::all()->random(1)->first();
        $data = [
            'user_id' => $user['id'],
        ];

        $this->repo->update($data, $this->testData[0]->id);
        $groupUser = $this->repo->getUnverifiedGroupUsers($data['user_id']);
        $this->assertEquals(1, count($groupUser));

        echo PHP_EOL.'[42m OK  [0m get unverified group users ';
    }

    public function test_get_group_based_on_email(){
        $data = [
            'email' => $this->testData[0]->email,
        ];
        $this->repo->update($data, $this->testData[3]->id);
        $this->repo->update($data, $this->testData[4]->id);
        $this->repo->update($data, $this->testData[5]->id);

        $found = $this->repo->getGroupsBasedOnEmail($this->testData[0]->email);
        $this->assertEquals(4, count($found));

        echo PHP_EOL.'[42m OK  [0m get groups based on email ';
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
            'email' => 'test@test.be',
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

    public function test_verify_user(){
        $groupUser = $this->repo->getGroupUser($this->testData[0]->id);
        $this->assertEquals($groupUser->verified, 0);
        $groupUser = $this->repo->verifyUser($this->testData[0]->id);
        $this->assertEquals($groupUser->verified, 1);
        $groupUser = $this->repo->getGroupUser($this->testData[0]->id);
        $this->assertEquals($groupUser->verified, 1);

        echo PHP_EOL.'[42m OK  [0m verify group user ';
    }

    public function test_unverify_user(){
        $groupUser = $this->repo->unverifyUser($this->testData[0]->id);
        $this->assertEquals($groupUser->user_id , null);
        $this->assertEquals($groupUser->email, null);
        $this->assertEquals($groupUser->verified, 0);
        echo PHP_EOL.'[42m OK  [0m unverify group user ';
    }
}
