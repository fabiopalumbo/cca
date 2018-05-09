<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleFeature  extends Model{

    protected $table = 'vehicles_features';

    protected $fillable = array('vehicle_id','feature_id');

    public $timestamps = false;

    protected $softDelete = false;

}