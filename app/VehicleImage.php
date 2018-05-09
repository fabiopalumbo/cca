<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleImage  extends Model{

    use SoftDeletes;

    protected $softDelete = true;
    protected $table = 'vehicles_images';
    protected $fillable = array('vehicle_id','url','alt');



}