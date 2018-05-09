<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('email')->unique()->index();
	        $table->string('first_name');
	        $table->string('last_name');
	        $table->string('phone');
            $table->string('password', 60);
	        $table->string('image_url');
            $table->string('activation_code')->nullable();
	        $table->dateTime('activated_at')->nullable();
            $table->dateTime('login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
