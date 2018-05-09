<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesAutoCosmosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_autocosmos',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->string("external_id");
            $table->string("permalink");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vehicle_id','vehicleAutocosmos_vehicle_foreign')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_autocosmos',function(Blueprint $table){
            $table->dropForeign("vehicleAutoCosmos_vehicle_foreign");
        });
        Schema::dropIfExists("vehicles_autocosmos");
    }
}
