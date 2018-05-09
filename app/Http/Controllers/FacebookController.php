<?php

namespace App\Http\Controllers;


use App\Module;
use App\Permission;
use App\VehicleFacebook;
use App\VehicleImage;
use App\VehicleNuestrosAutos;
use Illuminate\Support\Facades\Input;
use Facebook;
use App\FbSession;
use Config;
use GuzzleHttp;
use Facebook\FacebookRequest;
use DB;
use Illuminate\Http\Request;

class FacebookController extends Controller
{
    public function login(Request $request){
        if($request->session()->has('fbUser')) {
            $request->session()->forget('fbUser');
        }
       $fb = new Facebook\Facebook([
            'app_id' => Config::get('portals.fb.app_id'),
            'app_secret' => Config::get('portals.fb.app_secret'),
            'default_graph_version' => Config::get('portals.fb.default_graph_version'),
        ]);
        $client = new GuzzleHttp\Client();
        $helper = $fb->getJavaScriptHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            echo 'No cookie set or no OAuth data could be obtained from cookie.';
            exit;
        }
        // Logged in
        $userPages = array();
        session(['fbUser' => new FbSession((string)$accessToken)]);
        $response = $client->request('GET', "https://graph.facebook.com/me/accounts?access_token=".$accessToken->getValue());
        $pages = json_decode($response->getBody(),true);
        foreach($pages["data"] as $page){
            $paginas = new \stdClass();
            $paginas->page_id = $page["id"];
            $paginas->page_token = $page["access_token"];
            $paginas->name = $page["name"];
            $userPages[] = $paginas;
        }
        return $this->response(200,$userPages);
    }

    public function indexPages(){
        $client = new GuzzleHttp\Client();
        $userPages = array();
        $response = $client->request('GET', "https://graph.facebook.com/me/accounts?access_token=".Input::get('token'));
        $pages = json_decode($response->getBody(),true);
        foreach($pages["data"] as $page){
            $paginas = new \stdClass();
            $paginas->page_id = $page["id"];
            $paginas->page_token = $page["access_token"];
            $paginas->name = $page["name"];
            $userPages[] = $paginas;
        }
        return $this->response(200,$userPages);
    }

    public function shareFbItem($dealer_id,$vehicle_id,Request $request){
        $client = new GuzzleHttp\Client();
        $vehicle = DB::table("vehicles")->
        join("types_vehicles","types_vehicles.id",'=','vehicles.type_vehicle_id')->
        join("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
        join("types_vehicles_models",'types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
        join("types_currencies","types_currencies.id",'=','vehicles.type_currency_id')->
        leftJoin("types_vehicles_colors",'types_vehicles_colors.id','=','vehicles.type_vehicle_color_id')->
        leftJoin("types_vehicles_versions",'types_vehicles_versions.id','=','vehicles.type_vehicle_version_id')->
        where("vehicles.id",'=',$vehicle_id)->
        whereNull("vehicles.deleted_at")->
        first(array(
            "types_vehicles_models.name as model",
            "types_vehicles_brands.name as brand",
            "types_vehicles_colors.name as color",
            "vehicles.price",
            "types_vehicles_versions.name as version",
            "vehicles.kilometers",
            "vehicles.year",
            "vehicles.vehicle_image_highlighted_id"
        ));
        $vehicleImage = VehicleImage::find($vehicle->vehicle_image_highlighted_id);
        $page = Input::get('page');
        if(!isset($vehicleImage->url)){
            return $this->response(400,"Debes subir al menos una imagen para publicar en facebook.");
        }
        $pictures = VehicleImage::where("vehicle_id",'=',$vehicle_id)->where("id",'!=',$vehicle->vehicle_image_highlighted_id)->get();
        $item = array(
            "access_token" => $page['token'],
            "message" => $page['note'],
            "name"=>"Compra de Vehiculos",
            "multi_share_end_card" => false,
            "description" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->color.' - '.$vehicle->kilometers .'km'. ".Si quieres saber mas , no dudes en preguntarnos!",
            "caption" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->version?:'',
            "link" => $vehicleImage->url,
            "picture" =>$vehicleImage->url
        );
        if(count($pictures))
            $item["child_attachments"] = array(
                array(
                    "description" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->color.' - '.$vehicle->kilometers .'km'. ".Si quieres saber mas , no dudes en preguntarnos!",
                    "caption" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->version?:'',
                    "link" => $vehicleImage->url,
                    "picture" =>$vehicleImage->url
                )
            );
        foreach($pictures as $picture){
            $image = array(
                "description" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->color.' - '.$vehicle->kilometers .'km'. ".Si quieres saber mas , no dudes en preguntarnos!",
                "caption" => $vehicle->brand.' '.$vehicle->model.' '.$vehicle->version?:'',
                "link" => $picture->url,
                "picture" =>$picture->url
            );
            if(count($item["child_attachments"]) < 5)
                $item["child_attachments"][] = $image;
        }
        $response = $client->request('POST', "https://graph.facebook.com/".Config::get('portals.fb.default_graph_version')."/".$page["id"]."/feed",[
            'json' => $item
        ]);
        $post = json_decode($response->getBody(),true);
        if($post["id"]){

            $vehicleFb = VehicleFacebook::create(array(
                "vehicle_id" => $vehicle_id,
                "page_id" => explode('_',$post["id"])[0],
                "post_id" => explode('_',$post["id"])[1],
                "permalink" =>'https://www.facebook.com/'.$post["id"]
            ));

            if(!(VehicleNuestrosAutos::where("vehicle_id",'=',$vehicle_id)->first())){
                VehicleNuestrosAutos::create(array(
                    "vehicle_id" => $vehicle_id
                ));
            }

            return $this->response(200,$vehicleFb);
        }

        return $this->response(400,'Error');
    }

    public function deleteFbItem($dealer_id,$vehicle_id,Request $request){
        $fbUser = $request->session()->get('fbUser');
        $client = new GuzzleHttp\Client();
        $vehicleFb = VehicleFacebook::where("vehicle_id",'=',$vehicle_id)->first();
        $response = $client->request('GET', "https://graph.facebook.com/me/accounts?access_token=".$fbUser->access_token);
        $pages = json_decode($response->getBody(),true);
        foreach($pages["data"] as $page){
           if($page['id'] == $vehicleFb->page_id) $token = $page["access_token"];
        }
        $response = $client->request('DELETE', "https://graph.facebook.com/".Config::get('portals.fb.default_graph_version')."/".$vehicleFb->page_id.'_'.$vehicleFb->post_id,[
            'json' => array('access_token' =>  $token)
        ]);
        $item = json_decode($response->getBody(),true);
        if($vehicleFb->delete()){
            return $this->response(200,$item["success"]);
        }else return $this->response(400,'Error');
    }



}