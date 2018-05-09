<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesBrandsAddAutocosmosIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_brands',function(Blueprint $table){
            $table->string("autocosmos_id")->after("mercadolibre_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types_vehicles_brands',function(Blueprint $table){
            $table->dropColumn("autocosmos_id");
        });
    }
}
