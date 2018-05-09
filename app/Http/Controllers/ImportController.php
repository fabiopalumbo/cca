<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\CarDealerToken;
use App\CarDealerUser;
use App\RegionCity;
use App\TypeVehicle;
use App\TypeVehicleBrand;
use App\TypeVehicleModel;
use App\TypeVehicleVersion;
use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
	public function importCarCSV(){
		Excel::load(public_path().'/listado-cca-autos.csv', function($results) {
			$currentBrandNumber = null;
			$currentModelNumber = null;
			foreach ($results->get() as $result) {
				$name = ucwords(strtolower($result["nombre"]));
				$slug = str_slug($result["nombre"]);
				if($result["tipo"] == 'm'){
					if(!$brand = TypeVehicleBrand::where("slug",'=',$slug)->
						where('type_vehicle_id','=',TypeVehicle::AUTOS_CAMIONETAS)->
						first()){
						$brand = TypeVehicleBrand::create(array(
								"type_vehicle_id" => TypeVehicle::AUTOS_CAMIONETAS,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					$currentBrandNumber = $brand->id;
				}else if($result["tipo"] == 'o'){
					if(!$model = TypeVehicleModel::where("slug",'=',$slug)->
						where('type_vehicle_brand_id','=',$currentBrandNumber)->
						first()){
						$model = TypeVehicleModel::create(array(
								"type_vehicle_brand_id" => $currentBrandNumber,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					$currentModelNumber = $model->id;
				}else if($result["tipo"] == 'v'){
					/*
					$DBversions = TypeVehicleVersion::where("type_vehicle_model_id",'=',$currentModelNumber)->get();
					$found = false;
					foreach($DBversions as $DBversion){
						if($this->compareStrings($DBversion->slug,$slug)){
							$found = true;
							break;
						};
					}
					if(!$found){
						TypeVehicleVersion::create(array(
								"type_vehicle_model_id" => $currentModelNumber,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					*/
				}
			}
			echo 'OK importCarCSV';
		});
	}

	public function importTruckCSV(){
		Excel::load(public_path().'/listado-cca-camiones.csv', function($results) {
			$currentBrandNumber = null;
			$currentModelNumber = null;
			foreach ($results->get() as $result) {
				$name = ucwords(strtolower($result["nombre"]));
				$slug = str_slug($result["nombre"]);
				if($result["tipo"] == 'm'){
					if(!$brand = TypeVehicleBrand::where("slug",'=',$slug)->
						where('type_vehicle_id','=',TypeVehicle::CAMIONES)->
						first()){
						$brand = TypeVehicleBrand::create(array(
								"type_vehicle_id" => TypeVehicle::CAMIONES,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					$currentBrandNumber = $brand->id;
				}else if($result["tipo"] == 'o'){
					if(!$model = TypeVehicleModel::where("slug",'=',$slug)->
						where('type_vehicle_brand_id','=',$currentBrandNumber)->
						first()){
						$model = TypeVehicleModel::create(array(
								"type_vehicle_brand_id" => $currentBrandNumber,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					$currentModelNumber = $model->id;
				}else if($result["tipo"] == 'v'){
					/*
					$DBversions = TypeVehicleVersion::where("type_vehicle_model_id",'=',$currentModelNumber)->get();
					$found = false;
					foreach($DBversions as $DBversion){
						if($this->compareStrings($DBversion->slug,$slug)){
							$found = true;
							break;
						};
					}
					if(!$found){
						TypeVehicleVersion::create(array(
								"type_vehicle_model_id" => $currentModelNumber,
								"name" => $name,
								"slug" => $slug
							)
						);
					}
					*/
				}
			}
			echo 'OK importTruckCSV';
		});
	}

	public function importPartnerCSV(){
		Excel::load(public_path().'/listado-cca-socios.csv', function($results) {
			foreach ($results->get() as $result) {
				if($region_city = RegionCity::where('slug','=',str_slug($result["s_localidad"]))->first()){
					$region_id = $region_city->region_id;
					$region_city_id = $region_city->id;
				}else{
					$region_id = 7;
					$region_city_id = 168;
				}
				$dealer = CarDealer::create(array(
					'name' => $result['s_empresa'],
					'email' => is_null($result['s_mail'])?'':$result['s_mail'],
					'phone' => is_null($result['s_tel'])?'':$result['s_tel'],
					'address' => is_null($result['s_direccion'])?'':$result['s_direccion'],
					'zip_code' => is_null($result['s_codpostal'])?'':$result['s_codpostal'],
					'region_id' => $region_id,
					'region_city_id' => $region_city_id,
					'partner' => $result['s_socio'],
					'company' => $result['s_empresa'],
					'cuit' => is_null($result['s_cuit'])?'':$result['s_cuit'],
					'dni_type' => is_null($result['s_tipodoc'])?'':$result['s_tipodoc'],
					'dni' => is_null($result['s_doc'])?'':$result['s_doc'],
					'cellphone' => is_null($result['s_cel'])?'':$result['s_cel'],
					'fax' => is_null($result['s_fax'])?'':$result['s_fax'],
					'contact' => is_null($result['s_contacto'])?'':$result['s_contacto'],
					'location' => is_null($result["s_localidad"])?'':$result["s_localidad"],
				));
				CarDealerToken::create(array(
					'car_dealer_id' => $dealer->id,
				));
				if(is_null($result['s_mail']) || User::where('email','=',$result['s_mail'])->first()){
					$email = $result['s_socio'];
				}else{
					$email = $result['s_mail'];
				}
				$user = User::create(array(
					'email' => $email,
					'username' => $result['s_usuario'],
					'first_name' => is_null($result['s_nombre'])?'':$result['s_nombre'],
					'last_name' => is_null($result['s_apellido'])?'':$result['s_apellido'],
					'phone' => is_null($result['s_tel'])?'':$result['s_tel'],
					'password' => Hash::make($result['s_pass']),
				));
				CarDealerUser::create(array(
					'car_dealer_id' => $dealer->id,
					'user_id' => $user->id,
				));
				UserGroup::create(array(
					'user_id' => $user->id,
					'group_id' => UserGroup::DEALER_ADMIN,
				));
			}
			echo 'OK';
		});
	}

	/**
	 * @param $first
	 * @param $second
	 * @return bool
	 */
	private function compareStrings($first,$second){
        $first_array = explode('-',$first);
        $second_array = explode('-',$second);
        if(count(array_diff($first_array,$second_array))){
            return false;
        }
        if(count(array_diff($second_array,$first_array))){
            return false;
        }
        return true;
    }
}