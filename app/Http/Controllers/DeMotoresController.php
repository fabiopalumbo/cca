<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\CarDealerToken;
use App\QuestionAutocosmo;
use App\Region;
use App\RegionCity;
use App\TypeVehicle;
use App\TypeVehicleBrand;
use App\TypeVehicleColor;
use App\TypeVehicleModel;
use App\TypeVehicleVersion;
use App\Vehicle;
use App\VehicleAutocosmos;
use App\VehicleDeautos;
use App\VehicleDeMotores;
use App\VehicleImage;
use App\VehicleNuestrosAutos;
use Carbon\Carbon;
use Config;
use DB;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use App\DASession;
use Illuminate\Http\Request;
use Input;
use Hash;
use Maatwebsite\Excel\Facades\Excel;

class DeMotoresController extends Controller
{
    public function importCarCSV(){
        ini_set('max_execution_time', 0);
        Excel::load(public_path().'/listado-demotores-autos.csv', function($results) {
            foreach ($results->get() as $result) {
                $slug_brand = str_slug($result["marca"]);
                $slug_model = str_slug($result["modelo"]);
                $slug_version = str_slug($result["version"]);
                if($brand = TypeVehicleBrand::where("slug",'=',$slug_brand)->
                    where('type_vehicle_id','=',TypeVehicle::AUTOS_CAMIONETAS)->
                    first()){

                    $brand->demotores_id = $result["idmarca"];
                    $brand->save();
                }else{
                    $brand = TypeVehicleBrand::create(array(
                            "type_vehicle_id" => TypeVehicle::AUTOS_CAMIONETAS,
                            "name" => ucwords(strtolower($result["marca"])),
                            "mercadolibre_id" => 'MLA1939',         //OtrasMarcas
                            'demotores_id' => $result["idmarca"],
                            "slug" => $slug_brand
                        )
                    );
                }

                if(!$model = TypeVehicleModel::where("slug",'=',$slug_model)->
                    where('type_vehicle_brand_id','=',$brand->id)->
                    first()){
                    if(is_null($otrosm = TypeVehicleModel::where('slug','=',DB::raw('"otros-modelos"'))->
                        where('type_vehicle_brand_id','=',$brand->id)->
                        first())){
                        $otros = null;
                    }else{
                        $otros = $otrosm->mercadolibre_id;
                    }
                    $model = TypeVehicleModel::create(array(
                            "type_vehicle_brand_id" => $brand->id,
                            "name" => ucwords(strtolower($result["modelo"])),
                            "slug" => $slug_model,
                            'demotores_id' => $result["idmodelo"],
                            'mercadolibre_id' => $otros
                        )
                    );
                }else{
                    $model->demotores_id = $result["idmodelo"];
                    $model->save();
                }
                $currentModelNumber = $model->id;
                $version_update = '';
                $DBversions = TypeVehicleVersion::where("type_vehicle_model_id",'=',$currentModelNumber)->
                    get();
                $found = false;
                foreach($DBversions as $DBversion){
                    if($this->compareStrings($DBversion->slug,$slug_version)){
                        $found = true;
                        $version_update = $DBversion->slug;
                        break;
                    };
                }
                if(!$found){
                    TypeVehicleVersion::create(array(
                            "type_vehicle_model_id" => $currentModelNumber,
                            "name" => ucwords(strtolower($result["version"])),
                            "slug" => $slug_version,
                            'demotores_id' => $result["idversion"],
                        )
                    );
                }else{
                    $version_t = TypeVehicleVersion::where("type_vehicle_model_id",'=',$currentModelNumber)->
                        where("slug",'=',$version_update)->first();
                    $version_t->demotores_id = $result["idversion"];
                    $version_t->save();
                }
            }
            echo 'OK importCarCSV';
        });
    }

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

    public function publish($dealer_id,$vehicle_id)
    {
        $client = new Client();
        if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
            $dm_tokens = json_decode($dealer_token->demotores,true);
            if(isset($dm_tokens['user_id'])){

            }else{
                return \Response::make('no hay cuenta vinculada',400);
            }
        }else{
            return \Response::make('no hay cuenta vinculada',400);
        }

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
                "types_vehicles_brands.name as brand",
                "types_vehicles_fuels.name as fuel",
                "types_vehicles_colors.name as color",
                "vehicles.price",
                "types_vehicles_versions.name as version",
                "vehicles.kilometers",
                "vehicles.transmission",
                "types_currencies.name as currency_name",
                "vehicles.doors",
                "vehicles.year",
                'vehicles.direction',
                'vehicles.domain',
                "vehicles.type_vehicle_brand_id as brand_id",
                "vehicles.type_vehicle_model_id as model_id",
                "vehicles.type_vehicle_version_id as version_id",
                "vehicles.chasis_number"
            ));
        if($da = VehicleDeMotores::where("vehicle_id",'=',$vehicle_id)->first()){
            $data = TypeVehicleBrand::where('id','=',$da->type_vehicle_brand_id)->first(array('name'));
            $vehicle->brand_id = $da->type_vehicle_brand_id;

            $vehicle->brand = $data['name'];
            $data = TypeVehicleModel::where('id','=',$da->type_vehicle_model_id)->first(array('name'));
            $vehicle->model_id = $da->type_vehicle_model_id;
            $vehicle->model = $data['name'];
            $data = TypeVehicleVersion::where('id','=',$da->type_vehicle_version_id)->first(array('name'));
            $vehicle->version_id = $da->type_vehicle_version_id;
            $vehicle->version = $data['name'];
        }else{
            if(is_null($vehicle->brand_id)){
                return array("brand"=>null);
            }else{

                if(is_null(TypeVehicleBrand::where('id','=',$vehicle->brand_id)->whereNotNull('demotores_id')->first())){
                    return array("brand"=>null,"brand_name"=>$vehicle->brand);
                }
                if(is_null($vehicle->model_id)){
                    return array("brand" => $vehicle->brand_id,"brand_name"=>$vehicle->brand,"model" => null);
                }else{
                    if(is_null(TypeVehicleModel::where('id','=',$vehicle->model_id)->whereNotNull('demotores_id')->first())){
                        return array("brand" => $vehicle->brand_id,"brand_name"=>$vehicle->brand,"model" => null,"model_name"=>$vehicle->model);
                    }
                    if(is_null($vehicle->version_id)) {
                        return array("brand" => $vehicle->brand_id,"brand_name"=>$vehicle->brand, "model" => $vehicle->model_id,"model_name"=>$vehicle->model, "version" => null);
                    }else{
                        if(is_null(TypeVehicleVersion::where('id','=',$vehicle->version_id)->whereNotNull('demotores_id')->first())){
                            return array("brand" => $vehicle->brand_id,"brand_name"=>$vehicle->brand, "model" => $vehicle->model_id,"model_name"=>$vehicle->model, "version" => null,"version_name"=>$vehicle->version);
                        }
                    }
                }
            }
        }
        if($vehicle->chasis_number){
            return \Response::make('N° de chasis incompleto',400);
        }
        $item = array(
            'provider' => Config::get("portals.dm.provider"),
            'providerVehicleId'=> $vehicle->chasis_number,
            'key' => Config::get("portals.dm.key"),
            'userId' => $dm_tokens['user_id'],
            'vehicleType' => 'CAR',
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'version' => $vehicle->version,
            'year' => $vehicle->year,
            'fuel' => $vehicle->fuel,
            'transmission' => $vehicle->transmission,
            'steering' => $vehicle->direction,
            'doors' => $vehicle->doors,
            'segment' => 'hatchback',
            'color' => $vehicle->color,
            'mileage' => $vehicle->kilometers > 0 ? $vehicle->kilometers : 100,
            'currency' => $vehicle->currency_name,
            'price' => $vehicle->price,
            'licensePlate' => str_replace(array(' ',''), '', strtoupper($vehicle->domain)),
            'uniqueOwner' => 'YES',
            'cab'=> 'NO',
            'oldOrClassic'=> 'NO',
            'subtitle'=> 'SUBTITLE',
            'description'=> 'description',
/*            'features'=> null,
            'images'=> null*/
        );
        $post_items = array();
        foreach( $item as $key => $value){
            $post_items[] = $key.'='.$value;
        }
        $post_string = implode('&',$post_items);
        $ch = curl_init();
        $url = "http://www.demotores.com.ar/frontend/rest/post.service";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded', 'Expect:  '));
        $result = curl_exec($ch);
        curl_close($ch);
        if(is_null($da)){
            VehicleDeMotores::create(array(
                "vehicle_id" => $vehicle_id,
                "publication_id" => $result,
                "permalink" => 'http://autos.demotores.com.ar/dm-'.$result.'-'.$item["brand"].'-'.str_replace(array(' ',''), '-', strtolower($item["model"])).'-'.str_replace(array(' ',''), '-', strtolower($item["version"])).'-'.$item["year"].'-'.$item["mileage"].'km',
                "type_vehicle_brand_id" => $vehicle->brand_id,
                "type_vehicle_model_id" => $vehicle->model_id,
                "type_vehicle_version_id" => $vehicle->version_id
            ));
        }else{
            $da->vehicle_id = $vehicle_id;
            $da->publication_id = $result;
            $da->permalink = 'http://autos.demotores.com.ar/dm-'.$result.'-'.$item["brand"].'-'.str_replace(array(' ',''), '-', strtolower($item["model"])).'-'.str_replace(array(' ',''), '-', strtolower($item["version"])).'-'.$item["year"].'-'.$item["mileage"].'km';
            $da->save();
        }
        return \Response::make('OK',200);
    }

    public function update($dealer_id,$vehicle_id){
        $data = Input::all();
        if($da = VehicleDeMotores::where("vehicle_id",'=',$vehicle_id)->first()){
            $da->type_vehicle_brand_id = $data["brand"];
            $da->type_vehicle_model_id = $data["model"];
            $da->type_vehicle_version_id = $data["version"];
            $da->save();
        }else{
            VehicleDeMotores::create(array(
                "vehicle_id" => $vehicle_id,
                "type_vehicle_brand_id" => $data["brand"],
                "type_vehicle_model_id" => $data["model"],
                "type_vehicle_version_id" => $data["version"]
            ));
        }

        return $this->response(200,'OK');
    }

    public function delete($dealer_id,$vehicle_id)
    {
       /* if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
            $dm_tokens = json_decode($dealer_token->demotores,true);
            if(isset($dm_tokens['user_id'])){

            }else{
                return \Response::make('no hay cuenta vinculada',400);
            }
        }else{
            return \Response::make('no hay cuenta vinculada',400);
        }*/

        $vehicle = DB::table("vehicles")->
            where("vehicles.id",'=',$vehicle_id)->
            whereNull("vehicles.deleted_at")->
            first(array(
                "vehicles.chasis_number"
            ));


    }
}