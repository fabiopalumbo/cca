<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;

    protected $table = 'regions';

    protected $fillable = array(
        'name',
        'mercadolibre_id',
        'deautos_id',
	    'slug',
    );

}