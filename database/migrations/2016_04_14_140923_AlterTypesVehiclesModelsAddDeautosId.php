<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesModelsAddDeautosId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_models', function (Blueprint $table) {
            $table->string("deautos_id")->after("autocosmos_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types_vehicles_models', function (Blueprint $table) {
            $table->dropColumn("deautos_id");
        });
    }
}
