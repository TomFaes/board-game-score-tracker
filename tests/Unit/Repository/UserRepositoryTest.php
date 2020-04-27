<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\User;
use App\Repositories\UserRepo;


class UserRepositoryTest extends TestCase
{
    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] =factory(User::class)->create();
        }

        $this->repo =  new UserRepo();

        //default dataset
        $this->data = [
            'name' => 'Firstname',
            'firstname' => 'Lastname',
            'email' => 'test@test.be',
        ];
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
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_played_game_scores()
    {
        echo PHP_EOL.PHP_EOL.'[43m User Repository Test:   [0m';
        $found = $this->repo->getAllUsers();
        $this->assertEquals(10, count($found));
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
        ];

        $user = $this->repo->create($data);
        $this->dataTests($data, $user);
        echo PHP_EOL.'[42m OK  [0m create user';
    }

    public function test_update_user()
    {

        $data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
        ];

        $user = $this->repo->update($data, $this->testData[0]->id);
        $this->dataTests($data, $user);

        echo PHP_EOL.'[42m OK  [0m update user';
    }

    public function test_forget_user(){
        $forgetUser = $this->repo->forgetUser($this->testData[0]->id);

        $this->assertInstanceOf(User::class, $forgetUser);
        echo PHP_EOL.'[42m OK  [0m forget user test';
    }

    public function test_delete_user()
    {
        $delete = $this->repo->delete($this->testData[0]->id);

        $this->assertTrue($delete);
        echo PHP_EOL.'[42m OK  [0m delete user test';
    }
}
