<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    Model::unguard();

//        $this->call(TypesSalesStatesTableSeeder::class);
	    $this->call(TypesCurrenciesTableSeeder::class);
	    $this->call(TypesVehiclesFuelsTableSeeder::class);
	    $this->call(TypesVehiclesColorsTableSeeder::class);
	    $this->call(UsersAndCarDealersTableSeeder::class);

	    Model::reguard();
    }
}
