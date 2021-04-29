<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;

use App\Models\GroupGameLink;
use App\Models\GroupGame;
use App\Repositories\GroupGameLinkRepo;
use phpseclib\Crypt\Random;

class GroupGameLinkRepositoryTest extends TestCase
{
    protected $testData;
    protected $repo;
    protected $recordCount;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();

        $this->repo = new GroupGameLinkRepo();
        $this->testData  = GroupGameLink::all();
        $this->recordCount = count($this->testData);
    }

    /**
     * Default data test
     */
    protected function dataTests($data, $testData) : void
    {
        $this->assertInstanceOf(GroupGameLink::class, $testData);
        $this->assertEquals($data['group_game_id'], $testData->group_game_id);
        $this->assertEquals($data['name'], $testData->name);
        $this->assertEquals($data['link'], $testData->link);
        $this->assertEquals($data['description'], $testData->description);
    }

    /**
     * A basic Unit test example.
     *
     * @return void
     */

    public function test_get_group_game_links()
    {
        echo PHP_EOL.PHP_EOL.'[44m GroupGameLink Repository Test:   [0m';
        echo PHP_EOL.'[46m Records:   [0m'.$this->recordCount;

        $found = $this->repo->getGroupGameLinks();
        $this->assertEquals($this->recordCount, count($found));

        echo PHP_EOL.'[42m OK  [0m get all group game links';
    }

    public function test_get_group_game_link()
    {
        $found = $this->repo->getGroupGameLink($this->testData[0]->id);
        $this->dataTests($this->testData[0], $found);
        echo PHP_EOL.'[42m OK  [0m get group game link';
    }

    public function test_get_links_of_group_game()
    {
        $found = $this->repo->getLinksOfGroupGame($this->testData[0]->id);

        $linkCount = count(GroupGameLink::with(['groupGame'])->where('group_game_id', $this->testData[0]->id)->get());

        $this->assertEquals($linkCount, count($found));

        echo PHP_EOL.'[42m OK  [0m get all links of  a group game';
    }

    public function test_create_group_game_link()
    {
        $data = [
            'group_game_id' => $this->testData[0]->group_game_id,
            'name' => "a new created link",
            'link' => "www.bing.com",
            'description' => "een omschrijving van wat de link inhoud",
        ];

        $createdData = $this->repo->create($data);
        $this->dataTests($data, $createdData);

        $found = $this->repo->getGroupGameLinks();
        $this->assertEquals($this->recordCount + 1, count($found));

        echo PHP_EOL.'[42m OK  [0m create group game link';
    }

    public function test_update_group_game_link()
    {
        $data = [
            'group_game_id' => $this->testData[1]->group_game_id,
            'name' => "update of a link",
            'link' => "www.bing.com",
            'description' => "een nieuwe omschrijving van wat de link inhoud",
        ];

        $updatedGame = $this->repo->update($data, $this->testData[0]->id);
        $this->dataTests($data, $updatedGame);

        echo PHP_EOL.'[42m OK  [0m update group game link';
    }

    public function test_delete_group_game_link()
    {
        $delete = $this->repo->delete($this->testData[0]->id);
        $found = $this->repo->getGroupGameLinks();

        $this->assertTrue($delete);
        $this->assertEquals(($this->recordCount - 1), count($found));
        echo PHP_EOL.'[42m OK  [0m delete group game link';
    }
}
