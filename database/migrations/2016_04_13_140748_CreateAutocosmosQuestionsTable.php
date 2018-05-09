<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutocosmosQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_autocosmos',function(Blueprint $table){
            $table->increments("id");
            $table->string("query_id");
            $table->string("external_id");
            $table->string("brand");
            $table->string("model");
            $table->string("version")->nullable();
            $table->string("name");
            $table->string("email");
            $table->text("question");
            $table->text("answer")->nullable();
            $table->dateTime("date");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("questions_autocosmos");
    }
}
