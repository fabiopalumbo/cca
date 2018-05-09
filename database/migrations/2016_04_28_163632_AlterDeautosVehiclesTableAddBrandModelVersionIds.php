<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeautosVehiclesTableAddBrandModelVersionIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles_deautos',function(Blueprint $table){
            $table->integer("type_vehicle_brand_id",false,true)->nullable();
            $table->integer("type_vehicle_model_id",false,true)->nullable();
            $table->integer("type_vehicle_version_id",false,true)->nullable();
            $table->foreign('type_vehicle_brand_id','vehicle_deautos_typeVehicleBrand_foreign')->references('id')->on('types_vehicles_brands');
            $table->foreign('type_vehicle_model_id','vehicle_deautos_typeVehicleModel_foreign')->references('id')->on('types_vehicles_models');
            $table->foreign('type_vehicle_version_id','vehicle_deautos_typeVehicleVersion_foreign')->references('id')->on('types_vehicles_versions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_deautos',function(Blueprint $table){
            $table->dropForeign('vehicle_deautos_typeVehicleBrand_foreign');
            $table->dropForeign('vehicle_deautos_typeVehicleModel_foreign');
            $table->dropForeign('vehicle_deautos_typeVehicleVersion_foreign');
            $table->dropColumn("type_vehicle_brand_id");
            $table->dropColumn('type_vehicle_model_id');
            $table->dropColumn('type_vehicle_version_id');
        });
    }
}
