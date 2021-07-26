<?php

namespace Tests\Unit\Route;

use Tests\TestCase;
use App\Models\User;
//use App\Repositories\UserRepo;
use Laravel\Passport\Passport;


class UserRouteTest extends TestCase
{
    protected $allUsers;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
        $this->allUsers = User::all();
    }

    /**
     *  Get authenticated user
     */
    protected function authenticatedUser($role = "Admin"){
        $user = Passport::actingAs(
            $this->allUsers[0],
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
        $this->assertEquals($data['name'], $testData['name']);
        $this->assertEquals($data['firstname'], $testData['firstname']);
        $this->assertEquals($data['email'], $testData['email']);
    }

    public function test_ProfileController_index()
    {
        echo PHP_EOL.PHP_EOL.'[44m Profile api Test:   [0m';

        $this->be($this->authenticatedUser('User'));

        $response = $this->get('/api/profile');
        $response_data = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($this->allUsers[0], $response_data);

        echo PHP_EOL.'[42m OK  [0m test index in the ProfileController';
    }

    public function test_ProfileController_update()
    {
        $this->be($this->authenticatedUser('User'));

        $data = [
            'firstname' => 'Firstname',
            'name' => 'Lastname',
            'email' => 'test@test.be',
        ];

        $response = $this->postJson('/api/profile/', $data);
        $response_data = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(200, $response->status());
        $this->dataTests($data, $response_data);

        echo PHP_EOL.'[42m OK  [0m test update method in the ProfileController';
    }

    public function test_ProfileController_destroy()
    {
        $this->be($this->authenticatedUser('User'));

        //a user is not really deleted but completly randomized
        $response = $this->postJson('/api/profile/delete');
        $response->assertStatus(204);
        $this->assertEquals(204, $response->status());

        //get the delete user
        $response = $this->get('/api/profile');
        $response_data = $response->decodeResponseJson();

        $this->assertNotEquals($response_data['firstname'], $this->allUsers[0]->firstname);
        $this->assertNotEquals($response_data['name'], $this->allUsers[0]->name);
        $this->assertEquals($response_data['role'], $this->allUsers[0]->role);
        $this->assertNotEquals($response_data, $this->allUsers[0]->email);

        echo PHP_EOL.'[42m OK  [0m test destroy method in the ProfileController';
    }
}
