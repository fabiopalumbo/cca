<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiclesMercadolibreTableAddListingTypeIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles_mercadolibre',function(Blueprint $table){
            $table->integer("mercadolibre_listing_type_id",false,true);

            $table->foreign("mercadolibre_listing_type_id",'vehiclesMercadolibre_mercadolibreListingType_foreign')->references('id')->on('mercadolibre_listing_types');
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
            $table->dropForeign('vehiclesMercadolibre_mercadolibreListingType_foreign');

	        $table->dropColumn("mercadolibre_listing_type_id");
        });
    }
}
