<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeVehicleColor extends Model
{

    protected $table = 'types_vehicles_colors';
    protected $fillable = array(
        'name',
        'description',
        'color',
        'deautos_id'
    );




}