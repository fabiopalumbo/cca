<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\User;

class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

}
