<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicleModel extends Model
{
	use SoftDeletes;

    protected $table = 'types_vehicles_models';
    protected $fillable = array(
        'name',
        'description',
        'type_vehicle_brand_id',
        'mercadolibre_id',
        'autocosmos_id',
        'deautos_id',
        'slug'
    );

}