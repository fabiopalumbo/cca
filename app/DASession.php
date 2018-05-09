<?php
namespace App;

class DASession {

    public $access_token;
    public $expires_in;
    public $user;
    public $password;


    function __construct($access_token,$expires_in,$user,$password){
        $this->access_token = $access_token;
        $this->expires_in = $expires_in;
        $this->user = $user;
        $this->password = $password;
    }

} 