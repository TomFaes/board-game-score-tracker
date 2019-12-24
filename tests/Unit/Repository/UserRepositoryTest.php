<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\User;
use App\Repositories\UserRepo;


class UserRepositoryTest extends TestCase
{
    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_user()
    {
        echo PHP_EOL.PHP_EOL.'[43m User Repository Test:   [0m';

        $user = factory(User::class)->create();
        $userRepo = new UserRepo();
        $found = $userRepo->getUser($user->id);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($found->name, $user->name);
        $this->assertEquals($found->firstname, $user->firstname);
        $this->assertEquals($found->email, $user->email);

        echo PHP_EOL.'[42m OK  [0m get user';
    }

    public function test_existing_user()
    {
        $user = factory(User::class)->create();
        $userRepo = new UserRepo();
        $found = $userRepo->existingUser($user->email);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($found->name, $user->name);
        $this->assertEquals($found->firstname, $user->firstname);
        $this->assertEquals($found->email, $user->email);

        echo PHP_EOL.'[42m OK  [0m get existing user';
    }

    public function test_create_user()
    {
        $data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
        ];

        $userRepo = new UserRepo();
        $user = $userRepo->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['firstname'], $user->firstname);
        $this->assertEquals($data['email'], $user->email);

        echo PHP_EOL.'[42m OK  [0m create user';
    }

    public function test_update_user()
    {
        $newUser = factory(User::class)->create();

        $data = [
            'firstname' => "Test first name",
            'name' => "Test name",
            'email' => "test@mail.be",
        ];

        $userRepo = new UserRepo();
        $user = $userRepo->update($data, $newUser->id);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['firstname'], $user->firstname);
        $this->assertEquals($data['email'], $user->email);

        echo PHP_EOL.'[42m OK  [0m update user';
    }

    public function test_delete_user()
    {
        $user = factory(User::class)->create();
        $userRepo = new UserRepo();
        $delete = $userRepo->delete($user->id);

        $this->assertTrue($delete);
        echo PHP_EOL.'[42m OK  [0m delete user test';
    }

    public function test_forget_user(){
        $user = factory(User::class)->create();
        $userRepo = new UserRepo();
        $forgetUser = $userRepo->forgetUser($user->id);

        $this->assertInstanceOf(User::class, $forgetUser);
        echo PHP_EOL.'[42m OK  [0m forget user test';
    }
}
