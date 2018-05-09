<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Redirect;

class ViewUserController extends Controller
{
    public function index(){
        if(!Auth::user()){
            return view('login');
        }else{
            return Redirect::to('admin/index.html');
        }
    }

    public function login(){
        if(!Auth::user()){
            return view('login');
        }else{
            return Redirect::to('admin/index.html');
        }
    }

    public function register(){
        return view('register');
    }

    public function forgotPassword(){
        return view('forgot-password');
    }

    public function contact(){
        return view('contact');
    }

    public function modify(){
        return view ('user/modify-info');
    }

//	public function checkSubdomain($pre,$subdominio,$post){
//		if($centro = Centro::where('url_subdominio',"=",$subdominio)->remember(5)->first()){
//			return Redirect::to('http://'.Config::get("app.pre_domain").".wonoma.".Config::get("app.post_domain")."/#/centro/".$centro->id);
//		}
//		return Redirect::to(Config::get("app.url"));
//	}

}
