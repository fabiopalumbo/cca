<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobsTable extends Migration{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("cronjobs",function(Blueprint $table){
            $table->increments("id");
            $table->timestamps();
        });
    }
	/**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
    {
        Schema::dropIfExists("cronjobs");
    }

}
