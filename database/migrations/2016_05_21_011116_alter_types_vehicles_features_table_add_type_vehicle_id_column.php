<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesFeaturesTableAddTypeVehicleIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_features',function(Blueprint $table){
	        $table->integer('type_vehicle_id',false,true)->after('id')->nullable();
	        $table->string('mercadolibre_id')->after('description');
	        $table->integer('parent_id',false,true)->after('mercadolibre_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('types_vehicles_features',function(Blueprint $table){
		    $table->dropColumn('type_vehicle_id');
		    $table->dropColumn('mercadolibre_id');
		    $table->dropColumn('parent_id');
	    });
    }
}
