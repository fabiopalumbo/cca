<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleState extends Model
{

    protected $table = 'sales_states';
    protected $fillable = array(
        'sale_id',
        'type_sale_state_id'
    );




}