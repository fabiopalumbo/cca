<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeCurrency extends Model
{
    protected $softDelete = true;
    protected $table = 'types_currencies';
    protected $fillable = array(
        'name'
    );




}