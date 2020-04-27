<?php

namespace App\Http\Middleware\Security;

use App\Repositories\PlayedGameRepo;
use Closure;

class PlayedGame
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
        $user = auth()->user();

        $playedGameRepo = app('App\Repositories\Contracts\IPlayedGame');
        $groupRepo = app('App\Repositories\Contracts\IGroup');
        $group = $groupRepo->getGroup($request->group_id);

        //Part 1: if the user is the admin of the group
        if($group->admin_id == $user->id){
            return $next($request);
        }else{
            if($request->route('id') > 0){
                $playedGame = $playedGameRepo->getPlayedGame($request->route('id'));
                //Part 2: The user is the creator of the game
                if($playedGame->creator_id == $user->id){
                    return $next($request);
                } else {
                    //Part 3: the user is not the admin or the creator of the game
                    return response()->json("You are not the admin of this group or the creator of this game", 200);
                }
            }
            return $next($request);
        }
    }
}
