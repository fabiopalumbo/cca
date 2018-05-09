<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesDeautosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_deautos',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->string("publication_id");
            $table->string("permalink");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vehicle_id','vehicleDeautos_vehicle_foreign')->references('id')->on('vehicles');
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
            $table->dropForeign("vehicleDeautos_vehicle_foreign");
        });
        Schema::dropIfExists("vehicles_deautos");
    }
}
