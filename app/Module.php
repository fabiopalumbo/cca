<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    const USER = 3;
		const GROUP = 1;
		const PERMISSION = 2;
    const STOCK = 4;
    const CONTACTOS = 5;
    const CONCESIONARIA = 6;
    const VENTAS = 7;
    const VINCULACIONES = 8;

		public static $USER_MODULES = array(
			self::STOCK,
			self::CONTACTOS,
			self::VENTAS
		);

    protected $table = 'modules';
    protected $fillable = array('name','list','read','create','update','delete');

}