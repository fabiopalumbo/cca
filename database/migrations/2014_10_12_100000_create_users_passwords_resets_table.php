<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPasswordsResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_passwords_resets', function (Blueprint $table) {
	        $table->increments('id');
            $table->integer('user_id',false,true)->index();
            $table->string('token')->index();
	        $table->timestamps();
            $table->softDeletes();

	        $table->foreign("user_id",'userPasswordReset_user_foreign')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('users_passwords_resets',function(Blueprint $table){
		    $table->dropForeign('userPasswordReset_user_foreign');
	    });
        Schema::dropIfExists('users_passwords_resets');
    }
}
