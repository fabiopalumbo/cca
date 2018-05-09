<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleDeMotores extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'vehicles_demotores';
    protected $fillable = array(
        'vehicle_id',
        'publication_id',
        'permalink',
        'type_vehicle_brand_id',
        'type_vehicle_model_id',
        'type_vehicle_version_id'
    );
}