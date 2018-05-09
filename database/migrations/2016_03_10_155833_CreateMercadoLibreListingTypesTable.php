<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMercadoLibreListingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercadolibre_listing_types',function(Blueprint $table){
            $table->increments("id");
            $table->string("name");
            $table->string("description");
            $table->string("mercadolibre_id");
            $table->decimal("price");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("mercadolibre_listing_types");
    }
}
