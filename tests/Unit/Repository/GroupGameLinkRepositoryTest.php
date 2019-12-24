<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupGameLink;
use App\Repositories\GroupGameLinkRepo;
use phpseclib\Crypt\Random;

class GroupGameLinkRepositoryTest extends TestCase
{
    protected $testData;
    protected $data;
    protected $repo;

    public function setUp() : void
    {
        parent::setUp();
        for($x=0;$x<10;$x++){
            $this->testData[] = factory(\App\Models\GroupGameLink::class)->create();
        }
        $this->repo = new GroupGameLinkRepo();

        $this->data = [
            'name' => "linkName",
            'group_game_id' => "2",
            'link' => "testLInk.com",
            'description' => "this is the description",
        ];
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(GroupGameLink::class, $testData);
        $this->assertEquals($data['group_game_id'], $testData->group_game_id);
        $this->assertEquals($data['link'], $testData->link);
        $this->assertEquals($data['description'], $testData->description);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_get_group_game_link()
    {
        echo PHP_EOL.PHP_EOL.'[43m GroupGameLink Repository Test:   [0m';
        $found = $this->repo->getGroupGameLink($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get group game link';
    }

    public function test_get_group_game_links()
    {
        $found = $this->repo->getGroupGameLinks();

        $this->assertEquals(10, count($found));

        echo PHP_EOL.'[42m OK  [0m get all group game links';
    }

    public function test_get_links_of_group_game()
    {
        $found = $this->repo->getLinksOfGroupGame(1);

        $this->assertEquals(10, count($found));

        echo PHP_EOL.'[42m OK  [0m get all links of  a group game';
    }

    public function test_create_group_game_link()
    {

        $testData = $this->repo->create($this->data);
        $this->dataTests($this->data, $testData);

        $found = $this->repo->getLinksOfGroupGame(2);
        $this->assertEquals(1, count($found));

        echo PHP_EOL.'[42m OK  [0m create group game link';
    }


    public function test_update_group_game_link()
    {
        $testData = $this->repo->update($this->data, 1);
        $this->dataTests($this->data, $testData);

        echo PHP_EOL.'[42m OK  [0m update group game link';
    }

    public function test_delete_group_game_link()
    {
        $delete = $this->repo->delete(2);
        $found = $this->repo->getGroupGameLinks();

        $this->assertTrue($delete);
        $this->assertEquals(9, count($found));
        echo PHP_EOL.'[42m OK  [0m delete group game link';
    }
}
