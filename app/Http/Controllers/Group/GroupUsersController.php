<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGroupUser;
use App\Services\GroupService;
use App\Http\Requests\GroupUserRequest;
use App\Http\Resources\GroupUserResource;

class GroupUsersController extends Controller
{
     /** App\Repositories\Contracts\IGroupUser */
     protected $groupUser;

     protected $groupService;

     public function __construct(IGroupUser $groupUser) {
        $this->middleware('auth:api');

        $this->middleware('groupuser')->except('show', 'joinGroup');

        $this->groupUser = $groupUser;

        //Check if the user is already added to a group
        $this->groupService = resolve(GroupService::class);
     }

    public function store(GroupUserRequest $request)
    {
        $groupUser = $this->groupUser->create($request->all());

        //check if the user exist and match the user to the usergroup
        if($groupUser->email != ""){
            $this->groupService->checkExistingUser($groupUser->email);
        }

        return response()->json(new GroupUserResource($groupUser), 200);
    }

    public function update(GroupUserRequest $request, $group, $id)
    {
        $groupUser = $this->groupUser->update($request->all(), $id);

        //check if the user exist and match the user to the usergroup
        if($groupUser->email != ""){
            $this->groupService->checkExistingUser($groupUser->email);
        }
        return response()->json(new GroupUserResource($groupUser), 201);
    }

    public function destroy($group, $id)
    {
        $this->groupUser->delete($id);
        return response()->json("Group user is deleted", 204);
    }

    public function joinGroup(Request $request)
    {
        $userId = auth()->user()->id;

        $groupUser = $this->groupUser->joinGroup($request->code, $userId);
        return response()->json(new GroupUserResource($groupUser), 201);
    }

    public function regenerateGroupUserCode($group, $id)
    {
        $groupUser = $this->groupUser->regenerateGroupUserCode($id);
        return response()->json(new GroupUserResource($groupUser), 201);

    }
}
