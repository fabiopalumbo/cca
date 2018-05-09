<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesDemotoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_demotores',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->string("publication_id");
            $table->string("permalink");
            $table->integer("type_vehicle_brand_id",false,true)->nullable();
            $table->integer("type_vehicle_model_id",false,true)->nullable();
            $table->integer("type_vehicle_version_id",false,true)->nullable();
            $table->timestamps();
            $table->softDeletes();

            /*$table->foreign('type_vehicle_brand_id','vehiclesDemotores_brand_foreign')->references('id')->on('types_vehicles_brands');
            $table->foreign('type_vehicle_model_id','vehiclesDemotores_model_foreign')->references('id')->on('types_vehicles_models');
            $table->foreign('type_vehicle_version_id','vehiclesDemotores_version_foreign')->references('id')->on('types_vehicles_versions');
            $table->foreign('vehicle_id','vehiclesDemotores_vehicle_foreign')->references('id')->on('vehicles');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_demotores',function(Blueprint $table){
            $table->dropForeign("vehiclesDemotores_vehicle_foreign");
            $table->dropForeign('vehiclesDemotores_brand_foreign');
            $table->dropForeign('vehiclesDemotores_model_foreign');
            $table->dropForeign('vehiclesDemotores_version_foreign');
        });
        Schema::dropIfExists("vehicles_demotores");
    }
}
