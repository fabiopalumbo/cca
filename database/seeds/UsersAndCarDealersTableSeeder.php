<?php

use App\User;
use App\UserGroup;
use App\CarDealer;
use App\CarDealerUser;
use Illuminate\Database\Seeder;

class UsersAndCarDealersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // Developer
      $user = User::create(array(
	      'first_name' => 'Loopear',
	      'last_name' => 'Software',
	      'email' => 'loopear@gmail.com',
	      'password' => Hash::make('ccaconloopear'),
	      'phone' => '4656-6614'
      ));
	    UserGroup::create(array(
		    'group_id' => UserGroup::DEV,
		    'user_id' => $user->id
	    ));

	    // Admin
      $user = User::create(array(
	      'first_name' => 'Adrián',
	      'last_name' => 'Martín',
	      'email' => 'adm.martin@gmail.com',
	      'password' => Hash::make('1234567890'),
	      'phone' => ''
      ));
	    UserGroup::create(array(
		    'group_id' => UserGroup::ADMIN,
		    'user_id' => $user->id
	    ));

			// Dealer - Admin
	    $user = User::create(array(
		    'first_name' => 'CCA',
		    'last_name' => 'Admin',
		    'email' => 'cca@cca.org.ar',
		    'password' => Hash::make('1234567890'),
		    'phone' => ''
	    ));
	    UserGroup::create(array(
		    'group_id' => UserGroup::DEALER_ADMIN,
		    'user_id' => $user->id
	    ));
	    $carDealer = CarDealer::create(array(
		    'name' => 'Concesionaria',
		    'email' => 'prueba@concesionaria.com',
		    'phone' => '12345678',
		    'region_id' => 4,
		    'region_city_id' => 4
	    ));
	    CarDealerUser::create(array(
		    "car_dealer_id" => $carDealer->id,
		    "user_id" => $user->id
	    ));

    }
}
