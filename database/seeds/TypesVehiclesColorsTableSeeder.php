<?php

use Illuminate\Database\Seeder;

class TypesVehiclesColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types_vehicles_colors")->insert(array(
           "name" => "Blanco"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Gris Plata"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Gris Oscuro"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Azul"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Azul Metalizado"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Verde"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Verde Oliva"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Rojo"
        ));

        DB::table("types_vehicles_colors")->insert(array(
            "name" => "Rojo Vivo"
        ));

		    DB::table("types_vehicles_colors")->insert(array(
			    "name" => "Negro"
		    ));

    }
}
