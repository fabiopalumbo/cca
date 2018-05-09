<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiclesTableAddVehicleHighlightImageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles',function(Blueprint $table){
	        $table->integer('vehicle_image_highlighted_id',false,true)->nullable();
	        $table->foreign('vehicle_image_highlighted_id','vehicle_vehicleImageHighlight_foreign')->references('id')->on('vehicles_images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function(Blueprint $table){
	        $table->dropForeign('vehicle_vehicleImageHighlight_foreign');
	        $table->dropColumn('vehicle_image_highlighted_id');
        });
    }
}
