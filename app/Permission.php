<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	const INDEX = 'list';
	const READ = 'read';
	const CREATE = 'create';
	const UPDATE = 'update';
    const DELETE = 'delete';

    protected $table = 'permissions';

    protected $fillable = array(
        'group_id',
        'module_id',
	    'list',
	    'read',
	    'create',
        'update',
        'delete',
    );
}