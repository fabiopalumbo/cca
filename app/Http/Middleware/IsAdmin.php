<?php

namespace App\Http\Middleware;

use App\Repositories\GroupsAndPermissionRepository;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        $repo = new GroupsAndPermissionRepository();
        if(Auth::check() && $repo->adminCheck()){
            return $next($request);
        }
        else{
            return redirect('/');
        }
    }
}
