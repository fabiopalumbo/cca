<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesFacebookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_facebook',function(Blueprint $table){
            $table->increments("id");
            $table->integer("vehicle_id",false,true)->index();
            $table->string("page_id");
            $table->string("post_id");
            $table->string("permalink");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vehicle_id','vehicleFacebook_vehicle_foreign')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_facebook',function(Blueprint $table){
            $table->dropForeign("vehicleFacebook_vehicle_foreign");
        });
        Schema::dropIfExists("vehicles_facebook");
    }
}
