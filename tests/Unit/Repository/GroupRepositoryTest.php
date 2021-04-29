<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\Group;
use App\Repositories\GroupRepo;


class GroupRepositoryTest extends TestCase
{

    protected $testData;
    protected $repo;
    protected $recordCount;
    protected $countUserInGroup;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo =  new GroupRepo();
        $this->testData  = $this->repo->getGroups();
        $this->recordCount = count($this->testData);

        foreach($this->testData AS $group){
            if( $group->admin_id == $this->testData[0]->admin_id){
                $this->countUserInGroup++;
                continue;
            }
            foreach($group->groupUsers AS $groupUser){
                if($groupUser->user_id == $this->testData[0]->admin_id && $groupUser->verified == 1){
                    $this->countUserInGroup++;
                    break;
                }
            }
        }
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
        echo PHP_EOL.PHP_EOL.'[44m Group Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getGroups();
        $this->assertEquals($this->recordCount, count($found));
        $this->assertEquals(10, 10);
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
        $found = $this->repo->getUserGroups($this->testData[0]->admin_id);

        $this->assertEquals($this->countUserInGroup, count($found));
        echo PHP_EOL.'[42m OK  [0m get all from a user groups';
    }

    public function test_create_group()
    {
        $data = [
            'name' => "A random created group",
            'description' => "test test",
            'admin_id' => $this->testData[0]->admin_id
        ];

        $createdData = $this->repo->create($data, $this->testData[0]->admin_id);
        $this->dataTests($data, $createdData);

        echo PHP_EOL.'[42m OK  [0m create group';
    }

    public function test_update_group()
    {
        $data = [
            'name' => "A random created group",
            'description' => "test test",
            'admin_id' => $this->testData[0]->admin_id
        ];

        $group = $this->repo->update($data, $this->testData[0]->id);
        $this->dataTests($data, $group);
        echo PHP_EOL.'[42m OK  [0m update game';
    }

    public function test_delete_game()
    {
        $delete = $this->repo->delete($this->testData[0]->id);
        $found = $this->repo->getGroups();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount-1), count($found));
        echo PHP_EOL.'[42m OK  [0m delete group test';
    }
}
