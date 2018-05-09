<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('group_id',false,true)->index();
	        $table->integer('module_id',false,true)->index();
	        $table->boolean('list');
	        $table->boolean('read');
	        $table->boolean('create');
	        $table->boolean('update');
	        $table->boolean('delete');
            $table->timestamps();

	        $table->index(array('group_id','module_id'));
	        $table->foreign('group_id','permission_group_foreign')->references('id')->on('groups');
	        $table->foreign('module_id','permission_module_foreign')->references('id')->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function(Blueprint $table){
            $table->dropForeign('permission_module_foreign');
            $table->dropForeign('permission_group_foreign');
        });
        Schema::dropIfExists('permissions');
    }
}
