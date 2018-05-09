<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPasswordReset extends Model
{
	use SoftDeletes;

    public $timestamps = true;

    protected $table = 'users_passwords_resets';

    protected $fillable = array('user_id','token');

}