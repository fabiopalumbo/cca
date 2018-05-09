<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_images',function(Blueprint $table){
            $table->increments('id');
            $table->integer('vehicle_id',false,true)->index();
            $table->string('url');
            $table->string('alt');
            $table->timestamps();
            $table->softDeletes()->index();
            $table->foreign("vehicle_id",'vehicleImage_vehicle_foreign')->references('id')->on('vehicles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_images', function(Blueprint $table){
            $table->dropForeign('vehicleImage_vehicle_foreign');
        });

        Schema::dropIfExists('vehicles_images');
    }
}
