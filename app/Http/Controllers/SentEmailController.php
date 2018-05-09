<?php

namespace App\Http\Controllers;

use App\SentEmailStatus;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SentEmailController extends Controller
{
	/**
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		$objects = json_decode(file_get_contents("php://input"));
		foreach($objects as $obj){
			if(property_exists($obj,'token')){
				SentEmailStatus::create(array(
					'sent_email_id' => $obj->token,
					'event' => $obj->event,
					'reason' => property_exists($obj,'reason') ? $obj->reason : '',
					'timestamp' => $obj->timestamp
				));
			}
		}
		return response();
	}
}
