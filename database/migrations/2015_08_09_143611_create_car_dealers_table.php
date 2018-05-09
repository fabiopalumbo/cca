<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_dealers',function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone');
            $table->string('address');
	        $table->string('zip_code');
            $table->string('work_hours');
	        $table->integer('region_id',false,true)->index();
	        $table->integer('region_city_id',false,true)->index();
            $table->dateTime('validated_at')->nullable();
            $table->integer('validated_by',false,true)->nullable()->index();
            $table->timestamps();
            $table->softDeletes()->index();

	        $table->foreign('region_id','carDealer_region_foreign')->references('id')->on('regions');
	        $table->foreign('region_city_id','carDealer_regionCity_foreign')->references('id')->on('regions_cities');
            $table->foreign('validated_by','carDealer_validatedBy_foreign')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_states', function(Blueprint $table){
            $table->dropForeign('carDealer_region_foreign');
            $table->dropForeign('carDealer_regionCity_foreign');
            $table->dropForeign('carDealer_validatedBy_foreign');
        });
        Schema::dropIfExists('car_dealers');
    }
}
