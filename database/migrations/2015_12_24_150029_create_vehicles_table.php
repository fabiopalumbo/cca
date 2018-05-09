<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('car_dealer_id',false,true)->index();
	        $table->integer('type_vehicle_id',false,true)->index();
	        $table->integer('type_vehicle_brand_id',false,true)->index();
	        $table->integer('type_vehicle_model_id',false,true)->index();
	        $table->integer('type_vehicle_version_id',false,true)->index();
            $table->string('version');
	        $table->string('domain');
            $table->string('year');
	        $table->string('chasis_number');
	        $table->string('kilometers');
            $table->integer('type_vehicle_fuel_id',false,true)->index();
	        $table->integer('type_vehicle_color_id',false,true)->index()->nullable();
	        $table->string('ingress');
            $table->string('glasses');
            $table->string('transmission');
            $table->string('heating');
            $table->string('direction');
	        $table->integer('doors');
            $table->string('buy');
            $table->string('sale_condition');
            $table->string('owner');
            $table->string('details');
	        $table->decimal('price');
	        $table->integer('type_currency_id',false,true)->index();
            $table->timestamps();
            $table->softDeletes()->index();

	        $table->foreign('car_dealer_id','vehicle_carDealer_foreign')->references('id')->on('car_dealers');
	        $table->foreign('type_vehicle_id','vehicle_typeVehicle_foreign')->references('id')->on('types_vehicles');
	        $table->foreign('type_vehicle_brand_id','vehicle_typeVehicleBrand_foreign')->references('id')->on('types_vehicles_brands');
	        $table->foreign('type_vehicle_model_id','vehicle_typeVehicleModel_foreign')->references('id')->on('types_vehicles_models');
	        $table->foreign('type_vehicle_version_id','vehicle_typeVehicleVersion_foreign')->references('id')->on('types_vehicles_versions');
	        $table->foreign('type_vehicle_fuel_id','vehicle_typeVehicleFuel_foreign')->references('id')->on('types_vehicles_fuels');
	        $table->foreign('type_vehicle_color_id','vehicle_typeVehicleColor_foreign')->references('id')->on('types_vehicles_colors');
	        $table->foreign('type_currency_id','vehicle_typeCurrency_foreign')->references('id')->on('types_currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles',function(Blueprint $table){
	        $table->dropForeign('vehicle_carDealer_foreign');
	        $table->dropForeign('vehicle_typeVehicle_foreign');
	        $table->dropForeign('vehicle_typeVehicleBrand_foreign');
	        $table->dropForeign('vehicle_typeVehicleModel_foreign');
	        $table->dropForeign('vehicle_typeVehicleFuel_foreign');
	        $table->dropForeign('vehicle_typeVehicleColor_foreign');
	        $table->dropForeign('vehicle_typeCurrency_foreign');
        });
        Schema::dropIfExists('vehicles');
    }
}
