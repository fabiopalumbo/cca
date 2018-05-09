<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_groups',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id',false,true)->index();
	        $table->integer('group_id',false,true)->index();
	        $table->timestamps();
	        $table->softDeletes()->index();

	        $table->foreign('user_id','userGroup_user_foreign')->references('id')->on('users');
            $table->foreign('group_id','userGroup_group_foreign')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_groups',function(Blueprint $table){
            $table->dropForeign('userGroup_user_foreign');
            $table->dropForeign('userGroup_group_foreign');
        });
        Schema::dropIfExists('users_groups');
    }
}
