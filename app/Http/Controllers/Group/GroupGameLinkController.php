<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IGroupGameLink;

use Illuminate\Http\Request;

use App\Http\Requests\GroupGameLinkRequest;
use App\Http\Resources\GroupGameLinkResource;

class GroupGameLinkController extends Controller
{
    protected $groupGameLink;

    public function __construct(IGroupGameLink $groupGameLink)
    {
        $this->middleware('auth:api');
        $this->middleware('groupgamelink');

        $this->groupGameLink = $groupGameLink;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupGameLinkRequest $request, $groupGameId)
    {
        $groupGameLink = $this->groupGameLink->create($request->all());
        return response()->json(new GroupGameLinkResource($groupGameLink), 200);
        return response()->json($groupGameLink, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupGameLink  $groupGameLink
     * @return \Illuminate\Http\Response
     */
    public function update(GroupGameLinkRequest $request, $groupGameId, $id)
    {
        $groupGameLink = $this->groupGameLink->update($request->all(), $id);
        return response()->json(new GroupGameLinkResource($groupGameLink), 201);
        return response()->json($groupGameLink, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupGameLink  $groupGameLink
     * @return \Illuminate\Http\Response
     */
    public function destroy($groupGameId, $id)
    {
        $this->groupGameLink->delete($id);
        return response()->json("Link is deleted", 204);
    }
}
