<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarDealersTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_dealers_tokens',function(Blueprint $table){
            $table->increments("id");
            $table->integer("car_dealer_id",false,true)->index();
            $table->text("mercadolibre");
            $table->text("autocosmos");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('car_dealer_id','car_dealerCAR_DEALERS_TOKENS_car_dealer_foreign')->references('id')->on('car_dealers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_dealers_tokens',function(Blueprint $table){
            $table->dropForeign('car_dealerCAR_DEALERS_TOKENS_car_dealer_foreign');
        });
        Schema::dropIfExists("car_dealers_tokens");
    }
}
