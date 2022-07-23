<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Http\Requests\GroupRequest;
use App\Repositories\Contracts\IGroup;
use App\Repositories\Contracts\IGroupUser;
use App\Http\Resources\GroupResource;
use App\Models\Group;

class GroupController extends Controller
{
    protected $groupRepo;
    protected $groupUserRepo;

    public function __construct(IGroup $group, IGroupUser $groupUser) {
        $this->groupRepo = $group;

        $this->groupUserRepo = $groupUser;
    }

    public function store(GroupRequest $request)
    {
        $userId = auth()->user()->id;
        $group = $this->groupRepo->create($request->validated(), $userId);

        //when creating the admin of the group is always the logged in user
        $data = [
            'firstname' => auth()->user()->firstname,
            'name' => auth()->user()->name,
            'group_id' => $group->id,
            'user_id' => auth()->user()->id,
        ];
        $groupUser = $this->groupUserRepo->create($data);
        $groupUser = $this->groupUserRepo->joinGroup($groupUser->code, $userId);
        return response()->json(new GroupResource($group), 200);
    }

    public function show(Group $group)
    {
        return response()->json(new GroupResource($group), 200);
    }

    public function update(GroupRequest $request, Group $group)
    {
        $group = $this->groupRepo->update($request->validated(), $group);
        return response()->json(new GroupResource($group), 201);
    }

    public function destroy(Group $group)
    {
        $this->groupRepo->delete($group);
        return response()->json("Group is deleted", 204);
    }
}
