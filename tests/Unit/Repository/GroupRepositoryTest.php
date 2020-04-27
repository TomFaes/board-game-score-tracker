<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\Group;
use App\Repositories\GroupRepo;


class GroupRepositoryTest extends TestCase
{

    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\Group::class)->create();
        }

        $this->repo = new GroupRepo();

        //default dataset
        $this->data = [
            'name' => "A random created group",
            'description' => "A random created group description",
            'admin_id' => "1",
        ];
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(Group::class, $testData);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['description'], $testData->description);
        $this->assertEquals($data['admin_id'], $testData->admin_id);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_groups()
    {
        echo PHP_EOL.PHP_EOL.'[43m Group Repository Test:   [0m';
        $found = $this->repo->getGroups();
        $this->assertEquals(10, count($found));
        echo PHP_EOL.'[42m OK  [0m get all groups';
    }

    public function test_get_group()
    {
        $found = $this->repo->getgroup($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get group';
    }

    public function test_get_user_groups()
    {
        $changeAdmin = $this->repo->getGroup(1);
        $changeAdmin->admin_id = 2;
        $changeAdmin->save();

        $found = $this->repo->getUserGroups(1);

        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m get all from a user groups';
    }

    public function test_get_played_games()
    {
        $found = $this->repo->getPlayedGames($this->testData[0]->id);
        $this->dataTests($found, $this->testData[0]);
        echo PHP_EOL.'[42m OK  [0m get group';
    }

    public function test_create_group()
    {
        $testData = $this->repo->create($this->data, $this->data['admin_id']);
        $this->dataTests($this->data, $testData);
        echo PHP_EOL.'[42m OK  [0m create group';
    }

    public function test_update_group()
    {
        $group = $this->repo->update($this->data, 1);
        $this->dataTests($this->data, $group);
        echo PHP_EOL.'[42m OK  [0m update game';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getGroups();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete group test';
    }
}
