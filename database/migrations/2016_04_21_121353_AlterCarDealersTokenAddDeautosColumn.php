<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarDealersTokenAddDeautosColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_dealers_tokens',function(Blueprint $table){
            $table->text("deautos")->after("autocosmos");
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
            $table->dropColumn("deautos");
        });
    }
}
