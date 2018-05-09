<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleMercadolibre extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'vehicles_mercadolibre';
    protected $fillable = array(
        'vehicle_id',
        'item_id',
        'permalink',
        'mercadolibre_listing_type_id',
        'type_vehicle_brand_id',
        'type_vehicle_model_id',
        'type_vehicle_version_id'
    );



}