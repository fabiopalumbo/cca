<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use App\UserPasswordReset;
use \Auth;
use \DB;
use Illuminate\Support\Facades\Input;

class PasswordController extends Controller
{
    public function generate()
    {
        $user = Auth::user();

            UserPasswordReset::create(array(
            'user_id' => $user->id,
            'token' => md5(uniqid(rand(), true))
        ));
        // envio de mail

    }

	//fix-loopear: No están bien establecidas las relaciones, además, se puede hacer con Model
    public function changePass($token)
    {
        if($user = DB::table('users_passwords_resets')->
            where('token','=',$token)->
            whereNull('deleted_at')->get()){
            User::find($user->user_id)-> update(array(
                'password' => \Hash::make(Input::get('password'))
            ));
            return $this->response(200,'OK');
        }
        return $this->response(400,'Error');
    }

}
