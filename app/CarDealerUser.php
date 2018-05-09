<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarDealerUser extends Model
{

    protected $table = 'car_dealers_users';
    protected $fillable = array(
        'car_dealer_id',
        'user_id'
    );



}