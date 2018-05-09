<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleAutocosmos extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'vehicles_autocosmos';
    protected $fillable = array(
        'vehicle_id',
        'external_id',
        'permalink',
        'type_vehicle_brand_id',
        'type_vehicle_model_id',
        'type_vehicle_version_id'
    );



}