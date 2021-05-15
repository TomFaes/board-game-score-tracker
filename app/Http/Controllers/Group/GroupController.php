<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use App\Group;
use Illuminate\Http\Request;

use App\Repositories\Contracts\IGroup;
use App\Repositories\Contracts\IGroupUser;


use App\Validators\GroupValidation;

use Auth;

class GroupController extends Controller
{
    /** @var App\Repositories\Contracts\IGroup */
    protected $group;
    /** @var App\Validators\GroupValidation */
    protected $groupValidation;
    /** repo App\Repositories\Contracts\IGroupUser */
    protected $groupUser;


    public function __construct(GroupValidation $groupValidation, IGroup $group, IGroupUser $groupUser) {
        $this->middleware('auth')->only('view');
        $this->middleware('auth:api')->except('view');

        $this->middleware('group:normal')->only('view');
        $this->middleware('group')->except('store');

        $this->groupValidation = $groupValidation;
        $this->group = $group;

        $this->groupUser = $groupUser;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->group->getGroups(20), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //when creating the admin of the group is always the logged in user
        $userId = auth()->user()->id;

        $this->groupValidation->validateGroup($request);
        $group = $this->group->create($request->all(), $userId);

        $data['firstname'] = auth()->user()->firstname;
        $data['name'] = auth()->user()->name;
        $data['email'] = auth()->user()->email;
        $data['group_id'] = $group->id;
        $data['user_id'] = auth()->user()->id;
        $groupUser = $this->groupUser->create($data);
        $groupUser = $this->groupUser->joinGroup($groupUser->code, $userId);

        return response()->json($group, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->group->getGroup($id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->groupValidation->validategroup($request);
        $group = $this->group->update($request->all(), $id);
        return response()->json($group, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->group->delete($id);
        return response()->json("Group is deleted", 204);
    }
}
