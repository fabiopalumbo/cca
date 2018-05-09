<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $table = 'vehicles';

    protected $fillable = array('domain','type_vehicle_brand_id','type_vehicle_model_id','type_vehicle_color_id','year','type_vehicle_version_id','version','type_vehicle_fuel_id','kilometers','sale_condition','owner','type_vehicle_id','direction','heating','transmission','glasses','details','car_dealer_id','chasis_number', 'price','vehicle_image_highlighted_id','type_currency_id','doors','token');

    public function features()
    {
        return $this->belongsToMany(
            'App\\TypeVehicleFeature',
            'vehicles_features',
            'vehicle_id',
            'type_vehicle_feature_id'
        );
    }




}