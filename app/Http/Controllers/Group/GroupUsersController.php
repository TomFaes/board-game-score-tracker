<?php

namespace App\Http\Controllers\Group;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Contracts\IGroupUser;
use App\Http\Requests\GroupUserRequest;
use App\Http\Resources\GroupUserCollection;
use App\Http\Resources\GroupUserResource;
use App\Models\Group;
use App\Models\GroupUser;

class GroupUsersController extends Controller
{
     protected $groupUserRepo;

     public function __construct(IGroupUser $groupUser)
     {
        $this->groupUserRepo = $groupUser;
     }

     public function index(Group $group)
     {
         $groupUser = $this->groupUserRepo->getGroupUsers($group->id);
         return response()->json(new GroupUserCollection($groupUser), 200);
     }

    public function store(Group $group, GroupUserRequest $request)
    {
        $groupUser = $this->groupUserRepo->create($request->validated());
        return response()->json(new GroupUserResource($groupUser), 200);
    }

    public function update(GroupUserRequest $request, Group $group, GroupUser $groupUser)
    {
        $groupUser = $this->groupUserRepo->update($request->validated(), $groupUser);
        return response()->json(new GroupUserResource($groupUser), 201);
    }

    public function destroy(Group $group, GroupUser $groupUser)
    {
        $getPlayedGames = $this->groupUserRepo->getGamesCreatedByGroupUser($groupUser->id);

        $playedGameScoreRepo = app('App\Repositories\Contracts\IPlayedGameScore');
        $gamesCreatedByGivenUser = count($getPlayedGames->gameCreator);
        $playedGamesForGivenUser = count($playedGameScoreRepo->getUserPlayedGameScores($groupUser->id));

        if($gamesCreatedByGivenUser > 0){
            $message = "There are ".$gamesCreatedByGivenUser." games connected to this user";
            return response()->json($message, 403);
        }
        if($playedGamesForGivenUser > 0){
            $message = "the player has ".$playedGamesForGivenUser." connected to his name";
            return response()->json($message, 403);
        }

        if($groupUser->user_id == $group->admin_id){
            $message = "The admin cannot be removed from the group";
            return response()->json($message, 403);
        }
        $this->groupUserRepo->delete($groupUser);
        return response()->json("Group user is deleted", 204);
    }

    public function joinGroup(Request $request)
    {
        $userId = auth()->user()->id;
        $groupUser = $this->groupUserRepo->joinGroup($request->code, $userId);
        if(!$groupUser){
            $message['errors']['code'][0] = "Code is not found";
            return response()->json($message, 422);
        }
        return response()->json(new GroupUserResource($groupUser), 201);
    }

    public function regenerateGroupUserCode(Group $group, GroupUser $groupUser)
    {
        $groupUser = $this->groupUserRepo->regenerateGroupUserCode($groupUser);
        return response()->json(new GroupUserResource($groupUser), 201);
    }
}
