<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesVehiclesModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_vehicles_models',function(Blueprint $table){
            $table->increments('id');
	        $table->integer('type_vehicle_brand_id',false,true)->index();
            $table->string('name');
            $table->string('description');
	        $table->string("mercadolibre_id");
            $table->timestamps();
            $table->softDeletes()->index();

	        $table->foreign("type_vehicle_brand_id",'typeVehicleModel_typeVehicleBrand_foreign')->references('id')->on('types_vehicles_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('types_vehicles_models',function(Blueprint $table){
		    $table->dropForeign('typeVehicleModel_typeVehicleBrand_foreign');
	    });
        Schema::dropIfExists('types_vehicles_models');
    }
}
