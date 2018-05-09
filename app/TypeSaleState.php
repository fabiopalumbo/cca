<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeSaleState extends Model
{

    const PUBLICADO = 1;
    const RESERVADO = 2;
    const VENDIDO =   3;
    const CANCELADO = 4;
    const TRANSFERIDO = 5;
    const PAUSADO = 6;

    protected $table = 'types_sales_states';
    protected $fillable = array(
        'name'
    );




}