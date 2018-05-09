<?php

namespace App\Http\Controllers;

use DB;
use App;
use Cache;
use Exception;
use App\CarDealerToken;
use App\VehicleAutocosmos;
use App\VehicleMercadolibre;
use App\Module;
use App\Permission;
use App\Vehicle;
use App\VehicleFeature;
use App\VehicleImage;
use App\VehicleNote;
use App\VehicleNuestrosAutos;
use App\TypeVehicleFeature;
use App\Repositories\Meli;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Repositories\CDNService;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Config;

class VehicleController extends Controller
{
    private $autocosmosController;

    public function __construct()
    {
        $this->middleware('permission:'.Module::STOCK.','.Permission::INDEX,['only' => ['index','getSharedVehicles']]);
	      $this->middleware('permission:'.Module::STOCK.','.Permission::READ,['only' => ['show','getSharedVehicle']]);
        $this->middleware('permission:'.Module::STOCK.','.Permission::CREATE,['only' => ['store','uploadVehiclePhoto','shareVehicle','storeListingType']]);
        $this->middleware('permission:'.Module::STOCK.','.Permission::UPDATE,['only' => ['update','updateNote','changeDestacada']]);
        $this->middleware('permission:'.Module::STOCK.','.Permission::DELETE,['only' => ['delete','removeVehiclePhoto','DeleteshareVehicle']]);
        $this->middleware('access', ['only' => ['index','store','uploadVehiclePhoto','update','updateNote','delete','removeVehiclePhoto','shareVehicle','storeListingType','changeDestacada','DeleteshareVehicle']]);

        $this->autocosmosController = new AutoCosmosController();
    }

/* - - - - - - - - - - - - - DATOS SEMI-ESTÁTICOS - - - - - - - - - - - - - - - - */

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFuels(){
		return $this->response(200,Cache::remember('VehicleControllerGetFuels',15,function(){
			return DB::table('types_vehicles_fuels')->whereNull('deleted_at')->get(array("id","name"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getTypes(){
		return $this->response(200,Cache::remember('VehicleControllerGetTypes',15,function(){
			return DB::table('types_vehicles')->whereNull('deleted_at')->get(array("id","name","mercadolibre_id"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getModels(){
		return $this->response(200,Cache::remember('VehicleControllerGetModels',15,function(){
			return DB::table('types_vehicles_models')->whereNull('deleted_at')->get(array("id","type_vehicle_brand_id","name","mercadolibre_id","autocosmos_id","deautos_id","demotores_id"));
		}));
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getVersions($id){
		return $this->response(200,Cache::remember('VehicleControllerGet'.$id.'Versions',15,function() use($id){
			return DB::table('types_vehicles_versions')->where("type_vehicle_model_id",'=',$id)->whereNull('deleted_at')->get(array("id","type_vehicle_model_id","name","mercadolibre_id","deautos_id","demotores_id"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getBrands(){
		return $this->response(200,Cache::remember('VehicleControllerGetBrands',15,function(){
			return DB::table('types_vehicles_brands')->whereNull('deleted_at')->get(array("id","type_vehicle_id","name","mercadolibre_id","autocosmos_id","deautos_id","demotores_id"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getColors(){
		return $this->response(200,Cache::remember('VehicleControllerGetColors',15,function(){
			return DB::table('types_vehicles_colors')->whereNull('deleted_at')->get(array("id","name"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCurrencies(){
		return $this->response(200,Cache::remember('VehicleControllerGetCurrencies',15,function(){
			return DB::table('types_currencies')->whereNull('deleted_at')->get(array("id","name"));
		}));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFeatures(){
		return $this->response(200,Cache::remember('VehicleControllerGetFeatures',15,function(){
			$features = DB::table('types_vehicles_features')->whereNull('deleted_at')->whereNull('parent_id')->get(array("id","type_vehicle_id","name"));
			foreach($features as $feature){
				$feature->children = DB::table('types_vehicles_features')->whereNull('deleted_at')->where('parent_id','=',$feature->id)->get(array("id","name"));
			}
			return $features;
		}));
	}

/* - - - - - - - - - - - - - FUNCIONES DE VEHÍCULOS - - - - - - - - - - - - - - - - */

	/**
	 * @param $dealer_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index($dealer_id){
        $data = Input::all();
        $query = DB::table('vehicles')->
	        join('types_vehicles','types_vehicles.id','=','vehicles.type_vehicle_id')->
	        join('types_vehicles_fuels','types_vehicles_fuels.id','=','vehicles.type_vehicle_fuel_id')->
            join('types_vehicles_brands','types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id' )->
            join('types_vehicles_models','types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
            leftJoin('types_vehicles_colors','types_vehicles_colors.id','=','vehicles.type_vehicle_color_id')->
            leftJoin('types_vehicles_versions','vehicles.type_vehicle_version_id','=','types_vehicles_versions.id')->
            leftJoin('vehicles_images','vehicles_images.id','=','vehicles.vehicle_image_highlighted_id')->
            leftJoin("vehicles_mercadolibre",function($join) {
                $join->on("vehicles_mercadolibre.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_mercadolibre.deleted_at", 'IS', DB::raw('NULL'));
                $join->on("vehicles_mercadolibre.item_id", 'IS NOT', DB::raw('NULL'));
            })->
            leftJoin("vehicles_nuestrosautos",function($join) {
                $join->on("vehicles_nuestrosautos.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_nuestrosautos.deleted_at", 'IS', DB::raw('NULL'));
            })->
            leftJoin("vehicles_facebook",function($join) {
                $join->on("vehicles_facebook.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_facebook.deleted_at", 'IS', DB::raw('NULL'));
            })->
            leftJoin("vehicles_autocosmos",function($join) {
                $join->on("vehicles_autocosmos.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_autocosmos.deleted_at", 'IS', DB::raw('NULL'));
                $join->on("vehicles_autocosmos.external_id", '!=', DB::raw('""'));
            })->
            leftJoin("vehicles_deautos",function($join) {
                $join->on("vehicles_deautos.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_deautos.deleted_at", 'IS', DB::raw('NULL'));
            })->
            leftJoin("vehicles_demotores",function($join) {
                $join->on("vehicles_demotores.vehicle_id", '=', 'vehicles.id');
                $join->on("vehicles_demotores.deleted_at", 'IS', DB::raw('NULL'));
            })->
            whereNull('vehicles.deleted_at')->
            where('vehicles.car_dealer_id','=',$dealer_id);
        if($data["brand"]){
            $query->where('vehicles.type_vehicle_brand_id','=',$data["brand"]);
        }
        if($data["model"]){
            $query->where('vehicles.type_vehicle_model_id','=',$data["model"]);
        }
        if($data["type"]){
            $query->where('vehicles.type_vehicle_id','=',$data["type"]);
        }
        if($data["fuel"]){
            $query->where('vehicles.type_vehicle_fuel_id','=',$data["fuel"]);
        }
        if($tag = $data["tag"]){
            $query->where(function($q) use($tag){
                $q->where('vehicles.domain','LIKE', $tag.'%')->
	                orWhere('vehicles.version','LIKE', $tag.'%')->
	                orWhere('vehicles.year','LIKE', $tag.'%')->
	                orWhere('vehicles.sale_condition','LIKE', $tag.'%')->
	                orWhere('vehicles.owner','LIKE', $tag.'%');
            });
        }
        $result = $query->select(array(
            "vehicles.id as id",
            "vehicles.ingress as ingress",
            "vehicles.domain as domain",
            "types_vehicles_brands.name as brand",
            "types_vehicles_models.name as model",
            "types_vehicles_versions.name as version",
            "types_vehicles_brands.id as brand_id",
            "types_vehicles_models.id as model_id",
            "types_vehicles_models.name as model_name",
            "types_vehicles_brands.name as brand_name",
            "types_vehicles_versions.id as version_id",
            "types_vehicles_versions.name as version_name",
            "vehicles.year as year",
            "types_vehicles_fuels.name as fuel",
            "vehicles.kilometers as kilometers",
            "types_vehicles_colors.id as color_id",
            "types_vehicles_colors.name as color_name",
            "types_vehicles_colors.color as color",
            "vehicles.buy as buy",
            "vehicles.doors as doors",
            "vehicles.price as price",
            "vehicles.sale_condition as sale_condition",
            "vehicles.owner as owner",
            "types_vehicles.name as type",
            "vehicles_images.url as image",
            "vehicle_image_highlighted_id",
            "vehicles.token",
            "vehicles_mercadolibre.permalink as permalink_ml",
            "vehicles_mercadolibre.id as mercadolibre_id",
            "vehicles_nuestrosautos.id as nuestrosautos_id",
            "vehicles_autocosmos.id as autocosmos_id",
            "vehicles_autocosmos.permalink as permalink_ac",
            "vehicles_deautos.id as deautos_id",
            "vehicles_deautos.permalink as permalink_da",
            "vehicles_demotores.id as demotores_id",
            "vehicles_demotores.permalink as permalink_dm",
            "vehicles_facebook.id as facebook_id",
            "vehicles_facebook.permalink as permalink_fb"
        ))->
        paginate(25);

        return $this->response(200,$result);
    }

	/**
	 * @param $dealer_id
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($dealer_id,$id){
		if($vehicle = DB::table('vehicles')->
			join('types_vehicles','types_vehicles.id','=','vehicles.type_vehicle_id')->
			join('types_vehicles_brands','types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
			join('types_vehicles_models','types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
			join('types_vehicles_fuels','types_vehicles_fuels.id','=','type_vehicle_fuel_id')->
			leftJoin('types_vehicles_colors','types_vehicles_colors.id','=','vehicles.type_vehicle_color_id')->
			leftJoin("vehicles_mercadolibre",function($join) {
				$join->on("vehicles_mercadolibre.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_mercadolibre.deleted_at", 'IS', DB::raw('NULL'));
				$join->on("vehicles_mercadolibre.item_id", 'IS NOT', DB::raw('NULL'));
			})->
			leftJoin("vehicles_nuestrosautos",function($join) {
				$join->on("vehicles_nuestrosautos.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_nuestrosautos.deleted_at", 'IS', DB::raw('NULL'));
			})->
			leftJoin("vehicles_facebook",function($join) {
				$join->on("vehicles_facebook.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_facebook.deleted_at", 'IS', DB::raw('NULL'));
			})->
			leftJoin("vehicles_autocosmos",function($join) {
				$join->on("vehicles_autocosmos.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_autocosmos.deleted_at", 'IS', DB::raw('NULL'));
                $join->on("vehicles_autocosmos.external_id", '!=', DB::raw('""'));
			})->
			leftJoin("vehicles_deautos",function($join) {
				$join->on("vehicles_deautos.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_deautos.deleted_at", 'IS', DB::raw('NULL'));
			})->
			leftJoin("vehicles_notes",function($join) {
				$join->on("vehicles_notes.vehicle_id", '=', 'vehicles.id');
				$join->on("vehicles_notes.deleted_at", 'IS', DB::raw('NULL'));
			})->
			whereNull('vehicles.deleted_at')->
			where('vehicles.car_dealer_id','=',$dealer_id)->
			where('vehicles.id','=',$id)->
			first(array(
				"vehicles.ingress as ingress",
				"vehicles.domain as domain",
				"types_vehicles_brands.id as brand_id",
				"types_vehicles_models.id as model_id",
				"types_vehicles_fuels.id as type_vehicle_fuel_id",
				"vehicles.type_vehicle_version_id as version_id",
				"vehicles.year as year",
				"vehicles.kilometers as kilometers",
				"vehicles.type_currency_id as currency_id",
				"types_vehicles_colors.id as color_id",
				"vehicles.buy as buy",
				"types_vehicles.id as type",
				"vehicles.price as price",
				"vehicles.transmission as transmission",
				"vehicles.heating as heating",
				"vehicles.direction as direction",
				"vehicles.chasis_number as chasis_number",
				"vehicles.sale_condition as sale_condition",
				"vehicles.owner as owner",
                "vehicles_notes.id as note_id",
                "vehicles_notes.text as note",
				"vehicles.token",
				"vehicles.doors",
				"vehicles_mercadolibre.id as mercadolibre_id",
				"vehicles_nuestrosautos.id as nuestrosautos_id",
				"vehicles_autocosmos.id as autocosmos_id",
				"vehicles_deautos.id as deautos_id",
				"vehicles_deautos.permalink as permalink_da",
				"vehicles_autocosmos.permalink as permalink_ac",
				"vehicles_mercadolibre.item_id",
				"vehicles_mercadolibre.permalink as permalink_ml",
				"vehicles_facebook.id as facebook_id",
				"vehicles_facebook.permalink as permalink_fb"
			))){

			$vehicle->features = Vehicle::find($id)->features()->get();

			$vehicle->images = DB::table("vehicles_images")->
				where("vehicle_id",'=',$id)->
				whereNull("deleted_at")->
				get(array(
					"id",
					"url",
					"alt as description"
				));

			$vehicle->publish = array(
				'mercadolibre'  => !is_null($vehicle->mercadolibre_id),
				'nuestrosAutos' => !is_null($vehicle->nuestrosautos_id),
				'facebook'      => !is_null($vehicle->facebook_id),
				'autoCosmos'    => !is_null($vehicle->autocosmos_id),
				'deAutos'       => !is_null($vehicle->deautos_id)
			);
		}
		return $this->response(200,$vehicle);
	}

	/**
	 * @param $dealer_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store($dealer_id){
        $data = Input::get("data");
        $token = $this->randomString(9);
        $tokenVehicle = Vehicle::where("token",'=',$token)->first();
        while($tokenVehicle){
           $token = $this->randomString(9);
           $tokenVehicle = Vehicle::where("token",'=',$token)->first();
        }
        $validator = Validator::make(
            array(
                'sale_condition' => $data["sale_condition"],
                'brand_id' => $data["brand_id"],
                'domain' => $data["domain"],
                'type_vehicle_fuel_id' => $data["type_vehicle_fuel_id"],
                'year' => $data["year"],
                'model_id' => $data['model_id'],
                'type' => $data['type'],
                'price' => $data["price"],
                'currency_id' => $data["currency_id"],
                'doors' => $data["doors"]
            ),
            array(
                'sale_condition' => 'required',
                'brand_id' => 'required',
                'domain' => 'required',
                'type_vehicle_fuel_id' => 'required',
                'year' => 'required',
                'model_id' => 'required',
                'type' => 'required',
                'price' => 'required',
                'currency_id' => 'required',
                'doors' => 'required'
            )
        );

        if (! $validator->passes()) {
            return $this->response(400,$validator->errors());
        }
        $vehicle = Vehicle::create(array(
            "car_dealer_id" => $dealer_id,
            //"ingress" => $data['ingress'],
            "domain" => isset($data['domain']) ? $data['domain'] : '',
            "type_vehicle_brand_id" => $data['brand_id'],
            "type_vehicle_model_id" => $data['model_id'],
            "type_vehicle_version_id" => isset($data['version_id']) ? $data['version_id'] : null,
            "year" => isset($data['year']) ? $data['year'] : '',
            "type_vehicle_fuel_id" => isset($data['type_vehicle_fuel_id']) ? (int)$data['type_vehicle_fuel_id'] : '',
            "kilometers" => isset($data['kilometers']) ? $data['kilometers'] : '',
            "type_vehicle_color_id" => isset($data['color_id']) ? $data['color_id'] : '',
            //"glasses" => $data['glasses'],
            "transmission" => isset($data['transmission']) ? $data['transmission'] : '',
            "heating" => isset($data['heating']) ? $data['heating'] : '',
            "direction" => isset($data['direction']) ? $data['direction'] : '',
            //"buy" => $data['buy'],
            "sale_condition" => isset($data['sale_condition']) ? $data['sale_condition'] : '',
            "owner" => isset($data['owner']) ? $data['owner'] : '',
            //"details" => $data['details'],
            "type_vehicle_id" => isset($data['type']) ? (int)$data['type'] : '',
            "chasis_number" => isset($data['chasis_number']) ? $data['chasis_number'] : '',
            "price" => isset($data['price']) ? $data['price'] : '',
            "type_currency_id" => $data["currency_id"],
	        "doors" => (int)$data["doors"],
            "token" => $token,
        ));

        return $this->response(200,array(
	        'id' => $vehicle->id,
	        'token' => $vehicle->token
        ));
    }

    public function update($dealer_id,$id,Request $request)
    {
        $data = Input::get("data");
        $validator = Validator::make(
            array(
                'sale_condition' => $data["sale_condition"],
                'brand_id' => $data["brand_id"],
                'domain' => $data["domain"],
                'type_vehicle_fuel_id' => $data["type_vehicle_fuel_id"],
                'year' => $data["year"],
                'model_id' => $data['model_id'],
                'type' => $data['type'],
                'price' => $data["price"],
                'currency_id' => $data["currency_id"],
                'doors' => $data["doors"]
            ),
            array(
                'sale_condition' => 'required',
                'brand_id' => 'required',
                'domain' => 'required',
                'type_vehicle_fuel_id' => 'required',
                'year' => 'required',
                'model_id' => 'required',
                'type' => 'required',
                'price' => 'required',
                'currency_id' => 'required',
                'doors' => 'required'
            )
        );

        if (! $validator->passes()) {
            return $this->response(400,'Campos requeridos');
        }

        $stock = Vehicle::find($id);
        //$stock->ingress = $data['ingress'];
        $stock->domain = $data['domain'];
        $stock->type_vehicle_brand_id = $data['brand_id'];
        $stock->type_vehicle_model_id = $data['model_id'];
        $stock->type_vehicle_version_id = isset($data['version_id']) ? $data['version_id'] : null;
        $stock->year = isset($data['year']) ? $data['year'] : '';
        $stock->type_vehicle_fuel_id = isset($data['type_vehicle_fuel_id']) ? (int)$data['type_vehicle_fuel_id'] : '';
        $stock->kilometers = isset($data['kilometers']) ? $data['kilometers'] : '';
        $stock->type_vehicle_color_id = isset($data['color_id']) ? $data['color_id'] : '';
        //$stock->buy = $data['buy'];
        $stock->sale_condition = isset($data['sale_condition']) ? $data['sale_condition'] : '';
        $stock->owner = isset($data['owner']) ? $data['owner'] : '';
        $stock->type_vehicle_id = isset($data['type']) ? (int)$data['type'] : '';
        $stock->heating = isset($data['heating']) ? $data['heating'] : '';
        $stock->direction =  isset($data['direction']) ? $data['direction'] : '';
        $stock->transmission = isset($data['transmission']) ? $data['transmission'] : '';
        $stock->price = isset($data['price']) ? $data['price'] : '';
        $stock->type_currency_id = $data['currency_id'];
        $stock->doors = (int)$data['doors'];
        //$stock->glasses = $data['glasses'];
        //$stock->details = $data['details'];
        if($stock->save()){
            $stock->features()->sync(Input::get("features"));

            return $this->response(200,'OK');
        }else{
            return $this->response(400,'Error');
        }
    }

	/**
	 * @param $dealer_id
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($dealer_id,$id)
	{
		if(Vehicle::find($id)->delete()){
			return $this->response(200,'OK');
		}else{
			return $this->response(400,'Error');
		}
	}

    public function uploadVehiclePhoto($dealer_id,$id)
    {
        $input = Input::all();
        $file = $input['file'];
        $extension = $file->guessExtension();
        $filename = md5($file->getFilename());
        $image = Image::make($file)->fit(800, 600)->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->encode($extension);
        $image->save(storage_path('images' . $filename . '.' . $extension));
        if ($url = CDNService::upload(
            $filename,
            storage_path('images' . $filename . '.' . $extension),
            $file->getMimeType()
            )) {
            $vehicleImage = VehicleImage::create(array(
                "vehicle_id" => $id,
                "url" => $url
            ));

            if (VehicleImage::where("vehicle_id", '=', $id)->count() <= 1) {
                Vehicle::find($id)->update(array("vehicle_image_highlighted_id" => $vehicleImage->id));
            }


            File::delete(storage_path('images' . $filename . '.' . $extension));

            return $this->response(
                200,
                array(
                    'url' => $url,
                    'filename' => $filename
                )
            );
        }else return $this->response(400,"Error al subir");
    }

    public function removeVehiclePhoto($dealer_id,$id){
        if($vehicle = Vehicle::where("vehicle_image_highlighted_id",'=',$id)->first())
        $vehicle->vehicle_image_highlighted_id = null;
        $vehicle->save();
        if(VehicleImage::find($id)->delete()){
            return $this->response(200,'OK');
        }else{
            return $this->response(400,'Error');
        }
    }

    public function createNote($dealer_id,$id){
        $note = Input::get("note",'');
        return $this->response(200,VehicleNote::create(array(
            "vehicle_id" => $id,
            "user_id" => \Auth::user()->id,
            "text" => $note
        )));
    }

    public function updateNote($dealer_id,$vehicle_id,$id){
         $note = VehicleNote::find($id);
         $note->text = Input::get("note",'');
        if($note->save()){
            return $this->response(200,$note);
        }else{
            return $this->response(400,'Error');
        }
    }

    public function removeNote($id){
       if(VehicleNote::find($id)->delete()){
           return $this->response(200,'OK');
       }else{
           return $this->response(400,'Error');
       }
    }

    public function getVehiclePhotos($dealer_id,$id){
      $images =  VehicleImage::where('vehicle_id','=',$id)->get(array("id","url","alt as description"));
      $vehicle = Vehicle::find($id);
      if($vehicle->vehicle_image_highlighted_id)
        return $this->response(200,array("images" => $images,"vehicle_image_highlighted_id"=>$vehicle->vehicle_image_highlighted_id));
      else
        return $this->response(200,array("images" => $images));
    }

    public function changeDestacada($dealer_id,$id){
        return $this->response(200,Vehicle::find(Input::get("vehicle_id"))->update(
            array("vehicle_image_highlighted_id" => $id)
        ));
    }

    private function randomString($length = 6) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function getSharedVehicles($dealer_id){
        $data = Input::all();
        $query = DB::table('vehicles')->
        join('car_dealers','car_dealers.id','=','vehicles.car_dealer_id')->
        join('types_vehicles_fuels','types_vehicles_fuels.id','=','type_vehicle_fuel_id')->
        join('types_vehicles','types_vehicles.id','=','vehicles.type_vehicle_id')->
        join('types_vehicles_brands','types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id' )->
        join('types_vehicles_models','types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
        join('types_vehicles_colors','types_vehicles_colors.id','=','vehicles.type_vehicle_color_id')->
        leftJoin('types_vehicles_versions','types_vehicles_versions.id','=','vehicles.type_vehicle_version_id')->
        leftJoin('vehicles_images','vehicles_images.id','=','vehicles.vehicle_image_highlighted_id')->
        leftJoin("vehicles_mercadolibre",function($join) {
            $join->on("vehicles_mercadolibre.vehicle_id", '=', 'vehicles.id');
            $join->on("vehicles_mercadolibre.deleted_at", 'IS', DB::raw('NULL'));
        })->
        join("vehicles_nuestrosautos",function($join) {
            $join->on("vehicles_nuestrosautos.vehicle_id", '=', 'vehicles.id');
            $join->on("vehicles_nuestrosautos.deleted_at", 'IS', DB::raw('NULL'));
        })->
        leftJoin("vehicles_facebook",function($join) {
            $join->on("vehicles_facebook.vehicle_id", '=', 'vehicles.id');
            $join->on("vehicles_facebook.deleted_at", 'IS', DB::raw('NULL'));
        })->
        leftJoin("vehicles_autocosmos",function($join) {
            $join->on("vehicles_autocosmos.vehicle_id", '=', 'vehicles.id');
            $join->on("vehicles_autocosmos.deleted_at", 'IS', DB::raw('NULL'));
            $join->on("vehicles_autocosmos.external_id", '!=', DB::raw('""'));
        })->
        whereNull('vehicles.deleted_at');
        if($data["brand"]){
            $query->where('types_vehicles_brands.id','=',$data["brand"]);
        }
        if($data["model"]){
            $query->where('types_vehicles_models.id','=',$data["model"]);
        }
        if($data["type"]){
            $query->where('types_vehicles.id','=',$data["type"]);
        }
        if($data["fuel"]){
            $query->where('types_vehicles_fuels.id','=',$data["fuel"]);
        }
        if($data["tag"]){
            $tag = $data["tag"];
            $query->where(function($q) use($tag){
                $q->where('vehicles.domain','LIKE', $tag.'%')->
                orWhere('vehicles.version','LIKE', $tag.'%')->
                orWhere('vehicles.year','LIKE', $tag.'%')->
                orWhere('vehicles.sale_condition','LIKE', $tag.'%')->
                orWhere('vehicles.owner','LIKE', $tag.'%');
            });
        }
        $result = $query->select(array(
            "vehicles.id as id",
            "car_dealers.id as dealer_id",
            "car_dealers.name as dealer_name",
            "vehicles.ingress as ingress",
            "vehicles.domain as domain",
            "types_vehicles_brands.name as brand",
            "types_vehicles_models.name as model",
            "types_vehicles_brands.id as brand_id",
            "types_vehicles_models.id as model_id",
            "types_vehicles_models.name as model_name",
            "types_vehicles_brands.name as brand_name",
            "vehicles.type_vehicle_version_id as version_id",
            "types_vehicles_versions.name as version_name",
            "vehicles.year as year",
            "types_vehicles_fuels.name as fuel",
            "vehicles.kilometers as kilometers",
            "types_vehicles_colors.id as color_id",
            "types_vehicles_colors.name as color_name",
            "types_vehicles_colors.color as color",
            "vehicles.buy as buy",
            "vehicles.sale_condition as sale_condition",
            "vehicles.owner as owner",
            "types_vehicles.name as type",
            "vehicles_images.url as image",
            "vehicle_image_highlighted_id",
            "vehicles.token",
            "vehicles_mercadolibre.item_id as mercadolibre_id",
            "vehicles_nuestrosautos.id as nuestrosautos_id",
            "vehicles_autocosmos.id as autocosmos_id",
            "vehicles_facebook.id as facebook_id"
        ))->
        paginate(25);

        return $this->response(200,$result);
    }

    public function shareVehicle($dealer_id,$id){
        if(VehicleNuestrosAutos::where("vehicle_id",'=',$id)->first()) return $this->response(400,'Error');
        VehicleNuestrosAutos::create(array(
            "vehicle_id" => $id
        ));
        return $this->response(200,'OK');
    }

    public function getSharedVehicle($dealer_id,$id){
        if(VehicleNuestrosAutos::where("vehicle_id",'=',$id)->first()){
            return $this->response(200,Vehicle::find($id));
        }else{
            return $this->response(400,"Vehiculo no compartido");
        }
    }

    public function DeleteshareVehicle($dealer_id,$id){
       VehicleNuestrosAutos::where("vehicle_id",'=',$id)->delete();
       return $this->response(200,'OK');
    }

	/**
	 * @param $dealer_id
	 * @param $id
	 */
	public function updatePortals($dealer_id,$id)
    {
	    try{
		    $this->updateMercadoLibre($dealer_id,$id);
	    }catch(Exception $e) {
//		    return $this->response(400,'error '.$e);
	    }
	    try{
		    $this->updateAutoCosmos($dealer_id,$id);
	    }catch(Exception $e) {
//		    return $this->response(400,'error '.$e);
	    }
    }

	private function updateMercadoLibre($dealer_id,$vehicle_id)
	{
		if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
			$ml_tokens = json_decode($dealer_token->mercadolibre,true);
			if(isset($ml_tokens["user_id"]) && isset($ml_tokens["expires_at"]) && isset($ml_tokens["access_token"]) && isset($ml_tokens["refresh_token"])){
				if($vehicle_ml = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first()){
					if($vehicle_ml->item_id){
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
								"types_vehicles_brands.name as brand",
								"types_vehicles_models.name as model",
								"types_vehicles_models.mercadolibre_id as category",
								"types_vehicles_fuels.name as fuel",
								"types_vehicles_colors.name as color",
								"vehicles.price",
								"types_vehicles_versions.name as version",
								"vehicles.kilometers",
								"vehicles.doors",
								"vehicles.year"
							));

						$brand = new \stdClass();
						$model = new \stdClass();
						$fuel = new \stdClass();
						$doors = new \stdClass();
						$year = new \stdClass();
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

						$item = array(
							"title" => $vehicle->brand .' '.$vehicle->model.' '.$vehicle->version ?:'',
							"price" => (int)$vehicle->price,
							"attributes" => array(
								$brand,
								$model,
								$fuel,
								$doors,
								$year,
								$kilometers
							)
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

//						$response = $meli->get('/items/'.$vehicle_ml->item_id);
//						$itemOld = json_decode($response['body'],true);
//						if($itemOld["sold_quantity"] > 1)
//							$message = "Algunos campos no pudieron ser actualizados debido a la venta de articulos";
//						else
//							$message = null;

						if($ml_tokens['expires_at'] >= time()){
							$refresh_return = $meli->refreshAccessToken();
							$ml_tokens['refresh_token'] = $refresh_return['body']->refresh_token;
							$ml_tokens['expires_at'] = time()+$refresh_return['body']->expires_in;
							$dealer_token->update(array(
								'mercadolibre' => json_encode($ml_tokens)
							));
						}

						$publish_item = $meli->put('/items/'.$vehicle_ml->item_id,$item,array('access_token' => $ml_tokens["access_token"]));
					}
				}
			}
		}
	}

	private function updateAutoCosmos($dealer_id,$vehicle_id)
	{
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
				"types_vehicles_brands.name as brand",
				"types_vehicles_models.name as model",
				"types_vehicles_brands.autocosmos_id as autocosmos_brand_slug",
				"types_vehicles_models.mercadolibre_id as category",
				"types_vehicles_models.autocosmos_id as autocosmos_model_slug",
				"types_vehicles_fuels.name as fuel",
				"types_vehicles_colors.name as color",
				"vehicles.price",
				"vehicles.domain",
				"types_vehicles_versions.name as version",
				"vehicles.kilometers",
				"vehicles.transmission",
				"vehicles.direction",
				"types_currencies.name as currency_name",
				"vehicles.doors",
				"vehicles.year"
			));
		if($vehicle_ac = VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->first()){
			if($vehicle_ac->external_id){

				$appKey = Config::get('portals.ac.app_key');
				$appSecret = Config::get('portals.ac.app_secret');
				$lesignature = Config::get('portals.ac.user_signature');
				$urlDomain = 'http://www.autocosmos.com.ar';
				$urlPath = '/api2/ClasificadosUsados?externalId='.$vehicle_ac->external_id;
				$url = $urlDomain . $urlPath;
				$date = gmdate('D, d M Y H:i:s') . ' GMT';
				$canonical = join("\n", $this->autocosmosController->GetCanonicalParts('GET',"","",$date,array("X-ACS-User-Signature" => $lesignature), $urlPath));
				$signature = $this->autocosmosController->GetSignature($canonical, $appSecret);
				$options = array(
					'http' => array(
						'header' => array(
							"Date: " . $date,
							"Authorization: ACS-H ".$appKey.":".$signature,
							"X-ACS-User-Signature: ".$lesignature
						),
						'method' => 'GET',
					),
				);
				$context = stream_context_create($options);
				$result = json_decode(file_get_contents($url, false, $context),true);

				if(is_null($vehicle->autocosmos_brand_slug) || is_null($vehicle->autocosmos_model_slug)){
					return $this->response(400,'La marca o el modelo no existe en Autocosmos. No es posible publicarlo aqui.');
				}
				$jsonCar = $this->autocosmosController->get_location($vehicle->autocosmos_brand_slug,$vehicle->autocosmos_model_slug);
				if($jsonCar == 404){
					return $this->response(400,'La marca o el modelo no existe en Autocosmos. No es posible publicarlo aqui.');
				}
				$array_location = json_decode($jsonCar,true);
				$_location = $array_location["_location"];
				$images = array();
				$vehicle->version = $vehicle->version ?: '-';
				$vehicle->kilometers =  $vehicle->kilometers < 100 ? 100 : $vehicle->kilometers;
				if($vehicle->kilometers == 0)
					return $this->response(400,'No es posible publicar autos nuevos en Autocosmos.');

				$pictures = VehicleImage::where("vehicle_id",'=',$vehicle_id)->get();
				foreach($pictures as $VehicleImage) {
					$images[] = $VehicleImage->url;
				}
				$data ='{
					"Modelo": {"_location": "'.$_location.'"},
					"Version":"'.$vehicle->version.'",
					"Anio":'.(int)$vehicle->year.',
					"Color":"'.$vehicle->color.'",
					"Patente":"'.$vehicle->domain.'",
					"Kilometraje":"'.$vehicle->kilometers.'",
					"Comentario":"",
					"Moneda":"'.$vehicle->currency_name.'",
					"Precio":'.$vehicle->price.',
					"Transmision":"'.$vehicle->transmission.'",
					"Combustible":"'.$vehicle->fuel.'",
					"Imagenes":'.json_encode($images).',
				    "Opciones":[]
				    }';

				$dataMD5 = base64_encode(md5($data, true));
				$urlPath = '/api2/ClasificadosUsados/' .$result["ClasificadoId"];
				$url = $urlDomain . $urlPath;
				$contentType = "application/json";
				$canonical = join("\n",$this->autocosmosController->GetCanonicalParts('PUT',$dataMD5,$contentType,$date,array("X-ACS-User-Signature" => $lesignature),$urlPath));
				$signature = $this->autocosmosController->GetSignature($canonical, $appSecret);
				$options = array(
					'http' => array(
						'header'  => array(
							"Date: ".$date,
							"Authorization: ACS-H ".$appKey.":".$signature,
							"Content-Type: ".$contentType,
							"Content-MD5: ".$dataMD5,
							"X-ACS-User-Signature: ".$lesignature
						),
						'method'  => 'PUT',
						'content' => $data
					),
				);
				$context  = stream_context_create($options);
				file_get_contents($url, false, $context);
			}
		}
	}
}