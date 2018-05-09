<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesTableRemoveTypeVehicleIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('types_vehicles',function(Blueprint $table){
		    $table->dropColumn('type_vehicle_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('types_vehicles',function(Blueprint $table){
		    $table->integer('type_vehicle_id',false,true)->nullable()->index();
	    });
    }
}
