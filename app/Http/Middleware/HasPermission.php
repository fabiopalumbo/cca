<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use Auth;
use DB;
use Illuminate\Database\Query\JoinClause;

class HasPermission
{
    /**
     * @param $request
     * @param callable $next
     * @param $module_id
     * @param $action
     * @return string
     */
    public function handle($request, Closure $next,$module_id,$action)
    {
        if($user = Auth::user()){
            if(Cache::remember('DbMwHasPermissionU'.$user->id.'M'.$module_id.'A'.$action, 10, function() use($module_id,$action,$user){
                return DB::table('users_groups')->
                where('users_groups.user_id','=',$user->id)->
                join('permissions',function(JoinClause $join) use ($module_id,$action){
                    $join->on('permissions.group_id','=','users_groups.group_id');
                    $join->on('permissions.module_id','=',DB::raw($module_id));
                    $join->on('permissions.'.$action,'=',DB::raw(1));
                })->
                count();
            })){
                return $next($request);
            }
        }
        //fix-looepar: deberia retornoar Unauthorized
         return response('Unauthorized.', 401);
    }
}