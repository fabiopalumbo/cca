<?php

namespace App\Http\Controllers;


use App\Module;
use App\Permission;
use Illuminate\Support\Facades\Input;

class ModuleController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:'.Module::GROUP.','.Permission::INDEX, ['only' => ['index']]);

    }

    public function store()
    {
        $datos = array(
            'nombre' => Input::get('nombre'),
            'create' => Input::get('create'),
            'read'   => Input::get('read'),
            'update' => Input::get('update'),
            'delete' => Input::get('delete'),
            'list'   => Input::get('list')
        );
        if($id = Input::get('id')){
            Module::where('id','=',$id)->
                update($datos);
            return $this->response(200,'updated');
        }else{
            Module::create($datos);
            return $this->response(200,'saved');
        }
    }

    public function index()
    {
        return Module::all();
    }

    public function show($id)
    {
        //return Module::find($id);
    }

    public function delete($id)
    {
        Permission::where('module_id','=',$id)->destroy();
        Module::destroy($id);
    }

}