<?php

namespace App\Repositories;



use App\Group;
use App\Module;
use App\Permission;
use App\UserGroup;
use Illuminate\Support\Facades\Auth;

class GroupsAndPermissionRepository
{
    //todo: repensar los default_permission
    public function createModules($name, $permission_edit)
    {
        $module = Module::create(array(
            'name' => $name,
            'list' => $permission_edit['list'],
            'read' => $permission_edit['read'],
            'create' => $permission_edit['create'],
            'update' => $permission_edit['update'],
            'delete' => $permission_edit['delete']
        ));

        $module->save();
        $this->createModulePermissions($module->id);
    }

    private function createModulePermissions($module_id)
    {
        $grupos = Group::all();
        foreach($grupos as $grupo){
            if($grupo->admin){
                Permission::create(array(
                    'group_id' => $grupo->id,
                    'module_id' => $module_id,
                    'list' => 1,
                    'read' => 1,
                    'create' => 1,
                    'update' => 1,
                    'delete' => 1
                ));
            }else{
                if(in_array($module_id,Module::$USER_MODULES)){
		                Permission::create(array(
				                'group_id' => $grupo->id,
				                'module_id' => $module_id,
				                'list' => 1,
				                'read' => 1,
				                'create' => 1,
				                'update' => 1,
				                'delete' => 1
		                ));
                }else{
		                Permission::create(array(
				                'group_id' => $grupo->id,
				                'module_id' => $module_id,
				                'list' => 0,
				                'read' => 0,
				                'create' => 0,
				                'update' => 0,
				                'delete' => 0
		                ));
                }
            }
        }
    }



    public function createGroups($name,$description,$admin)
    {
        $group = Group::create(array(
            'name' => $name,
            'description' => $description,
            'admin' => $admin
        ));
        $this->createGroupPermissions($group);
    }

    private function createGroupPermissions($grupo){
        $modulos = Module::all();
        foreach($modulos as $modulo){
            if($grupo->admin){
                    Permission::create(array(
                        'group_id' => $grupo->id,
                        'module_id' => $modulo->id,
                        'list' => 1,
                        'read' => 1,
                        'create' => 1,
                        'update' => 1,
                        'delete' => 1
                    ));
                }else{
                    Permission::create(array(
                        'group_id' => $grupo->id,
                        'module_id' => $modulo->id,
                        'list' => 1,
                        'read' => 1,
                        'create' => 0,
                        'update' => 0,
                        'delete' => 0
                    ));
                }

        }
    }

	public function deleteGroupsAndModules()
    {
        $ids = array(1,2,3);
        \DB::table('permissions')->whereIn('group_id',$ids)->delete();
        \DB::table('permissions')->whereIn('module_id',array(1,2))->delete();
         sleep(1);
        \DB::table('users_groups')->whereIn('group_id',$ids)->delete();
        sleep(1);
        \DB::table('groups')->whereIn('id',$ids)->delete();
        sleep(1);
        \DB::table('modules')->whereIn('id',array(1,2))->delete();

    }
    public function adminCheck()
    {
        $auth_group = UserGroup::where('user_id','=',Auth::user()->id)->first()->group_id ;
        if(Auth::check() && Group::where('id','=',$auth_group)->
                where('admin','=',1)->
                count()){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteModule($module){
        $modulo = Module::where("name",'=',$module)->first();
        \DB::table('permissions')->whereIn('module_id',$modulo->id)->delete();
        sleep(1);
        Module::find($modulo->id)->delete();
    }
}