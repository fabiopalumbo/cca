<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarDealersUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_dealers_users',function(Blueprint $table){
            $table->increments('id');
            $table->integer('car_dealer_id',false,true)->index();
            $table->integer('user_id',false,true)->index();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->foreign('car_dealer_id','carDealerUser_carDealer_foreign')->references('id')->on('car_dealers');
            $table->foreign('user_id','carDealerUser_user_foreign')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_dealers_users', function(Blueprint $table){
            $table->dropForeign('carDealerUser_user_foreign');
            $table->dropForeign('carDealerUser_carDealer_foreign');
        });
        Schema::dropIfExists('car_dealers_users');
    }
}
