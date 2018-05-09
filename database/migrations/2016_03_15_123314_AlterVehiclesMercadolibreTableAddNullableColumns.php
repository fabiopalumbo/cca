<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiclesMercadolibreTableAddNullableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles_mercadolibre',function(Blueprint $table){
            $table->string("item_id")->nullable()->change();
            $table->string("permalink")->nullable()->change();
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
        $table->string("item_id")->change();
        $table->string("permalink")->change();
        });
    }
}
