<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicleVersion extends Model
{
	use SoftDeletes;

    protected $table = 'types_vehicles_versions';

    protected $fillable = array(
        'name',
        'description',
        'type_vehicle_model_id',
        'mercadolibre_id',
        'deautos_id',
        'slug'
    );

}