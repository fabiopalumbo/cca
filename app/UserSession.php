<?php
namespace App;

class UserSession {

    public $id;
    public $admin;
    public $dealers;
    public $groups;

    function __construct($user_id,$is_admin,$dealers,$groups){
        $this->id = $user_id;
        $this->admin = $is_admin;
        $this->dealers = $dealers;
        $this->groups = $groups;
    }

} 