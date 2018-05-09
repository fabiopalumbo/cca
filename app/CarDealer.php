<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarDealer extends Model
{

    protected $table = 'car_dealers';

    protected $fillable = array(
        'name',
        'email',
        'phone',
        'address',
        'zip_code',
        'work_hours',
        'region_id',
        'region_city_id',
        'validated_at',
        'validated_by',
        'partner',
        'company',
        'cuit',
        'last_name',
        'dni_type',
        'dni',
        'location',
        'cellphone',
        'fax',
        'contact',
        'original_image_url',
        'cropped_image_url'
    );



}