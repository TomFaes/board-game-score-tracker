<?php

namespace App\Http\Middleware\Security;
use Closure;

class Group
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
        $message = "You do not have the right to change this group";

        $user = auth()->user();

        //if user is admin he can change all groups
        if($user->role == "Admin"){
            if($request->method() != "DELETE"){
                return $next($request);
            }
        }

        if($request->route('id') > 0){
            $group = app('App\Repositories\Contracts\IGroup');
            $group = $group->getGroup($request->route('id'));

            if($group->admin_id == $user->id){
                if($request->method() == "DELETE"){
                    if(count($group->groupUsers) > 1 ||  count($group->groupGames) > 0){
                        $message = "There are still ".count($group->groupGames)." games and ".count($group->groupUsers)." users in this group ";
                    }else{
                        return $next($request);
                    }
                    return response()->json($message, 200);
                }
                return $next($request);
            }
        }

        if($method == 'API'){
            return response()->json($message, 200);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
