<?php
namespace App;

class MlSession {

    public $access_token;
    public $expires_in;
    public $refresh_token;
    public $user_id;


    function __construct($access_token,$expires_in,$refresh_token,$user_id){
        $this->access_token = $access_token;
        $this->expires_in = $expires_in;
        $this->refresh_token = $refresh_token;
        $this->user_id = $user_id;
    }

} 