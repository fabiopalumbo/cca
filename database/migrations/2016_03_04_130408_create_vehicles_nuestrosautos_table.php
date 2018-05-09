<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesNuestrosautosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_nuestrosautos',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vehicle_id','vehicleNuestrosautos_vehicle_foreign')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_nuestrosautos',function(Blueprint $table){
            $table->dropForeign("vehicleNuestrosautos_vehicle_foreign");
        });
        Schema::dropIfExists("vehicles_nuestrosautos");
    }
}
