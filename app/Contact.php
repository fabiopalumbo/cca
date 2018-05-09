<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';
    protected $softDelete = true;
    protected $fillable = array(
        'first_name',
        'last_name',
        'dni',
        'cuit',
        'phone',
        'mobile',
        'car_dealer_id',
        'email',
        'contact_type',
        'business_name',
        'address',
        'province',
        'zip_code',
        'birthdate',
        'genre',
        'iva_condition',
        'iibb_condition'
    );

}