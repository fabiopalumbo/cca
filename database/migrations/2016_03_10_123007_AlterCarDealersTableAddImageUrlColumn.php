<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarDealersTableAddImageUrlColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_dealers',function(Blueprint $table){
            $table->string("image_url")->after("work_hours")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_dealers',function(Blueprint $table){
            $table->dropColumn("image_url");
        });
    }
}
