<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Http\Resources\GroupCollection;
use App\Repositories\Contracts\IGroup;

class GroupsOfUserController extends Controller
{
    protected $groupRepo;

    public function __construct(IGroup $group)
    {
        $this->groupRepo = $group;
    }

    public function index()
    {
        $userId = auth()->user()->id;
        return response()->json(new GroupCollection($this->groupRepo->getGroupsOfUser($userId)), 200);
    }
}
