<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesMercadolibreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_mercadolibre',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->string("item_id");
            $table->string("permalink");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vehicle_id','mercadolibreVehicle_vehicle_foreign')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_mercadolibre',function(Blueprint $table){
            $table->dropForeign("mercadolibreVehicle_vehicle_foreign");
        });
        Schema::dropIfExists("vehicles_mercadolibre");
    }
}
