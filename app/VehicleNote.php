<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleNote  extends Model{


    protected $table = 'vehicles_notes';
    protected $fillable = array('user_id','vehicle_id','text');



}