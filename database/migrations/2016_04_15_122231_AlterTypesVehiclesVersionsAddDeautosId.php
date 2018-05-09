<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesVersionsAddDeautosId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_versions', function (Blueprint $table) {
            $table->string("deautos_id")->after("mercadolibre_id")->nullable();
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
            $table->dropColumn("deautos_id");
        });
    }
}
