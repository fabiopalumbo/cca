<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_features', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('vehicle_id',false,true)->index();
            $table->integer('type_vehicle_feature_id',false,true)->index();
            $table->timestamps();

	        $table->foreign('vehicle_id','vehicleFeature_vehicle_foreign')->references('id')->on('vehicles');
            $table->foreign('type_vehicle_feature_id','vehicleFeature_typeVehicleFeature_foreign')->references('id')->on('types_vehicles_features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_features',function(Blueprint $table){
            $table->dropForeign('vehicleFeature_typeVehicleFeature_foreign');
            $table->dropForeign('vehicleFeature_vehicle_foreign');
        });
        Schema::dropIfExists("vehicles_features");
    }
}
