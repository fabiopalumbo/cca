<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarDealerToken extends Model
{

    protected $table = 'car_dealers_tokens';

    protected $fillable = array(
        'car_dealer_id',
        'mercadolibre',
        'autocosmoss'
    );



}