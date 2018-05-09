<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicleFuel extends Model
{
    use SoftDeletes;

    protected $table = 'types_vehicles_fuels';

    protected $fillable = array('id,name');

}