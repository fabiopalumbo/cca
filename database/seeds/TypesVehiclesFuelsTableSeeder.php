<?php

use Illuminate\Database\Seeder;

class TypesVehiclesFuelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types_vehicles_fuels")->insert(array(
           "name" => "Nafta"
        ));

        DB::table("types_vehicles_fuels")->insert(array(
            "name" => "Diesel"
        ));

        DB::table("types_vehicles_fuels")->insert(array(
            "name" => "Nafta/Gnc"
        ));



    }
}
