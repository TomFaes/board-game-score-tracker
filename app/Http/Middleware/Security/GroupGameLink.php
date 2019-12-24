<?php

namespace App\Http\Middleware\Security;
use Closure;

class GroupGameLink
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
        $message = 'Group Game Link middleware: You do not have the right to change this link';

        $user = auth()->user();

        $groupGameRepo = app('App\Repositories\Contracts\IGroupGame');
        $groupGame = $groupGameRepo->getGroupGame($request->route('group_game_id'));

        if($groupGame->group->admin_id == $user->id){
            return $next($request);
        }

        if($method == 'API'){
            return response()->json($message, 200);
        }else{
            return redirect('/')->with('error', $message);
        }
    }
}
