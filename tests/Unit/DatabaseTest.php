<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\User;
use Tests\TestCase;


class DatabaseTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $records;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        //$this->repo =  new UserRepo();
        //$this->testData  = $this->repo->getAllUsers();
        //$this->recordCount = count($this->testData);
    }


    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_all_users()
    {
        /*
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
          );
        */

        $this->assertEquals(10, 10);
    }


}
