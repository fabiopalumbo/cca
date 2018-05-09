<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentEmailsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
    {
        Schema::create("sent_emails_status",function(Blueprint $table){
            $table->increments('id');
	        $table->integer('sent_email_id',false,true)->index();
	        $table->string('event');
	        $table->string('reason');
	        $table->integer('timestamp');
	        $table->timestamps();

	        $table->foreign('sent_email_id','sentEmailStatus_sentEmail_foreign')->references('id')->on('sent_emails');
        });
    }

	/**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
    {
	    Schema::table('sent_emails_status',function(Blueprint $table){
		    $table->dropForeign('sentEmailStatus_sentEmail_foreign');
	    });
	    Schema::dropIfExists("sent_emails_status");
    }

}
