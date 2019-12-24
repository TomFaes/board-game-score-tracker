<?php

namespace App\Http\Middleware\Security;
use Closure;

class Game
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
        $message = "You do not have the right to change this game";

        $user = auth()->user();

         //if user is admin he can change all groups, to delete a grouip there are extra rules
         if($user->role == "Admin"){
            if($request->method() != "DELETE"){
                return $next($request);
            }else{
                if($request->route('id') > 0){
                    $game = app('App\Repositories\Contracts\IGame');
                    $game = $game->getGame($request->route('id'));

                    if(count($game->expansions) > 0 || count($game->groupGames) > 0){
                        $message = "there are still ".count($game->expansions)." expansions and ".count($game->groupGames)." group games connected to this game";
                    }else{
                        return $next($request);
                    }
                }
            }
        }

        if($method == 'API'){
            return response()->json($message, 200);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
