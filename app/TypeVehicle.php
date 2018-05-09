<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicle extends Model
{
    use SoftDeletes;

    protected $table = 'types_vehicles';

    protected $fillable = array(
	    'name',
	    'mercadolibre_id'
    );

	CONST AUTOS_CAMIONETAS = 2;
	CONST CAMIONES = 3;

}