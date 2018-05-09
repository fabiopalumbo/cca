<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model{
    use SoftDeletes;

    const DEV = 1;
    const ADMIN = 2;
    const DEALER_ADMIN = 3;
    const DEALER_USER = 4;


    protected $table = 'users_groups';

    protected $fillable = array('user_id','group_id');

}