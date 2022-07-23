<?php

namespace App\Http\Middleware\Security;

use Closure;
use Illuminate\Http\Request;

class PlayedGameSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $method = 'API')
    {
        $user = auth()->user();

        $group = $request->route('group');
        $playedGame =  $request->route('played');

        if($group->id == null){
            return response()->json("There is no group with that id", 401);
        }

        //Part 1: if the user is the admin of the group
        if($group->admin_id == $user->id){
            return $next($request);
        }
        //Part 2: The user is the creator of the game
        if(isset($playedGame) === true){
            if($request->method() == 'GET'){
                return $next($request);
            }
            if($playedGame->creator_id != $user->id){
                return response()->json("You are not the creator of this played game: ".$playedGame->game->name, 401);
            }
        }
        return $next($request);
    }
}
