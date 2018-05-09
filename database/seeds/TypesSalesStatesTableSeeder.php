<?php

use Illuminate\Database\Seeder;

class TypesSalesStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types_sales_states")->insert(array(
           "id" => 1,
           "name" => "Publicado"
        ));

        DB::table("types_sales_states")->insert(array(
            "id" => 2,
            "name" => "Reservado"
        ));

        DB::table("types_sales_states")->insert(array(
            "id" => 3,
            "name" => "Vendido"
        ));

        DB::table("types_sales_states")->insert(array(
            "id" => 4,
            "name" => "Cancelado"
        ));

        DB::table("types_sales_states")->insert(array(
            "id" => 5,
            "name" => "Transferido"
        ));

        DB::table("types_sales_states")->insert(array(
            "id" => 6,
            "name" => "Pausado"
        ));
    }
}
