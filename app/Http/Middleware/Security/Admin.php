<?php

namespace App\Http\Middleware\Security;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(isset($user) === false){
            return response()->json("You do not have access to this page", 401);
        }

        if($user->role != "Admin"){
            return response()->json("You do not have access to this page", 401);
        }
        return $next($request);
    }
}
