<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QueuePost extends Controller
{
    public function Post(Request $request){
        $data=(json_decode($request->getContent(),true));
        $data['job'] = "App\\Jobs\\".$data['job'];
        $this->dispatch(new $data['job']($data['data']));
        return response('',200);
    }
}