<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Response;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function response($code, $data = array(), $options = 0)
    {
        return Response::json(
            $data,
            $code,
            array(
                'Content-type' => 'application/json'
            ),
            $options
        );
    }
}
