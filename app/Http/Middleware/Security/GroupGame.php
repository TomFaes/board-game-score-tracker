<?php

namespace App\Http\Middleware\Security;
use Closure;

class GroupGame
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
        $message = "Group game middleware: You do not have the right to change this group";

        $user = auth()->user();

        $groupGameRepo = app('App\Repositories\Contracts\IGroupGame');
        $groupRepo = app('App\Repositories\Contracts\IGroup');
        $group = $groupRepo->getGroup($request->group_id);

        if($group->admin_id == $user->id){
            //Request method: DELETE
            if($request->method() == "DELETE"){
                //check if there are links match to the game
                $groupGame = $groupGameRepo->getGroupGame($request->route('group_game'));

                //if there are links sent error
                if(count($groupGame->links) > 0){
                    return response()->json("There are still links in this group game", 200);
                }
                return $next($request);
            }
            //Request method: Other
            return $next($request);
        }

        if($method == 'API'){
            return response()->json($message, 200);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
