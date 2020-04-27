<?php

namespace App\Http\Middleware\Security;
use Closure;

class GroupUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $method = 'API')
    {
        $statusCode = 200;
        $message = "Group user middleware: You do not have the right to change this group";
        $user = auth()->user();

        $groupRepo = app('App\Repositories\Contracts\IGroup');
        $group = $groupRepo->getGroup($request->route('group_id'));

        $groupUserRepo= app('App\Repositories\Contracts\IGroupUser');
        $groupUser = $groupUserRepo->getGroupUser($request->route('id'));

        if(isset($group) === true){
            //check if  logged in user is the admin of the group
            if($group->admin_id == $user->id){
                //Request method: DELETE
                if($request->method() == "DELETE"){
                    $getPlayedGames = $groupUserRepo->getCreatedGames($groupUser->id);

                    $playedGameScoreRepo = app('App\Repositories\Contracts\IPlayedGameScore');

                    $gameCreator = count($getPlayedGames->gameCreator);
                    $playedscores = count($playedGameScoreRepo->getUserPlayedGameScores($groupUser->id));

                    //A user can only be deleted if he hasn't got any played games
                    if($groupUser->user_id != $group->admin_id  &&  $gameCreator == 0 && $playedscores == 0){
                        return $next($request);
                    }else{
                        $statusCode = 403;
                        if($gameCreator > 0){
                            $message = "Group user middleware: There are ".$gameCreator." games connected to this user";
                        }elseif($playedscores > 0){
                            $message = "Group user middleware: the player has ".$playedscores." connected to his name";
                        }
                        else{
                             $message = "Group user middleware: The admin cannot be removed from the group";
                        }
                    }
                }else{
                    return $next($request);
                }
            }
        }
        if($method == 'API'){
            return response()->json($message, $statusCode);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
