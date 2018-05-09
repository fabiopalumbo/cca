<?php

namespace App\Http\Controllers;

use App\Group;
use App\Module;
use App\Permission;
use App\UserGroup;
use Illuminate\Support\Facades\Input;
use \DB;
use App\User;

class GroupController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:'.Module::GROUP.','.Permission::CREATE, ['only' => ['store']]);
        $this->middleware('permission:'.Module::GROUP.','.Permission::UPDATE, ['only' => ['update','asociar','desasociar']]);
        $this->middleware('permission:'.Module::GROUP.','.Permission::DELETE, ['only' => ['delete']]);
    }

    public function store()
    {
        $grupo = Input::all();

        if(DB::table('groups')->
            where('name','=',$grupo['name'])->
        get()){
             return $this->response(400,'Error');
        }
        Group::create(array(
            'name' => $grupo['name'],
            'description' => $grupo['description'],
            'admin' => $grupo["admin"]
        ));
        $group= Group::where('name','=',$grupo['name'])->first();
        $modulos = Module::all();

        foreach($modulos as $modulo){
            if((int)$group["admin"] == 1 ){
                Permission::create(array(
                    'group_id' => $group->id,
                    'module_id' => (int)$modulo->id,
                    'list' => 1,
                    'read' => 1,
                    'create' => 1,
                    'update' => 1,
                    'delete' => 1
                ));
            }else{
                Permission::create(array(
                    'group_id' => $group->id,
                    'module_id' => (int)$modulo->id,
                    'list' => 0,
                    'read' => 0,
                    'create' => 0,
                    'update' => 0,
                    'delete' => 0
                ));
            }
        }
        return $this->response(200,'OK');
    }

    public function index()
    {
        return $this->response(200,DB::table('groups')->whereNull('deleted_at')->where('id','<>',1)->get());
    }

    public function update($id)
    {
        $group = Input::all();
        if(Group::where('id','=',(int)$id)->
                update(array('name' => $group['name'],'description' => $group['description'],'admin'=>$group['admin']))){
            return $this->response(200,'OK');
        }else return $this->response(400,'Error');

    }

    public function delete($id)
    {
        if(Group::destroy($id)){
            $permission = DB::table("permissions")->
                where('group_id','=',$id)->
                get(array(
                    'permissions.id'
                ));

            $ids = array_map(function($item){
                return (int)$item->id;
            },$permission);
            $ids[] = 0;

             Permission::destroy($ids);
            return $this->response(200,'OK');
        }else{
            return $this->response(400, "Error");
        }
    }

    public function asociate($user_id)
    {
        User::findOrFail($user_id)->
            grupos()->
            sync(Input::get('group_id'));

        return $this->response(
            200,
            true
        );
    }

    public function desasociate($user_id)
    {
        if(UserGroup::where('user_id','=',$user_id)->
            where('group_id','=',Input::get('group_id'))->
            delete()){
                return $this->response(200,'desasociado');
        }
        return $this->response(400,'Error');
    }

    public function changeGroup(){
        if($usergroup = UserGroup::where('user_id',Input::get('user_id'))->first()){
            $usergroup->group_id = Input::get('group_id');
            $usergroup->save();
            return $this->response(200,'OK');
        }
        else{
            return $this->response(400,'Error');

        }


    }

   public function show($id){
        return $this->response(200,Group::find($id));
   }



}