<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionAutocosmo extends Model
{
    use SoftDeletes;

    protected $table = 'questions_autocosmos';

    protected $fillable = array('query_id,external_id','brand','model','version','email','name','question','answer','date');

}