<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IGroupGameLink;

use App\Http\Requests\GroupGameLinkRequest;
use App\Http\Resources\GroupGameLinkCollection;
use App\Http\Resources\GroupGameLinkResource;
use App\Models\Group;
use App\Models\GroupGame;
use App\Models\GroupGameLink;

class GroupGameLinkController extends Controller
{
    protected $groupGameLink;

    public function __construct(IGroupGameLink $groupGameLink)
    {
        $this->groupGameLink = $groupGameLink;
    }

    public function index(Group $group, GroupGame $game)
    {
        $groupGameLinks = $this->groupGameLink->getLinksOfGroupGame($game->id);
        return response()->json(new GroupGameLinkCollection($groupGameLinks));
    }

    public function store(GroupGameLinkRequest $request, Group $group,  GroupGame $game)
    {
        $groupGameLink = $this->groupGameLink->create($request->validated());
        return response()->json(new GroupGameLinkResource($groupGameLink), 200);
    }

    public function update(GroupGameLinkRequest $request, Group $group, GroupGame $game, GroupGameLink $link)
    {
        $groupGameLink = $this->groupGameLink->update($request->validated(), $link);
        return response()->json(new GroupGameLinkResource($groupGameLink), 201);
    }

    public function destroy(Group $group, GroupGame $game, GroupGameLink $link)
    {
        $this->groupGameLink->delete($link);
        return response()->json("Link is deleted", 204);
    }
}
