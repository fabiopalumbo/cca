<?php

namespace App\Http\Middleware;

use Request;
use Closure;
use Auth;
use DB;
use Illuminate\Database\Query\JoinClause;

class HasAccess
{
    protected $user;

    public function __construct()
    {
        $this->user = session('user');
    }

        /**
     * @param $request
     * @param callable $next
     * @return string
     */
    public function handle($request, Closure $next)
    {
        if($this->user->admin) return $next($request);
        else{
            $dealer_id = $request->route()->getParameter('dealer_id');
            if(in_array($dealer_id,$this->user->dealers)) return $next($request);
        }
        return response('Unauthorized.', 401);
    }
}