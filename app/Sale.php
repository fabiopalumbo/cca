<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    protected $table = 'sales';
    protected $fillable = array(
        'name',
        'vehicle_id',
        'car_dealer_id',
        'contact_client_id',
        'contact_owner_id',
        'type_sale_state_id',
        'details'
    );




}