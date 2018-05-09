<?php
namespace App;

class FbSession {

    public $access_token;

    function __construct($access_token){
        $this->access_token = $access_token;

    }

} 