<?php

namespace App;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
	    'email',
	    'username',
	    'first_name',
	    'last_name',
	    'phone',
	    'password',
	    'activation_code',
	    'activated_at',
	    'login_at',
	    'remember_token',
        'original_image_url',
        'cropped_image_url'
    );

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'activation_code',
	    'activated_at',
	    'login_at',
        'password',
        'remember_token'
    );

    public function groups()
    {
        return $this->belongsToMany(
            'App\\Group',
            'users_groups',
            'user_id',
            'group_id'
        );
    }

    public function dealers()
    {
        return $this->belongsToMany(
            'App\\CarDealer',
            'car_dealers_users',
            'user_id',
            'car_dealer_id'
        );
    }
}
