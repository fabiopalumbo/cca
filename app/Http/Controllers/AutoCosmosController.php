<?php

namespace App\Http\Controllers;

use App\QuestionAutocosmo;
use App\TypeVehicle;
use App\TypeVehicleBrand;
use App\TypeVehicleModel;
use App\TypeVehicleVersion;
use App\VehicleAutocosmos;
use App\VehicleImage;
use App\VehicleNuestrosAutos;
use Carbon\Carbon;
use Config;
use DB;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use Input;
use App\Repositories\DealerTokenRepository;

class AutoCosmosController extends Controller
{
    public function __construct()
    {
	    $this->portal = 'autocosmos';
	    $this->appKey = Config::get('portals.ac.app_key');
	    $this->appSecret = Config::get('portals.ac.app_secret');
	    $this->appDomain = Config::get('portals.ac.app_domain');
	    $this->dealerTokenRepo = new DealerTokenRepository();
    }

	/**
	 * @param $s
	 * @param $key
	 * @return string
	 */
	public function GetSignature($s, $key) {
		return base64_encode(hash_hmac('sha256',utf8_encode($s),$key,true));
	}

	/**
	 * @param $method
	 * @param $md5
	 * @param $contentType
	 * @param $date
	 * @param $customHeaders
	 * @param $url
	 * @return array
	 */
	public function GetCanonicalParts($method, $md5, $contentType, $date, $customHeaders, $url){
		$parts = array($method,$md5,$contentType,$date);
		foreach ($customHeaders as $headerKey => $headerValue){
			$parts[] = strtolower($headerKey).":".$headerValue;
		}
		$parts[] = $url;
		return $parts;
	}

	/**
	 * @param $brand
	 * @param $name
	 * @return int|string
	 */
	public function get_location($brand,$name){
		$urlPath = '/api2/Modelos?marca='.$brand.'&nombre='.$name;
		$url = $this->appDomain.$urlPath;

		//TODO: esto es raro... ¿por que se hace un request y luego el file_get_contents?
		$client = new GuzzleHttp\Client();
		try {
			$client->request('GET', $url);
		} catch (RequestException $e) {
			if ($e->hasResponse()) {
				if($e->getResponse()->getStatusCode() == 404)
					return 404;
			}
		}

		return $this->authorization($urlPath);
	}

	/**
	 * @param $uri
	 * @param string $method
	 * @param array $customHeaders
	 * @return string
	 */
	private function authorization($uri,$method = 'GET',$customHeaders = array()){
		$url = $this->appDomain.$uri;
		$date = gmdate('D, d M Y H:i:s').' GMT';
		$canonical = join("\n",$this->GetCanonicalParts($method,"","",$date,$customHeaders,$uri));
		$signature = $this->GetSignature($canonical, $this->appSecret);
		$options = array(
			'http' => array(
				'header'  => array(
					"Date: ".$date,
					"Authorization: ACS-H ".$this->appKey.":".$signature,
				),
				'method'  => $method,
			),
		);
		foreach($customHeaders as $headerKey => $headerValue){
			$options['http']['header'][] = $headerKey.": ".$headerValue;
		}
		$context = stream_context_create($options);
		return file_get_contents($url,false,$context);
	}

	/*public function prueba(){
		$url = '/api2/Usuarios?email=adrian@cca.org.ar';
		$url1 = '/api2/Marcas';
		//$url1 = '/api2/ClasificadosUsados?externalId=AAA-43';
		//$url1 = '/api2/Modelos?marca=lexus';
		return $this->response(200,json_decode($this->authorization($url1),true));
	}*/

	/* - - - - - - - - - - - - Importación de datos de ML - - - - - - - - - - - - - - - - - - */

	public function getBrands(){
		// Actualiza el valor de autocosmos_id o crea una marca asociada a OtrasMarcas de ML
       	$url = '/api2/Marcas';
	   	$ACbrands = json_decode($this->authorization($url),true);
		foreach($ACbrands["_entries"] as $ACbrand){
			if((int)TypeVehicleBrand::where('slug','=',$ACbrand["Codename"])->count()){
				TypeVehicleBrand::where('slug','=',$ACbrand["Codename"])->update(array(
					'autocosmos_id' => $ACbrand["Codename"]
				));
			}else{
				TypeVehicleBrand::create(array(
					"type_vehicle_id" => TypeVehicle::AUTOS_CAMIONETAS,
					"name" =>  $ACbrand['Nombre'],
					"mercadolibre_id" => 'MLA1939',         //OtrasMarcas
					"autocosmos_id" => $ACbrand["Codename"],
					"slug" => str_slug($ACbrand['Nombre'])
				));
			}
		}
		return 'Ok getBrands';
	}

	public function getModels(){
		// Actualiza el valor de autocosmos_id o crea un modelos asociada a OtrasModelos  de ML
		$DBbrands = DB::table('types_vehicles_brands')->
			whereNotNull('autocosmos_id')->
			groupBy('autocosmos_id')->
			select(array(
				'autocosmos_id',
				DB::raw('GROUP_CONCAT(id SEPARATOR ",") as brand_ids')
			))->
			get();
		foreach($DBbrands as $DBbrand){
			$brand_ids = explode(',',$DBbrand->brand_ids);
			$url = '/api2/Modelos?marca='.$DBbrand->autocosmos_id;
			$ACmodels = json_decode($this->authorization($url),true);
			foreach($ACmodels["_entries"] as $ACmodel){
				if($DBmodel = TypeVehicleModel::whereIn('type_vehicle_brand_id',$brand_ids)->
					where('slug','=',$ACmodel["Codename"])->
					first()){
					$DBmodel->update(array(
						'autocosmos_id' => $ACmodel["Codename"]
					));
				}else{
					$type_vehicle_brand_id = $brand_ids[0];
					if($otherModel = TypeVehicleModel::where('type_vehicle_brand_id','=',$type_vehicle_brand_id)->
						where('slug','=',DB::raw('"otros-modelos"'))->
						first()){
						$mercadolibre_id = $otherModel->mercadolibre_id;    // Otros modelos de la marca
					}else{
						$mercadolibre_id = 'MLA27301';  // Otros modelos generales
					}
					TypeVehicleModel::create(array(
						"name" => $ACmodel['Nombre'],
						"type_vehicle_brand_id" => $type_vehicle_brand_id,
						"mercadolibre_id" => $mercadolibre_id,
						"autocosmos_id" => $ACmodel["Codename"],
						"slug" => str_slug($ACmodel['Nombre'])
					));
				}
			}
		}
		return 'Ok getModels';
	}

	/* - - - - - - - - - - - - - - - - - - - Métodos adicionales - - - - - - - - - - - - - - - - - - - - - - */

	/**
	 * @param $externalId
	 * @param $vehicle
	 * @param $lesignature
	 * @return string
	 */
	private function getItem($externalId,$vehicle,$lesignature){
		$urlPath = '/api2/ClasificadosUsados?externalId='.$externalId;
		$result = json_decode($this->authorization($urlPath,'GET',array("X-ACS-User-Signature" => $lesignature)),true);
		return $this->appDomain.'/clasificados/usados/'.$vehicle->autocosmos_brand_slug.'/'.$vehicle->autocosmos_model_slug.'/'.$vehicle->version.'/'.$result["ClasificadoId"];
	}

	/**
	 * @param $dealer_id
	 * @param $vehicle_id
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
	 */
	public function publishItem($dealer_id,$vehicle_id){
		$tokens = json_decode($this->dealerTokenRepo->getToken($dealer_id,$this->portal));
        if(isset($tokens->user_signature)){
            $lesignature = $tokens->user_signature;
            $email_signature = $tokens->email;
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
				"types_vehicles_models.id as model_id",
				"types_vehicles_models.autocosmos_id as autocosmos_model_slug",
				"types_vehicles_models.mercadolibre_id as category",
				"types_vehicles_brands.name as brand",
				"types_vehicles_brands.id as brand_id",
				"types_vehicles_brands.autocosmos_id as autocosmos_brand_slug",
				"types_vehicles_fuels.name as fuel",
				"types_vehicles_colors.name as color",
				"vehicles.price",
				"types_vehicles_versions.name as version",
				"types_vehicles_versions.id as version_id",
				"vehicles.kilometers",
				"vehicles.transmission",
				"vehicles.direction",
				"types_currencies.name as currency_name",
				"vehicles.doors",
				"vehicles.year",
				"vehicles.domain"
			));
		if($ac = VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->first()){
			if(!is_null($ac->type_vehicle_brand_id) && !is_null($ac->type_vehicle_model_id)){
				$brand_ac = TypeVehicleBrand::find($ac->type_vehicle_brand_id);
				$model_ac = TypeVehicleModel::find($ac->type_vehicle_model_id);
				$version_ac = TypeVehicleVersion::find($ac->type_vehicle_version_id);
				$vehicle->autocosmos_brand_slug = $brand_ac->autocosmos_id;
				$vehicle->autocosmos_model_slug = $model_ac->autocosmos_id;
			}
		}else{
			if(is_null($vehicle->autocosmos_brand_slug)){
				return $this->response(200,array("brand"=>null));
			}else{
				if(is_null($vehicle->autocosmos_model_slug)){
					return $this->response(200,array("brand" => $vehicle->brand_id,"model" => null));
				}
			}
		}

		$jsonCar = $this->get_location($vehicle->autocosmos_brand_slug ,$vehicle->autocosmos_model_slug);
		if($jsonCar == 404){
			return $this->response(400,"Error");
		}

		$array_location = json_decode($jsonCar,true);
		$_location = $array_location["_location"];

		$images = array();
		$VehicleImages = VehicleImage::where("vehicle_id",'=',$vehicle_id)->get();
		foreach($VehicleImages as $VehicleImage) {
			$images[] = $VehicleImage->url;
		}

		if(isset($version_ac)) $vehicle->version = $version_ac->name;
		$vehicle->version = $vehicle->version ?: '-';
		$vehicle->kilometers =  $vehicle->kilometers < 100 ? 100 : $vehicle->kilometers;
		if($vehicle->kilometers == 0)
			return $this->response(400,'No es posible publicar autos nuevos en Autocosmos.');

		do{
			$externalId = str_random(5);
		}while(VehicleAutocosmos::where("external_id",'=',$externalId)->first());

		$data ='{
		"externalId":"'.$externalId.'",
		"Email":"'.$email_signature.'",
		"Modelo":{"_location": "'.$_location.'"},
		"Version":"'.$vehicle->version.'",
		"Anio":'.(int)$vehicle->year.',
		"Color":"'.$vehicle->color.'",
		"Patente":"'.$vehicle->domain.'",
		"Kilometraje":"'.$vehicle->kilometers.'",
		"Comentario":"'.'",
		"Moneda":"'.$vehicle->currency_name.'",
		"Precio":'.$vehicle->price.',
		"Transmision":"'.$vehicle->transmission.'",
		"Combustible":"'.$vehicle->fuel.'",
		"Imagenes":'.json_encode($images).',
	    "Opciones":[]'./*$selected_features.*/'
	    }';
		$dataMD5 = base64_encode(md5($data, true));
		$contentType = "application/json";

		$urlPath = '/api2/ClasificadosUsados';
		$url = $this->appDomain.$urlPath;
		$date = gmdate('D, d M Y H:i:s') . ' GMT';
		$method  = 'POST';

		$canonical = join("\n",$this->GetCanonicalParts($method, $dataMD5, $contentType, $date, array("X-ACS-User-Signature" => $lesignature), $urlPath));
		$signature = $this->GetSignature($canonical, $this->appSecret);
		$options = array(
			'http' => array(
				'header'  => array(
					"Date: ".$date,
					"Authorization: ACS-H ".$this->appKey.":".$signature,
					"Content-Type: ".$contentType,
					"Content-MD5: ".$dataMD5,
					"X-ACS-User-Signature: ".$lesignature,
				),
				'method'  => $method,
				'content' => $data
			),
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		if($result == ""){
			$permalink = $this->getItem($externalId,$vehicle,$lesignature);
			if($publish = VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->first()){
				$publish->external_id = $externalId;
				$publish->permalink = $permalink;
				$publish->save();
			}else{
				VehicleAutocosmos::create(array(
					"vehicle_id" => $vehicle_id,
					"external_id" => $externalId,
					"permalink" => $permalink
				));
			}
			if(!VehicleNuestrosAutos::where("vehicle_id",'=',$vehicle_id)->first()){
				VehicleNuestrosAutos::create(array(
					"vehicle_id" => $vehicle_id
				));
			}
			return $this->response(200,array("data"=>"OK"));
		}else{
			return $this->response(400,'Error');
		}
	}

	/**
	 * @param $dealer_id
	 * @param $vehicle_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteItem($dealer_id,$vehicle_id){
		$vehicle = VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->first();
		$tokens = json_decode($this->dealerTokenRepo->getToken($dealer_id,$this->portal));
        $lesignature = $tokens->user_signature;

		$urlPath = '/api2/ClasificadosUsados?externalId=' .$vehicle->external_id;
		$result = json_decode($this->authorization($urlPath,'GET',array("X-ACS-User-Signature" => $lesignature)),true);
		if($this->delete($result,$vehicle_id,$lesignature)){
			return $this->response(200,'OK');
		}else
			return $this->response(400,'Error');
	}

	/**
	 * @param $result
	 * @param $vehicle_id
	 * @param $lesignature
	 * @return mixed
	 */
	private function delete($result,$vehicle_id,$lesignature){
		$urlPath = '/api2/ClasificadosUsados/'.$result["ClasificadoId"];
		$this->authorization($urlPath,'DELETE',array("X-ACS-User-Signature" => $lesignature));
		return VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->delete();
	}

	public function getQuestions(){
	  $data = Input::all();
	  QuestionAutocosmo::create(array(
		  "query_id" => $data["IdConsulta"],
		  "external_id" => $data["RefObjetoExterna"],
		  "brand" => $data["Marca"],
		  "model" => $data["Modelo"],
		  "version" => $data["Version"],
		  "email" => $data["Email"],
		  "name" => $data["Nombre"].' '.$data["Apellido"],
		  "question" => $data["Comentario"],
		  "data" => Carbon::now()
	  ));

	  return $this->response(200,'OK');

	}

	public function updateFeatures($dealer_id,$vehicle_id){
		$data = Input::all();
		if($ac = VehicleAutocosmos::where("vehicle_id",'=',$vehicle_id)->first()){
            $ac->type_vehicle_brand_id = $data["brand"];
            $ac->type_vehicle_model_id = $data["model"];
            $ac->type_vehicle_version_id = isset($data["version"]) ? $data["version"] : null;
            $ac->save();
		}else{
			VehicleAutocosmos::create(array(
				"vehicle_id" => $vehicle_id,
				"type_vehicle_brand_id" => $data["brand"],
				"type_vehicle_model_id" => $data["model"],
				"type_vehicle_version_id" => isset($data["version"])?$data["version"]:null
			));
		}
		return $this->response(200,'OK');
	}


}