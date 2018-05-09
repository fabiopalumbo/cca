<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts',function(Blueprint $table){
            $table->increments('id');
	        $table->integer('car_dealer_id',false,true)->index();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('contact_type');
            $table->string('business_name')->nullable();
	        $table->string('cuit')->nullable();
	        $table->string('phone');
	        $table->string('mobile')->nullable();
	        $table->string('email');
	        $table->string('dni')->nullable();
            $table->string('address');
            $table->string('province');
            $table->string('zip_code');
            $table->dateTime('birthday')->nullable();
            $table->string('genre')->nullable();
            $table->string('iva_condition')->nullable();
            $table->string('iibb_condition')->nullable();
	        $table->timestamps();
	        $table->softDeletes()->index();

	        $table->foreign('car_dealer_id','contact_carDealer_foreign')->references('id')->on('car_dealers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts',function(Blueprint $table){
            $table->dropForeign('contact_carDealer_foreign');
        });
        Schema::dropIfExists('contacts');
    }
}
