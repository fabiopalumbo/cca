<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesBrandsAddSlugColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_brands', function (Blueprint $table) {
            $table->string("slug")->after("autocosmos_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types_vehicles_brands', function (Blueprint $table) {
            $table->dropColumn("slug");
        });
    }
}
