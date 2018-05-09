<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $table = 'sent_emails';

    protected $fillable = array(
        'email'
    );

}
