<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarDealersTableRenameCuitColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('car_dealers',function(Blueprint $table){
		    $table->renameColumn('CUIT','cuit');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('car_dealers',function(Blueprint $table){
		    $table->renameColumn('cuit','CUIT');
	    });
    }
}
