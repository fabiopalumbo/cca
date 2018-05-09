<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions_cities',function(Blueprint $table){
            $table->increments("id");
	        $table->integer("region_id",false,true)->index();
	        $table->string("name");
            $table->string("mercadolibre_id");
            $table->timestamps();
            $table->softDeletes();

	        $table->foreign('region_id','regionCity_region_foreign')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regions_cities',function(Blueprint $table){
            $table->dropForeign("regionCity_region_foreign");
        });

        Schema::dropIfExists('regions_cities');
    }
}
