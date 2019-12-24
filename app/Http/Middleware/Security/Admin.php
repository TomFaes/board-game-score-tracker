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
    public function handle($request, Closure $next, $role = "Admin", $method = 'API')
    {
        $user = auth()->user();

        if(isset($user) === true){
            if($user->role == $role){
                return $next($request);
            }
        }
        if($method == 'API'){
            return response()->json("You do not have access to this page", 200);
        }else{
            return redirect('/')->with('error', 'You do not have access to this page');
        }
    }
}
