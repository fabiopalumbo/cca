<?php
namespace App\Http\Controllers;

use App\CarDealerToken;
use App\RegionCity;
use App\TypeVehicleFeature;
use App\VehicleMercadolibre;
use App\Region;
use App\Repositories\Meli;
use App\TypeVehicleBrand;
use App\TypeVehicleModel;
use App\TypeVehicleVersion;
use App\Vehicle;
use App\VehicleImage;
use App\VehicleNuestrosAutos;
use Exception;
use Config;
use App\MlSession;
use Input;
use GuzzleHttp;
use App\TypeVehicle;
use DB;
use Illuminate\Http\Request;
use View;
use Illuminate\Translation;
use App\MercadolibreListingType;
use GuzzleHttp\Exception\RequestException;

class MercadoLibreController extends Controller
{
	/* - - - - - - - - - - - - Importación de datos de ML - - - - - - - - - - - - - - - - - - */

	public function getVehiclesTypes(){
		$client = new GuzzleHttp\Client();
		$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/categories/MLA1743');
		$promise = $client->sendAsync($request)->then(function ($response) {
			$types = json_decode($response->getBody(),true);
			foreach($types["children_categories"] as $type){
				if(is_null($DBTypeVehicle = TypeVehicle::withTrashed()->where('mercadolibre_id','=',$type["id"])->first())){
					$DBTypeVehicle = TypeVehicle::create(array(
						"name" => $type["name"],
						"mercadolibre_id" => $type["id"]
					));
				}
				if(!in_array($DBTypeVehicle->mercadolibre_id,array('MLA1744','MLA58254'))){//autos y camiones
					$DBTypeVehicle->delete();
				}
			}
		});
		$promise->wait();
		return 'Ok getVehiclesTypes';
	}

	public function getBrandsTypes(){
		$client = new GuzzleHttp\Client();
		$types_vehicles = TypeVehicle::all();
		foreach($types_vehicles as $type_vehicle){
			$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/categories/'.$type_vehicle->mercadolibre_id);
			$promise = $client->sendAsync($request)->then(function ($response) use($type_vehicle){
				$brands = json_decode($response->getBody(),true);
				foreach($brands["children_categories"] as $brand){
					if(is_null(TypeVehicleBrand::withTrashed()->where('mercadolibre_id','=',$brand["id"])->first())){
						TypeVehicleBrand::create(array(
							"type_vehicle_id" => $type_vehicle->id,
							"name" => $brand["name"],
							"mercadolibre_id" => $brand["id"],
							"slug" => str_slug($brand["name"])
						));
					}
				}
			});
			$promise->wait();
		}
		return 'Ok getBrandsTypes';
	}

	public function getModelsTypes(){
		$client = new GuzzleHttp\Client();
		$brands = TypeVehicleBrand::whereNotNull("mercadolibre_id")->get();
		foreach($brands as $brand){
			$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/categories/'.$brand->mercadolibre_id);
			$promise = $client->sendAsync($request)->then(function ($response) use($brand){
				$models = json_decode($response->getBody(),true);
				foreach($models["children_categories"] as $model){
					if(is_null(TypeVehicleModel::withTrashed()->where('mercadolibre_id','=',$model["id"])->first())){
						TypeVehicleModel::create(array(
							"type_vehicle_brand_id" => $brand->id,
							"name" => $model["name"],
							"mercadolibre_id" => $model["id"],
							"slug" => str_slug($model["name"])
						));
					}
				}
			});
			$promise->wait();
		}
		return 'Ok getModelsTypes';
	}

	public function getVersionsTypes(){
		$client = new GuzzleHttp\Client();
		$models = TypeVehicleModel::whereNotNull("mercadolibre_id")->get();
		foreach($models as $model){
			$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/categories/'.$model->mercadolibre_id.'/attributes');
			$promise = $client->sendAsync($request)->then(function ($response) use($model){
				$versions = json_decode($response->getBody(),true);
				foreach($versions as $version){
					if($version["name"] == "Versión" && isset($version["values"])){
						foreach($version["values"] as $vers){
							if(is_null(TypeVehicleVersion::withTrashed()->where('mercadolibre_id','=',$vers["id"])->first())){
								TypeVehicleVersion::create(array(
									"type_vehicle_model_id" => $model->id,
									"name" => $vers["name"],
									"mercadolibre_id" => $vers["id"],
									"slug" => str_slug($vers["name"])
								));
							}
						}
					};
				}
			});
			$promise->wait();
		}
		return 'Ok getVersionsTypes';
	}

	public function getRegions(){
		$client = new GuzzleHttp\Client();
		$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/classified_locations/countries/AR');
		$promise = $client->sendAsync($request)->then(function ($response) {
			$regions = json_decode($response->getBody(),true);
			foreach($regions["states"] as $region){
				if(is_null($DBregion = Region::withTrashed()->where('mercadolibre_id','=',$region["id"])->first())){
					$DBregion = Region::create(array(
						"name" => $region["name"],
						"mercadolibre_id" => $region["id"],
						"slug" => str_slug($region["name"])
					));
				}else{
					$DBregion->update(array(
						"name" => $region["name"],
						"slug" => str_slug($region["name"])
					));
				}
				if(in_array($DBregion->mercadolibre_id,array('TUxBUEJSQWwyMzA1','TUxBUFJFUDQyMjQ4Ng','TUxBUFVTQWl1cXdlMg','TUxBUFVSVXllZDVl'))){//Brasil,República Dominicana,USA,Uruguay
					$DBregion->delete();
				}
			}
		});
		$promise->wait();
		return 'Ok getRegions';
	}

	public function getCities()
	{
		$client = new GuzzleHttp\Client();
		$regions = Region::all();
		foreach ($regions as $region) {
			$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/classified_locations/states/'.$region->mercadolibre_id);
			$promise = $client->sendAsync($request)->then(function ($response) use ($region) {
				$cities = json_decode($response->getBody(), true);
				foreach ($cities["cities"] as $city) {
					if(is_null($DBRegion = RegionCity::withTrashed()->where('region_id','=',$region->id)->where('mercadolibre_id','=',$city["id"])->first())){
						RegionCity::create(array(
							"region_id" => $region->id,
							"name" => $city["name"],
							"mercadolibre_id" => $city["id"],
							"slug" => str_slug($city["name"])
						));
					}else{
						$DBRegion->update(array(
							"name" => $city["name"],
							"slug" => str_slug($city["name"])
						));
					}
				}
			});
			$promise->wait();
		}
		return 'Ok getCities';
	}

	public function getAttributes()
	{
		$client = new GuzzleHttp\Client();
		$types_vehicles = TypeVehicle::all();
		foreach ($types_vehicles as $type_vehicle) {
			$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/categories/'.$type_vehicle->mercadolibre_id.'/attributes');
			$promise = $client->sendAsync($request)->then(function ($response) use ($type_vehicle) {
				$attributes = json_decode($response->getBody(), true);
				foreach ($attributes as $attribute) {
					if(in_array($attribute['attribute_group_id'],TypeVehicleFeature::$FEATURES)){
						if(!$parentAttribute = TypeVehicleFeature::withTrashed()->
							where('mercadolibre_id','=',$attribute['attribute_group_id'])->
							where('type_vehicle_id','=',$type_vehicle->id)->
							first()){
							$parentAttribute = TypeVehicleFeature::create(array(
								'type_vehicle_id' => $type_vehicle->id,
								'name' => $attribute['attribute_group_name'],
								'mercadolibre_id' => $attribute['attribute_group_id'],
							));
						}
						if(!$DBAttribute = TypeVehicleFeature::withTrashed()->
							where('mercadolibre_id','=',$attribute["id"])->
							first()){
							TypeVehicleFeature::create(array(
								'name' => $attribute["name"],
								'mercadolibre_id' => $attribute["id"],
								'parent_id' => $parentAttribute->id
							));
						}else{
							$DBAttribute->update(array(
								"name" => $attribute["name"],
							));
						}
					}
				}
			});
			$promise->wait();
		}
		return 'Ok getAttributes';
	}

	public function getListingTypes(){
		$client = new GuzzleHttp\Client();
		$request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.mercadolibre.com/sites/MLA/listing_types');
		$promise = $client->sendAsync($request)->then(function ($response) {
			$listingTypes = json_decode($response->getBody(),true);
			foreach($listingTypes as $listingType){
				$client = new GuzzleHttp\Client();
				$response = $client->request('GET', 'https://api.mercadolibre.com/sites/MLA/listing_types/'.$listingType["id"]);
				$type = json_decode($response->getBody(),true);

				if($type["id"] == "gold_premium"){
					$description = 'Duración: 60 días. Al finalizar, tendrás 30 días para republicarlo gratis, todas las veces que quieras. Es necesario publicar al menos una foto. Aparece como primera publicación destacada';
				}elseif($type["id"] == "gold"){
					$description = 'Duración: 60 días. Al finalizar, tendrás 30 días para republicarlo gratis, todas las veces que quieras. Es necesario publicar al menos una foto. Aparece en lista destacada';
				}elseif($type["id"] == "silver"){
					$description = 'Duración: 60 días. Al finalizar, tendrás 30 días para republicarlo gratis, todas las veces que quieras. Ubicacion en el listado: Inferior';
				}elseif($type["id"] == "free"){
					$description = 'Duración: 30 días. Una publicacion gratuita cada 365 dias. Exposicion: Ultima en los listados.';
				}else{
					$description = '';
				}
				if(isset($type["exceptions_by_category"]) && !empty($type["exceptions_by_category"])){
					$type = MercadolibreListingType::create(array(
						"name" => $type["configuration"]["name"],
						"description" => $description,
						"mercadolibre_id" => $type["id"],
						"price" => $type["exceptions_by_category"][0]["configuration"]["listing_fee_criteria"]["max_fee_amount"]
					));
					if($type["configuration"]["name"] == 'bronze'){
						$type->delete();
					}
				}
			}
		});
		$promise->wait();
		return 'Ok';
	}

	/* - - - - - - - - - - - - - - - - - - - Métodos adicionales - - - - - - - - - - - - - - - - - - - - - - */

  public function login(Request $request){
      $meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"));
      $parameters = explode(',',Input::get('state'));
      $car_dealer_id = $parameters[1];
      if(Input::get('code')) {
          $oAuth = $meli->authorize(Input::get('code'), Config::get("portals.ml.url_redirect"));
          if( $token = $oAuth['body']->access_token) {
              // Now we create the sessions with the authenticated user
              session(['mlUser' => new MlSession($token,time() + $oAuth['body']->expires_in,$oAuth['body']->refresh_token,$oAuth['body']->user_id)]);
              if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$car_dealer_id)->first()){
                  $mlTokens = json_decode($dealer_token->mercadolibre,true);
                  $mlTokens['user_id'] = $oAuth['body']->user_id;
                  $mlTokens['access_token'] = $oAuth['body']->access_token;
                  $mlTokens['refresh_token'] = $oAuth['body']->refresh_token;
	              $mlTokens['expires_at'] = time()+$oAuth['body']->expires_in;
                  $dealer_token->mercadolibre = json_encode($mlTokens);
                  $dealer_token->save();
              }else{
                  $mlTokens = new \stdClass();
                  $mlTokens->user_id = $oAuth['body']->user_id;
                  $mlTokens->access_token = $oAuth['body']->access_token;
                  $mlTokens->refresh_token = $oAuth['body']->refresh_token;
                  $mlTokens->expires_at = time()+$oAuth['body']->expires_in;
                  CarDealerToken::create(array(
                      "car_dealer_id" => $car_dealer_id,
                      "mercadolibre" => json_encode($mlTokens)
                  ));
              }
              return View::make('mercadolibre-login')->
                  with(array(
                      "msg" =>"Se ha logueado correctamente. Ya puede cerrar esta ventana.",
                  ));
          }
      }
    /*$parameters = explode(',',Input::get('state'));

    $vehicleToken = $parameters[0];
    $meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"));

    //$meli->getAuthUrl(Config::get("portals.ml.url_redirect")); //Esto se está haciendo desde el front HARCODEADO

    if(!is_null($vehicle = Vehicle::where("token",'=',$vehicleToken)->first())){
      // Create our Application instance (replace this with your appId and secret).
      if(Input::get('code')) {
        $oAuth = $meli->authorize(Input::get('code'), Config::get("portals.ml.url_redirect"));
        if( $token = $oAuth['body']->access_token) {
          // Now we create the sessions with the authenticated user
          session(['mlUser' => new MlSession($token,time() + $oAuth['body']->expires_in,$oAuth['body']->refresh_token,$oAuth['body']->user_id)]);
          if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$vehicle->car_dealer_id)->first()){
                $mlTokens = json_decode($dealer_token->mercadolibre,true);
                $mlTokens["access_token"] = $token;
                $mlTokens['refresh_token'] = $oAuth['body']->refresh_token;
                $dealer_token->mercadolibre = json_encode($mlTokens);
                $dealer_token->save();
          }else{
                $mlTokens = new \stdClass();
                $mlTokens->app_ID = Config::get("portals.ml.app_ID");
                $mlTokens->secret_key = Config::get("portals.ml.secret_key");
                $mlTokens->url_redirect = Config::get("portals.ml.url_redirect");
                $mlTokens->access_token = $token;
                $mlTokens->refresh_token = $oAuth['body']->refresh_token;
              CarDealerToken::create(array(
                    "car_dealer_id" => $vehicle->car_dealer_id,
                    "mercadolibre" => json_encode($mlTokens)
                ));
          }
          if($parameters[1] === 'login'){
              return View::make('mercadolibre-login')->
              with(array(
                  "msg" =>"Se ha logueado correctamente. Ya puede cerrar esta ventana.",
              ));
          }
          //$this->prueba($request->session()->get('mlUser'));
          if($vehicleML = VehicleMercadolibre::where('vehicle_id','=',$vehicle->id)->first()){
            if($vehicleML->item_id){
              return View::make('mercadolibre-login')->
                with(array(
                  "msg" => "El vehículo ya se encuentra publicado.",
                  "permalink" => $vehicleML->permalink
                ));
            }else{
              if($permalink = $this->publishItem($vehicle->car_dealer_id,$vehicle->id,$request->session()->get('mlUser'))){
                if(!VehicleNuestrosAutos::where("vehicle_id",'=',$vehicle->id)->first()){
                  VehicleNuestrosAutos::create(array(
                      "vehicle_id" => $vehicle->id
                  ));
                }
                return View::make('mercadolibre-login')->
                with(array(
                    "msg" =>"El vehículo se ha publicado correctamente. Ya puede cerrar esta ventana.",
                    "permalink" => $permalink
                ));
              }else{
                return View::make('mercadolibre-login')->
                with(array(
                    "msg" => trans('errors.'.$GLOBALS['error'])
                ));
              }
            }
          }else{
            return View::make('mercadolibre-login')->
            with(array(
                "msg" =>"Hubo un error de grave.No se pudo publicar el vehiculo."
            ));
          }
        }else{
          // We can check if the access token in invalid checking the time

          if(time() + $oAuth['body']->expires_in < time()) {
            try {
              // Make the refresh proccess
              $refresh = $meli->refreshAccessToken();
              // Now we create the sessions with the new parameters
              session(['mlUser' => new MlSession($refresh['body']->access_token,time() + $refresh['body']->expires_in,$refresh['body']->refresh_token,$refresh['body']->user_id)]);
              if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$vehicle->car_dealer_id)->first()){
                    $mlTokens = json_decode($dealer_token->mercadolibre,true);
                    $mlTokens->access_token = $token;
                    $mlTokens['refresh_token'] = $oAuth['body']->refresh_token;
                    $dealer_token->mercadolibre = json_encode($mlTokens);
                    $dealer_token->save();
              }else{
                    $mlTokens = new \stdClass();
                    $mlTokens->app_ID = Config::get("portals.ml.app_ID");
                    $mlTokens->secret_key = Config::get("portals.ml.secret_key");
                    $mlTokens->url_redirect = Config::get("portals.ml.url_redirect");
                    $mlTokens->access_token = $token;
                    $mlTokens->refresh_token = $oAuth['body']->refresh_token;
                    CarDealerToken::create(array(
                        "car_dealer_id" => $vehicle->car_dealer_id,
                        "mercadolibre" => json_encode($mlTokens)
                    ));
              }
            } catch (Exception $e) {
              return View::make('mercadolibre-login')->with("msg",$e->getMessage());
              //echo "Exception: ",  $e->getMessage(), "\n";
            }
          }
        }
      }
    }else if($parameters[0] === 'null' && $parameters[2]){
        if(Input::get('code')) {
            $oAuth = $meli->authorize(Input::get('code'), Config::get("portals.ml.url_redirect"));
            if( $token = $oAuth['body']->access_token) {
                // Now we create the sessions with the authenticated user
                session(['mlUser' => new MlSession($token, time() + $oAuth['body']->expires_in, $oAuth['body']->refresh_token, $oAuth['body']->user_id)]);
                if ($dealer_token = CarDealerToken::where("car_dealer_id", '=', (int)$parameters[2])->first()) {
                    $mlTokens = json_decode($dealer_token->mercadolibre, true);
                    $mlTokens["access_token"] = $token;
                    $mlTokens['refresh_token'] = $oAuth['body']->refresh_token;
                    $dealer_token->mercadolibre = json_encode($mlTokens);
                    $dealer_token->save();
                } else {
                    $mlTokens = new \stdClass();
                    $mlTokens->app_ID = Config::get("portals.ml.app_ID");
                    $mlTokens->secret_key = Config::get("portals.ml.secret_key");
                    $mlTokens->url_redirect = Config::get("portals.ml.url_redirect");
                    $mlTokens->access_token = $token;
                    CarDealerToken::create(array(
                        "car_dealer_id" => (int)$parameters[2],
                        "mercadolibre" => json_encode($mlTokens)
                    ));
                }
                if ($parameters[1] === 'login') {
                    return View::make('mercadolibre-login')->
                    with(array(
                        "msg" => "Se ha logueado correctamente. Ya puede cerrar esta ventana.",
                    ));
                }
            }else{
                // We can check if the access token in invalid checking the time

                if(time() + $oAuth['body']->expires_in < time()) {
                    try {
                        // Make the refresh proccess
                        $refresh = $meli->refreshAccessToken();
                        // Now we create the sessions with the new parameters
                        session(['mlUser' => new MlSession($refresh['body']->access_token,time() + $refresh['body']->expires_in,$refresh['body']->refresh_token,$refresh['body']->user_id)]);
                        if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$vehicle->car_dealer_id)->first()){
                            $mlTokens = json_decode($dealer_token->mercadolibre,true);
                            $mlTokens->access_token = $token;
                            $dealer_token->mercadolibre = json_encode($mlTokens);
                            $dealer_token->save();
                        }else{
                            $mlTokens = new \stdClass();
                            $mlTokens->app_ID = Config::get("portals.ml.app_ID");
                            $mlTokens->secret_key = Config::get("portals.ml.secret_key");
                            $mlTokens->url_redirect = Config::get("portals.ml.url_redirect");
                            $mlTokens->access_token = $token;
                            CarDealerToken::create(array(
                                "car_dealer_id" => $vehicle->car_dealer_id,
                                "mercadolibre" => json_encode($mlTokens)
                            ));
                        }
                    } catch (Exception $e) {
                        return View::make('mercadolibre-login')->with("msg",$e->getMessage());
                        //echo "Exception: ",  $e->getMessage(), "\n";
                    }
                }
            }
        }
    }*/
  }

	public function publishItem($dealer_id,$vehicle_id){
		if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
			$ml_tokens = json_decode($dealer_token->mercadolibre,true);
			if(isset($ml_tokens["user_id"]) && isset($ml_tokens["expires_at"]) && isset($ml_tokens["access_token"]) && isset($ml_tokens["refresh_token"])){
				//No hago nada, sigo de largo
			}else{
				return \Response::make('no hay cuenta vinculada',400);
			}
		}else{
			return \Response::make('no hay cuenta vinculada',400);
		}
		$meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"),$ml_tokens["access_token"],$ml_tokens["refresh_token"]);
		$vehicle = DB::table("vehicles")->
			join("types_vehicles","types_vehicles.id",'=','vehicles.type_vehicle_id')->
			join("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
			join("types_vehicles_models",'types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
			join("types_currencies","types_currencies.id",'=','vehicles.type_currency_id')->
			leftJoin("types_vehicles_colors",'types_vehicles_colors.id','=','vehicles.type_vehicle_color_id')->
			leftJoin("types_vehicles_versions",'types_vehicles_versions.id','=','vehicles.type_vehicle_version_id')->
			join("types_vehicles_fuels",'types_vehicles_fuels.id','=','vehicles.type_vehicle_fuel_id')->
			where("vehicles.id",'=',$vehicle_id)->
			whereNull("vehicles.deleted_at")->
			first(array(
				"types_vehicles_models.name as model",
				"types_vehicles_models.mercadolibre_id as category",
				"types_vehicles_brands.name as brand",
				"types_vehicles_fuels.name as fuel",
				"types_vehicles_colors.name as color",
				"vehicles.price",
				"types_vehicles_versions.name as version",
				"vehicles.kilometers",
				"vehicles.transmission",
				"types_currencies.name as currency_name",
				"vehicles.doors",
				"vehicles.year"
			));

		$vehicleML = DB::table('vehicles_mercadolibre')->
			join("mercadolibre_listing_types",'mercadolibre_listing_types.id','=','vehicles_mercadolibre.mercadolibre_listing_type_id')->
//			leftJoin("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles_mercadolibre.type_vehicle_brand_id')->
			leftJoin("types_vehicles_models",'types_vehicles_models.id','=','vehicles_mercadolibre.type_vehicle_model_id')->
//			leftJoin("types_vehicles_versions",'types_vehicles_versions.id','=','vehicles_mercadolibre.type_vehicle_version_id')->
			whereNull('vehicles_mercadolibre.deleted_at')->
			where("vehicles_mercadolibre.vehicle_id",'=',$vehicle_id)->
			select(array(
//				'types_vehicles_brands.name as brand',
//				'types_vehicles_models.name as model',
//				'types_vehicles_versions.name as version',
				'types_vehicles_models.mercadolibre_id as model_mercadolibre',
				'mercadolibre_listing_types.mercadolibre_id as listing_type_mercadolibre'
			))->
			first();
//		if($vehicleML->brand){
//			$vehicle->brand = $vehicleML->brand;
//		}
		if($vehicleML->model_mercadolibre){
//			$vehicle->model = $vehicleML->model;
			$vehicle->category = $vehicleML->model_mercadolibre;
		}
//		if($vehicleML->version){
//			$vehicle->version = $vehicleML->version;
//		}

		$brand = new \stdClass();
		$model = new \stdClass();
		$year = new \stdClass();
		$doors = new \stdClass();
		$fuel = new \stdClass();
		$kilometers = new \stdClass();
		$features = array();
		$selected_features = DB::table('vehicles_features')->
			join('types_vehicles_features','types_vehicles_features.id','=','vehicles_features.type_vehicle_feature_id')->
			where('vehicles_features.vehicle_id','=',$vehicle_id)->
			get(array(
				'types_vehicles_features.mercadolibre_id'
			));
		foreach($selected_features as $selected_feature){
			$features[$selected_feature->mercadolibre_id] = str_replace('_','/',$selected_feature->mercadolibre_id).'-Y';
		}
		$response = $meli->get("/categories/".$vehicle->category."/attributes");
		$variations = $response["body"];
		foreach($variations as $variation){
			if($variation->name == "Marca"){
				$brand->id = $variation->id;
				$brand->value_id = $variation->values[0]->id;
			}elseif($variation->name == "Modelo"){
				$model->id = $variation->id;
				$model->value_id = $variation->values[0]->id;
			}elseif($variation->name == "Año"){
				$year->id = $variation->id;
				foreach($variation->values as $value){
					if($value->name == (string)$vehicle->year){
						$year->value_id = $value->id;
					}
				}
			}elseif($variation->name == "Cant. de puertas"){
				$doors->id = $variation->id;
				foreach($variation->values as $value){
					if($value->name == (string)$vehicle->doors ){
						$doors->value_id = $value->id;
					}
				}
			}elseif($variation->name == "Combustible"){
				$fuel->id = $variation->id;
				foreach($variation->values as $value){
					if($value->name == $vehicle->fuel ){
						$fuel->value_id = $value->id;
					}
				}
			}elseif($variation->name == "Kilómetros"){
				$kilometers->id = $variation->id;
				$kilometers->value_name = (int)$vehicle->kilometers;
			}elseif(in_array($variation->attribute_group_id,TypeVehicleFeature::$FEATURES)){
				if(!array_key_exists($variation->id,$features)){
					$features[$variation->id] = str_replace('_','/',$variation->id).'-N';
				}
			}
		}

		$dealer = DB::table("car_dealers")->
			join("regions_cities","regions_cities.id",'=','car_dealers.region_city_id')->
			join("regions","regions_cities.region_id","=","regions.id")->
			whereNull("car_dealers.deleted_at")->
			where("car_dealers.id",'=',$dealer_id)->
			first(array(
				"regions.mercadolibre_id as region_mercadolibre",
				"regions.name as region_name",
				"regions_cities.name as city_name",
				"regions_cities.mercadolibre_id as city_mercadolibre",
				"car_dealers.zip_code",
				"car_dealers.address"
			));

		$item = array(
			"title" => $vehicle->brand .' '.$vehicle->model.' '.$vehicle->version ?:'',
			"category_id" => $vehicle->category,
			"price" => (int)$vehicle->price,
			"currency_id" => $vehicle->currency_name,
			"available_quantity" => 1,
			"buying_mode" => "classified",
			"listing_type_id" => $vehicleML->listing_type_mercadolibre,
			"condition" => (int)$vehicle->kilometers == 0 ? "new" : "used",
			"location" => array(
				"address_line" => $dealer->address,
				"zip_code" => $dealer->zip_code,
				"state" => array(
					"id" => $dealer->region_mercadolibre,
					"name" => $dealer->region_name
				),
				"country" => array(
					"id"=> "AR",
					"name"=> "Argentina",
				),
				"city"=> array(
					"id" => $dealer->city_mercadolibre,
					"name" => $dealer->city_name
				),
				"latitude" => -38.416097,
				"longitude" => -63.616672
			),
			"description" => "Vehiculo:".' '.$vehicle->brand.' '."Modelo:".' '. $vehicle->model .' '."Color:".' '.$vehicle->color.' '."Transmision:".' '.$vehicle->transmission .' '. ".Si quieres saber mas , no dudes en preguntarnos!",
			"attributes" => array(
				$brand,
				$model,
				$fuel,
				$doors,
				$year,
				$kilometers
			),
			"video_id" => null,
			//"warranty" => "12 month by Ray Ban",
		);
		foreach($features as $feature_id => $feature_value){
			$item["attributes"][] = (object)array(
				'id' => $feature_id,
				'value_id' => $feature_value,
			);
		}
		$pictures = VehicleImage::where("vehicle_id",'=',$vehicle_id)->get();
		foreach($pictures as $picture){
			$item["pictures"][] = (object)array("source" => $picture->url);
		}

		if($ml_tokens['expires_at'] >= time()){
			$refresh_return = $meli->refreshAccessToken();
			$ml_tokens['refresh_token'] = $refresh_return['body']->refresh_token;
			$ml_tokens['expires_at'] = time()+$refresh_return['body']->expires_in;
            $dealer_token->mercadolibre = json_encode($ml_tokens);
            $dealer_token->save();
		}
		/* Esto permite validar que el objeto está bien armado */
		$validate_item =  $meli->post("/items/validate",$item,array('access_token' => $ml_tokens["access_token"]));
		if($validate_item['httpCode'] == 401 && $validate_item["body"]->message == 'invalid_token'){
			return \Response::make('not found',400);
		}elseif($validate_item['httpCode'] == 400){
			$result = 'MercadoLibre responde: ';
			if(isset($validate_item["body"]->message)){
				$result .= $validate_item["body"]->message;
			}
			if(isset($validate_item["body"]->cause)){
				foreach($validate_item["body"]->cause as $error){
					$result .= ' - Error: '.$error->code.' ('.$error->message.')';
				}
			}
			\Bugsnag::notifyError('Error en ML',$result,array('dealer' => $dealer_id, 'vehicle' => $vehicle_id));
			return \Response::make($result,400);
		}

		$publish_item = $meli->post("/items", $item, array('access_token' => $ml_tokens["access_token"]));
		if(isset($publish_item["body"]->message)){
			if($publish_item["body"]->message == "Error validating grant. Your authorization code or refresh token may be expired or it was already used."){
				$refresh_return = $meli->refreshAccessToken();
				$ml_tokens['refresh_token'] = $refresh_return["body"]->refresh_token;
                $dealer_token->mercadolibre = json_encode($ml_tokens);
                $dealer_token->save();
				return \Response::make('refreshed',400);
			}
			if($publish_item["body"]->message == "seller.unable_to_list"){
				$GLOBALS['error'] = (string) $publish_item["body"]->message;
				return \Response::make($GLOBALS['error'],400);
			}else{
				$GLOBALS['error'] = (string) $publish_item["body"]->error;
				return \Response::make($GLOBALS['error'],400);
			}
		}
		if(isset($publish_item["body"]->cause)){
			foreach($publish_item["body"]->cause as $error){
				$GLOBALS['error'] = (string)$error->code;
			}
			return \Response::make($GLOBALS['error'],400);
		}else{
			if(isset($publish_item["body"]->error)){
				$GLOBALS['error'] = 'item.category_error';
				return \Response::make($GLOBALS['error'],400);
			}
		}

		$item = array(
			"item_id" => $publish_item["body"]->id,
			"permalink" => $publish_item["body"]->permalink
		);
		VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->
			first()->
			update($item);
		return $publish_item["body"]->permalink;
	}

  public function listItems($dealer_id){
    return $this->response(200,DB::table("vehicles_mercadolibre")->
      join("vehicles","vehicles.id",'=','vehicles_mercadolibre.vehicle_id')->
      where("vehicles.car_dealer_id",'=',$dealer_id)->
      whereNull("vehicles_mercadolibre.deleted_at")
        ->get(array
        (
            "vehicles_mercadolibre.item_id as item",
            "vehicles_mercadolibre.permalink"
        ))
    );
  }

	/**
	 * @param $dealer_id
	 * @param $item_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($dealer_id,$item_id){
		return $this->response(200,VehicleMercadolibre::where("item_id",'=',$item_id)->first());
	}

	/**
	 * @param $dealer_id
	 * @param $vehicle_id
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
	 */
	public function closePublish($dealer_id,$vehicle_id)
	{
		if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
			$ml_tokens = json_decode($dealer_token->mercadolibre,true);
			if(isset($ml_tokens["user_id"]) && isset($ml_tokens["expires_at"]) && isset($ml_tokens["access_token"]) && isset($ml_tokens["refresh_token"])){
				if($vehicle_ml = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first()){
					if($vehicle_ml->item_id){
						$meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"),$ml_tokens["access_token"],$ml_tokens["refresh_token"]);
						$item = array(
							'status' => 'closed',
						);
						$publish_item = $meli->put('/items/'.$vehicle_ml->item_id,$item,array('access_token' => $ml_tokens["access_token"]));
						if($publish_item['httpCode'] == 200){
							$vehicle_ml->delete();
							return $this->response(200,"OK");
						}else{
							return $this->response(400,'Error: '.$publish_item['body']->message);
						}
					}
				}else{
					return \Response::make("Ha ocurrido un error inesperado.",400);
				}
			}
		}
		return \Response::make("Debe loguearse para eliminar la publicacion.",400);
	}

	private function prueba($mlUser)
	{
		$item = array(
			"identification_type" => "DNI",
			"identification_number" => "33333333",
			"address" => "Triunvirato 5555",
			"state" =>"AR-C",
			"city" =>"Capital Federal",
			"zip_dode"=> "1431",
			"phone"=>array(
				"area_code"=>"011",
				"number" =>"4444-4444",
				"extension" =>"001"
			),
			"first_name"=>"CCA",
			"last_name"=> "Fonsoft",
			"company"=>array(
				"corporate_name"=>"Acme",
				"brand_name"=>"Acme Company"
			),
			"mercadoenvios"=> "accepted"
		);
		$client = new GuzzleHttp\Client();
		//$response = $client->request('POST', "https://api.mercadolibre.com/users/".$mlUser->user_id."?access_token=".$mlUser->access_token, $item);
		$r = $client->request('PUT', "https://api.mercadolibre.com/users/".$mlUser->user_id."?access_token=".$mlUser->access_token, [
			'json' => $item
		]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listingTypes(){
		return $this->response(200,MercadolibreListingType::get());
	}

	/**
	 * @param $dealer_id
	 * @param $id
	 * @return array|\Illuminate\Http\JsonResponse
	 */
	public function storeListingType($dealer_id,$id){
	    $vehicle = DB::table("vehicles")->
		    join("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
		    join("types_vehicles_models",'types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
		    leftJoin("types_vehicles_versions",'vehicles.type_vehicle_version_id','=','types_vehicles_versions.id')->
		    whereNull("vehicles.deleted_at")->
		    where("vehicles.id",'=',$id)->
		    first(array(
			    "types_vehicles_models.name as model",
			    "types_vehicles_models.id as modelid",
			    "types_vehicles_models.mercadolibre_id as model_mercadolibre",
			    "types_vehicles_brands.name as brand",
			    "types_vehicles_brands.id as brandid",
			    "types_vehicles_brands.mercadolibre_id as brand_mercadolibre",
			    "types_vehicles_versions.name as version",
			    "types_vehicles_versions.id as versionid",
			    "types_vehicles_versions.mercadolibre_id as version_mercadolibre"
		    ));

        if($vehicleML = DB::table('vehicles_mercadolibre')->
	        leftJoin("types_vehicles_brands",'vehicles_mercadolibre.type_vehicle_brand_id','=','types_vehicles_brands.id')->
	        leftJoin("types_vehicles_models",'vehicles_mercadolibre.type_vehicle_model_id','=','types_vehicles_models.id')->
	        leftJoin("types_vehicles_versions",'vehicles_mercadolibre.type_vehicle_version_id','=','types_vehicles_versions.id')->
	        whereNull('vehicles_mercadolibre.deleted_at')->
	        where("vehicles_mercadolibre.vehicle_id",'=',$id)->
	        select(array(
		        'vehicles_mercadolibre.id',
		        'vehicles_mercadolibre.item_id',
		        'vehicles_mercadolibre.type_vehicle_brand_id',
		        'vehicles_mercadolibre.type_vehicle_model_id',
		        'types_vehicles_brands.mercadolibre_id as brand_mercadolibre',
		        'types_vehicles_models.mercadolibre_id as model_mercadolibre',
		        'types_vehicles_versions.mercadolibre_id as version_mercadolibre',
	        ))->
	        first()){
	        if($vehicleML->item_id){
		        return $this->response(400,'El vehiculo ya se encuentra publicado');
	        }else{
		        if($vehicleML->brand_mercadolibre){
			        $vehicle->brandid = $vehicleML->type_vehicle_brand_id;
			        $vehicle->brand_mercadolibre = $vehicleML->brand_mercadolibre;
		        }
		        if($vehicleML->model_mercadolibre){
			        $vehicle->modelid = $vehicleML->type_vehicle_model_id;
			        $vehicle->model_mercadolibre = $vehicleML->model_mercadolibre;
		        }
		        if($vehicleML->version_mercadolibre)
			        $vehicle->version_mercadolibre = $vehicleML->version_mercadolibre;

		        if(is_null($vehicle->brand_mercadolibre)){
			        return array("brand" => null);
		        }else{
			        if(is_null($vehicle->model_mercadolibre)){
				        return array("brand" => $vehicle->brandid,"model" => null);
			        }else{
				        if(is_null($vehicle->version_mercadolibre)) {
					        if((int)TypeVehicleVersion::where('type_vehicle_model_id','=',$vehicle->modelid)->count()){
						        return array("brand" => $vehicle->brandid, "model" => $vehicle->modelid, "version" => null);
					        }
				        }
			        }
		        }
		        VehicleMercadolibre::where('id','=',$vehicleML->id)->update(array(
			        "mercadolibre_listing_type_id" => Input::get('listing_type')
		        ));
		        return $this->response(200,array("data"=>'OK'));
	        }
        }else{
	        if(is_null($vehicle->brand_mercadolibre)){
		        return array("brand" => null);
	        }else{
		        if(is_null($vehicle->model_mercadolibre)){
			        return array("brand" => $vehicle->brandid,"model" => null);
		        }else{
			        if(is_null($vehicle->version_mercadolibre)) {
				        if((int)TypeVehicleVersion::where('type_vehicle_model_id','=',$vehicle->modelid)->count()){
					        return array("brand" => $vehicle->brandid, "model" => $vehicle->modelid, "version" => null);
				        }
			        }
		        }
	        }
	        VehicleMercadolibre::create(array(
		        "vehicle_id" => $id,
		        "mercadolibre_listing_type_id" => Input::get('listing_type')
	        ));
	        return $this->response(200,array("data" => 'OK'));
        }
    }

	/**
	 * @param $dealer_id
	 * @param $vehicle_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function verifyMlState($dealer_id,$vehicle_id){
		if($item = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first()){
			return $this->response(200,array("item_id" => $item->item_id));
		}
		return $this->response(200,array("item_id" => null));
	}

	/**
	 * @param $dealer_id
	 * @param $vehicle_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function updateFeatures($dealer_id,$vehicle_id){
		$data = Input::all();
		if($ml = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first()){
            $ml->type_vehicle_brand_id = $data["brand"];
            $ml->type_vehicle_model_id = $data["model"];
            $ml->type_vehicle_version_id = isset($data["version"]) ? $data["version"] : null;
            $ml->mercadolibre_listing_type_id = $data["listing"];
		}else{
			VehicleMercadolibre::create(array(
				"vehicle_id" => $vehicle_id,
				"type_vehicle_brand_id" => $data["brand"],
				"type_vehicle_model_id" => $data["model"],
				"type_vehicle_version_id" => isset($data["version"]) ? $data["version"] : null,
				"mercadolibre_listing_type_id" => $data["listing"]
			));
		}
		return $this->response(200,'OK');
	}

    public function mlLogin(){
        $state = Input::get('state');
        $app_ID = Config::get('portals.ml.app_ID');
        $url_redirect = Config::get('portals.ml.url_redirect');
        return \Response::redirectTo('https://auth.mercadolibre.com.ar/authorization?response_type=code&client_id='.$app_ID.'&redirect_uri='.$url_redirect.'&state="'.$state);
    }

}