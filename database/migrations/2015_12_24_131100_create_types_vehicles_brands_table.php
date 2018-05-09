<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesVehiclesBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_vehicles_brands',function(Blueprint $table){
            $table->increments('id');
	        $table->integer("type_vehicle_id",false,true);
            $table->string('name');
            $table->string('description');
	        $table->string("mercadolibre_id");
            $table->timestamps();
            $table->softDeletes()->index();

	        $table->foreign('type_vehicle_id','typeVehicleBrand_typeVehicle_foreign')->references('id')->on('types_vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('types_vehicles_brands',function(Blueprint $table){
		    $table->dropForeign('typeVehicleBrand_typeVehicle_foreign');
	    });
        Schema::dropIfExists('types_vehicles_brands');
    }
}
