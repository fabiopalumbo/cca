<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarDealersTableRenameAndAddImagesUrlColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_dealers', function (Blueprint $table) {
            $table->string("cropped_image_url")->after("image_url");
            $table->renameColumn("image_url","original_image_url");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_dealers', function (Blueprint $table) {
            $table->dropColumn("cropped_image_url");
            $table->renameColumn("original_image_url","image_url");
        });
    }
}
