<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_dealers',function(Blueprint $table){
            $table->integer("partner");
            $table->string("company");
            $table->string("CUIT");
            $table->string("last_name");
            $table->string("dni_type");
            $table->string("dni");
            $table->string("location");
            $table->string("cellphone");
            $table->string("fax");
            $table->string("contact");
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
            $table->dropColumn("partner");
            $table->dropColumn("company");
            $table->dropColumn("CUIT");
            $table->dropColumn("last_name");
            $table->dropColumn("dni_type");
            $table->dropColumn("dni");
            $table->dropColumn("location");
            $table->dropColumn("cellphone");
            $table->dropColumn("fax");
            $table->dropColumn("contact");
        });
    }
}
