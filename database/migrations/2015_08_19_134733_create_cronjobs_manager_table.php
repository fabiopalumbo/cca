<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobsManagerTable extends Migration{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("cronjobs_manager",function(Blueprint $table){
            $table->increments("id");
            $table->integer("cronjob_id",false,true)->index();
            $table->string("description");
            $table->timestamps();
	        $table->softDeletes()->index();

	        $table->foreign("cronjob_id",'cm_cronjob_foreign')->references('id')->on('cronjobs');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('cronjobs_manager', function(Blueprint $table){
		    $table->dropForeign('cm_cronjob_foreign');
	    });
        Schema::dropIfExists("cronjobs_manager");
    }

}
