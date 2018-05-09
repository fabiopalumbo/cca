<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicleBrand extends Model
{
	use SoftDeletes;

    protected $table = 'types_vehicles_brands';

    protected $fillable = array(
        'name',
        'description',
        'mercadolibre_id',
        'autocosmos_id',
        'deautos_id',
        'type_vehicle_id',
        'slug'
    );

}