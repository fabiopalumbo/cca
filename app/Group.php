<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
	use SoftDeletes;

    protected $table = 'groups';

    protected $fillable = array('name','description','admin');

    public function usuarios()
    {
        return $this->belongsToMany(
            'App\\User',
            'users_groups',
            'group_id',
            'user_id'

        );
    }
}