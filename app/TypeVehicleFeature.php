<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeVehicleFeature extends Model
{
	use SoftDeletes;

    protected $table = 'types_vehicles_features';

    protected $fillable = array(
	    'type_vehicle_id',
        'name',
        'description',
        'mercadolibre_id',
	    'parent_id',
    );

    public function vehicles()
    {
        return $this->belongsToMany(
            'App\\Vehicle',
            'vehicles_features',
            'feature_id',
            'vehicle_id'

        );
    }

	public static $FEATURES = array('SECURITY','CONFORT','SONIDO','EXTERIOR');


}