<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MercadolibreListingType extends Model
{
    use SoftDeletes;

    protected $table = 'mercadolibre_listing_types';
    protected $softDelete = true;
    protected $fillable = array(
        'name',
        'description',
        'price',
        'mercadolibre_id'
    );

}