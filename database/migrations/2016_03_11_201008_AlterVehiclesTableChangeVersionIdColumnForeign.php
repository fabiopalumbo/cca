<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiclesTableChangeVersionIdColumnForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles',function(Blueprint $table){
            $table->dropForeign('vehicle_typeVehicleVersion_foreign');
            sleep(1);
            $table->integer("type_vehicle_version_id",false,true)->nullable()->change();
            $table->foreign('type_vehicle_version_id','vehicle_typeVehicleVersion_foreign')->references('id')->on('types_vehicles_versions');

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
            $table->dropForeign('vehicle_typeVehicleVersion_foreign');
            sleep(1);
            $table->integer("type_vehicle_version_id",false,true)->change();
            $table->foreign('type_vehicle_version_id','vehicle_typeVehicleVersion_foreign')->references('id')->on('types_vehicles_versions');
        });
    }
}
