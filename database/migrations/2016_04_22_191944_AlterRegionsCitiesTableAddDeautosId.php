<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRegionsCitiesTableAddDeautosId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regions_cities',function(Blueprint $table){
            $table->string("deautos_id")->after("mercadolibre_id")->nullable();
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
            $table->dropColumn("deautos_id");
        });
    }
}
