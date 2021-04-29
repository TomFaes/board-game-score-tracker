<?php

namespace Tests\Unit\Repository;

use App\Models\Group;
use Tests\TestCase;

use App\Models\User;
use App\Repositories\UserRepo;


class UserRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $records;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo =  new UserRepo();
        $this->testData  = $this->repo->getAllUsers();
        $this->recordCount = count($this->testData);
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(User::class, $testData);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['firstname'], $testData->firstname);
        $this->assertEquals($data['email'], $testData->email);
        $this->assertEquals($data['role'], $testData->role);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_all_users()
    {
        echo PHP_EOL.PHP_EOL.'[44m User Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getAllUsers();
        $this->assertEquals($this->recordCount, count($found));
        echo PHP_EOL.'[42m OK  [0m get all  users';
    }

    public function test_get_user()
    {
        $found = $this->repo->getUser($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);

        echo PHP_EOL.'[42m OK  [0m get user';
    }

    public function test_existing_user()
    {
        $found = $this->repo->existingUser($this->testData[0]->email);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get existing user';
    }

    public function test_create_user()
    {
        $data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
            'role' => "User",
        ];

        $user = $this->repo->create($data);
        $this->dataTests($data, $user);
        echo PHP_EOL.'[42m OK  [0m create user';
    }

    public function test_create_social_user()
    {
        $data = [
            'given_name' => "Test first name",
            'family_name' => "Test name",
            'email' => "test@mail.be",
            'role' => "User",
        ];

        $testData = $this->repo->createSocialUser($data);

        $this->assertInstanceOf(User::class, $testData);
        $this->assertEquals($data['family_name'], $testData->name);
        $this->assertEquals($data['given_name'], $testData->firstname);
        $this->assertEquals($data['email'], $testData->email);
        $this->assertEquals($data['role'], $testData->role);

        echo PHP_EOL.'[42m OK  [0m create socialite user';
    }

    public function test_update_user()
    {
        $data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
            'role' => 'User',
        ];

        $user = $this->repo->update($data, $this->testData[0]->id);
        $this->dataTests($data, $user);

        echo PHP_EOL.'[42m OK  [0m update user';
    }

    public function test_forget_user(){
        $forgetUser = $this->repo->forgetUser($this->testData[0]->id);

        $this->assertInstanceOf(User::class, $forgetUser);
        $this->assertNotEquals($this->testData[0]->name, $forgetUser->name);
        $this->assertNotEquals($this->testData[0]->firstname, $forgetUser->firstname);
        $this->assertNotEquals($this->testData[0]->email, $forgetUser->email);

        echo PHP_EOL.'[42m OK  [0m forget user test';
    }

    public function test_change_favorite_group(){
        $user = $this->repo->changeFavoriteGroup($this->testData[0]->id, Group::first()->id);

        $this->assertEquals($user->favorite_group_id, Group::first()->id);
        echo PHP_EOL.'[42m OK  [0m change favorite group id';
    }

    public function test_delete_user()
    {
        $delete = $this->repo->delete($this->testData[0]->id);
        $found = $this->repo->getAllUsers();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
        echo PHP_EOL.'[42m OK  [0m delete user test';
    }
}
