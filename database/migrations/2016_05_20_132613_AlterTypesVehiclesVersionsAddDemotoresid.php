<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesVersionsAddDemotoresid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_versions', function (Blueprint $table) {
            $table->string("demotores_id")->after("deautos_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types_vehicles_versions', function (Blueprint $table) {
            $table->dropColumn("demotores_id");
        });
    }
}
