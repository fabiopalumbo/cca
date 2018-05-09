<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRegionsCitiesTableAddSlugColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('regions_cities',function(Blueprint $table){
		    $table->string('slug')->after('deautos_id');
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
		    $table->dropColumn('slug');
	    });
    }
}
