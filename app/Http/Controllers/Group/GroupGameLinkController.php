<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IGroupGameLink;
use App\Validators\GroupGameLinkValidation;
use Illuminate\Http\Request;

class GroupGameLinkController extends Controller
{
    protected $groupGameLink;
    protected $groupGameLinkValidation;

    public function __construct(IGroupGameLink $groupGameLink, GroupGameLinkValidation $groupGameLinkValidation)
    {
        $this->middleware('auth:api');
        $this->middleware('groupgamelink');

        $this->groupGameLink = $groupGameLink;
        $this->groupGameLinkValidation = $groupGameLinkValidation;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group_game_id)
    {
        $groupGamesLink = $this->groupGameLink->getLinksOfGroupGame($group_game_id);
        return response()->json($groupGamesLink, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $groupGameId)
    {
        $this->groupGameLinkValidation->validateGroupGameLink($request);
        $groupGameLink = $this->groupGameLink->create($request->all());
        return response()->json($groupGameLink, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupGameLink  $groupGameLink
     * @return \Illuminate\Http\Response
     */
    public function show($groupGameId, $id)
    {
        $groupGamesLink = $this->groupGameLink->getGroupGameLink($id);
        return response()->json($groupGamesLink, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupGameLink  $groupGameLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $groupGameId, $id)
    {
        $this->groupGameLinkValidation->validateGroupGameLink($request);
        $groupGameLink = $this->groupGameLink->update($request->all(), $id);
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
