<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMercadolibreIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_brands',function(Blueprint $table){
            $table->string("mercadolibre_id")->nullable()->change();
        });
        Schema::table('types_vehicles_models',function(Blueprint $table){
            $table->string("mercadolibre_id")->nullable()->change();
        });
        Schema::table('types_vehicles_versions',function(Blueprint $table){
            $table->string("mercadolibre_id")->nullable()->change();
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
            $table->string("mercadolibre_id")->change();
        });
        Schema::table('types_vehicles_models',function(Blueprint $table){
            $table->string("mercadolibre_id")->change();
        });
        Schema::table('types_vehicles_versions',function(Blueprint $table){
            $table->string("mercadolibre_id")->change();
        });
    }
}
