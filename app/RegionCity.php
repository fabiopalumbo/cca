<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionCity extends Model
{
    use SoftDeletes;

    protected $table = 'regions_cities';
    protected $softDelete = true;
    protected $fillable = array(
        'name',
        'region_id',
        'mercadolibre_id',
        'deautos_id',
	    'slug',
    );

}