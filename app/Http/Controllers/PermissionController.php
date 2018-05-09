<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Input;
use Response;
use \DB;
use App\Module;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.Module::PERMISSION.','.Permission::UPDATE, ['only' => ['updatePermiso']]);
        $this->middleware('permission:'.Module::PERMISSION.','.Permission::INDEX, ['only' => ['index']]);

    }


    public function store()
    {
        $datos = array(
            'group_id' => Input::get('group_id'),
            'module_id'=> Input::get('module_id'),
            'create' => Input::get('create'),
            'read'   => Input::get('read'),
            'update' => Input::get('update'),
            'delete' => Input::get('delete'),
            'list'   => Input::get('list')
        );
        if($id = Input::get('id')){
            Permission::where('id','=',$id)->
                update($datos);
            return $this->response(200, 'updated');
        }else{
            Permission::create($datos);
            return $this->response(200,'saved');
        }
    }

    public function index()
    {
        return $this->response(200, DB::table('permissions')
            ->where(function($query){
                if($modulo_id = Input::get('modulo_id')){
                    $query->where("permissions.module_id",'=',$modulo_id);
                }
                if($grupo_id = Input::get('grupo_id')){
                    $query->where('permissions.group_id','=',$grupo_id);
                }
            })->join('modules',function(JoinClause $join){
                $join->on('modules.id','=','permissions.module_id');
            })->join('groups',function(JoinClause $join){
                $join->on('groups.id','=','permissions.group_id');
            })->where('groups.id','<>',1)->
            groupBy('permissions.module_id')->
            groupBy('permissions.group_id')->
            select(array(
                'permissions.id',
                'permissions.read',
                'permissions.create',
                'permissions.update',
                'permissions.delete',
                'permissions.list',
                'modules.read as read_edit',
                'modules.create as create_edit',
                'modules.update as update_edit',
                'modules.delete as delete_edit',
                'modules.list as list_edit',
                'modules.name as module_name',
                'groups.name as group_name'
                //'modules.active'
            ))
            ->get());
    }

    public function updatePermiso(){
        $permissions = Input::get("data");
        foreach($permissions as $permission) {
            $a = Permission::find($permission['id']);
            $a->read = (int)$permission["read"];
            $a->create = (int)$permission["create"];
            $a->update = (int)$permission["update"];
            $a->delete = (int)$permission["delete"];
            $a->list = (int)$permission["list"];
            if(!$a->save()) return $this->response(200,'Error');
        }
        return $this->response(200,'OK');
    }
}