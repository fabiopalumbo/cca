<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales',function(Blueprint $table){
            $table->increments('id');
	        $table->integer('car_dealer_id',false,true)->index();
            $table->integer('vehicle_id',false,true)->index();
            $table->integer('contact_client_id',false,true)->index();
            $table->integer('contact_owner_id',false,true)->index();
            $table->integer('type_sale_state_id',false,true)->index();
            $table->string('details');
            $table->timestamps();
            $table->softDeletes()->index();

            $table->foreign("vehicle_id",'sale_vehicle_foreign')->references('id')->on('vehicles');
            $table->foreign("car_dealer_id",'sale_carDealer_foreign')->references('id')->on('car_dealers');
            $table->foreign("contact_client_id",'sale_contactClientUser_foreign')->references('id')->on('contacts');
            $table->foreign("contact_owner_id",'sale_contactOwnerUser_foreign')->references('id')->on('contacts');
            $table->foreign("type_sale_state_id",'sale_typeSaleState_foreign')->references('id')->on('types_sales_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function(Blueprint $table){
            $table->dropForeign('sale_vehicle_foreign');
            $table->dropForeign('sale_carDealer_foreign');
            $table->dropForeign('sale_contactClientUser_foreign');
            $table->dropForeign('sale_contactOwnerUser_foreign');
            $table->dropForeign('sale_typeSaleState_foreign');
        });
        Schema::dropIfExists('sales');
    }
}
