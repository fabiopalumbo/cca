<?php

use Illuminate\Database\Seeder;

class TypesCurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types_currencies")->insert(array(
           "name" => "ARS"
        ));

        DB::table("types_currencies")->insert(array(
            "name" => "USD"
        ));


    }
}
