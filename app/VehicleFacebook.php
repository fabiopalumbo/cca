<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleFacebook extends Model
{
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'vehicles_facebook';
    protected $fillable = array(
        'vehicle_id',
        'page_id',
        'post_id',
        'permalink'
    );



}