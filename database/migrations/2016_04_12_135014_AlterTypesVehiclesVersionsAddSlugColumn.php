<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypesVehiclesVersionsAddSlugColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types_vehicles_versions', function (Blueprint $table) {
            $table->string("slug")->after("mercadolibre_id");
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
            $table->dropColumn("slug");
        });
    }
}
