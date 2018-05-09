<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_notes',function(Blueprint $table){
            $table->increments('id');
            $table->integer('vehicle_id',false,true)->index();
            $table->integer('user_id',false,true)->index();
            $table->string('text');
            $table->timestamps();
            $table->softDeletes()->index();
            $table->foreign("vehicle_id",'vehicleNote_vehicle_foreign')->references('id')->on('vehicles');
            $table->foreign("user_id",'vehicleNote_user_foreign')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles_notes', function(Blueprint $table){
            $table->dropForeign('vehicleNote_vehicle_foreign');
            $table->dropForeign('vehicleNote_user_foreign');
        });
        Schema::dropIfExists('vehicles_notes');
    }
}
