<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VehicleNuestrosAutos extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'vehicles_nuestrosautos';
    protected $fillable = array(
        'vehicle_id',
    );



}