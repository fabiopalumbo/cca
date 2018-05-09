<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\DASession;
use App\Module;
use App\Permission;
use App\QuestionAutocosmo;
use App\Vehicle;
use App\VehicleDeautos;
use App\VehicleMercadolibre;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Facades\Input;
use App\Contact;
use DB;
use MyProject\Proxies\__CG__\stdClass;
use Validator;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Meli;
use Config;
use App\CarDealerToken;
use GuzzleHttp\Exception\RequestException;
use Mail;

class QuestionsController extends Controller
{
    public function loginQuestion($dealer_id,$user,$password,Request $request){
        $data = Input::all();
        $cmd = 'curl -X POST https://api.deautos.com/security/token -H "Content-Type:x-www-form-urlencoded" -d" grant_type=password&username='.$user.'&password='.$password.'"';
        $result = json_decode(exec($cmd),true);
        session(['DAUser' => new DASession($result["access_token"],$result["expires_in"],$user,$password)]);
        if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
            $daTokens = json_decode($dealer_token->deautos,true);
            $daTokens["access_token"] = $result["access_token"];
            $daTokens["user"] = $user;
            $daTokens["password"] = $password;
            $dealer_token->deautos = json_encode($daTokens);
            $dealer_token->save();
        }else{
            $daTokens = new \stdClass();
            $daTokens->access_token =  $result["access_token"];
            $daTokens->password = $data["password"];
            $daTokens->user = $data["user"];
            CarDealerToken::create(array(
                "car_dealer_id" => $dealer_id,
                "deautos" => json_encode($daTokens)
            ));
        }
       return true;
    }

    public function index($dealer_id,Request $request){
        $mlUser = $request->session()->get('mlUser');
        $DAUser = $request->session()->get('DAUser');
        if(!$DAUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $da_tokens = json_decode($dealer_token->deautos,true);
                $DAUser = new \stdClass();
                $DAUser->access_token = $da_tokens["access_token"];
                $DAUser->user = $da_tokens["user"];
                $DAUser->password = $da_tokens["password"];
            }
        }
        if(!$mlUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $ml_tokens = json_decode($dealer_token->mercadolibre,true);
                $mlUser = new \stdClass();
                $mlUser->access_token = $ml_tokens["access_token"];
            }
        }
        $client = new GuzzleHttp\Client();
        $preguntas = array();
        $vehiclesML = VehicleMercadolibre::all();
        $questionsAC = QuestionAutocosmo::all();
        foreach($vehiclesML as $vehicleML) {
            if ($vehicleML->item_id && $mlUser){
                $vehicle = DB::table("vehicles")->
                join("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
                join("types_vehicles_models",'types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
                leftJoin("types_vehicles_versions",'types_vehicles_versions.id','=','vehicles.type_vehicle_version_id')->
                where("vehicles.id",'=',$vehicleML->vehicle_id)->
                whereNull("vehicles.deleted_at")->
                first(array(
                    "types_vehicles_models.name as model",
                    "types_vehicles_brands.name as brand",
                    "types_vehicles_versions.name as version",
                ));
                try {
                    $response = $client->request('GET', "https://api.mercadolibre.com/questions/search?item_id=" . $vehicleML->item_id . "&access_token=" . $mlUser->access_token);
                    $questions = json_decode($response->getBody(), true);
                    foreach ($questions["questions"] as $question) {
                        if($question["id"]){
                            $pregunta = new \stdClass();
                            $pregunta->platform = "MercadoLibre";
                            $pregunta->id = $question["id"];
                            $pregunta->vehicle_id = $vehicleML->vehicle_id;
                            $pregunta->vehicle = $vehicle->brand .' '.$vehicle->model.' '.$vehicle->version ?:'';
                            $date =  new Carbon($question["date_created"]);
                            $pregunta->date =  $date->format('d/m/Y'); ;
                            $pregunta->email = $question["from"]["email"];
                            $preguntas[] = $pregunta;
                        }
                    }
                } catch (RequestException $e) {
                    if($e->getCode() != 401){
                        if($vehicleML->item_id) return $this->response(400,"Debe loguearse en los portales para obtener las consultas.");
                    }
                }
            } else if(!$mlUser) return $this->response(400,"Debe loguearse en los portales para obtener las consultas.");
        }
        foreach($questionsAC as $questionAC){
            $pregunta = new \stdClass();
            $pregunta->platform = "Autocosmos";
            $pregunta->id = $questionAC->query_id;
            $pregunta->vehicle = $questionAC->brand .' '.$questionAC->model.' '.$questionAC->version ?:'';
            $pregunta->date =  $questionAC->date->format('d/m/Y'); ;
            $pregunta->email = $questionAC->email;
            $preguntas[] = $pregunta;
        }

        $cmd = 'curl -X GET https://api.deautos.com/security/userinfo -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
        $userInfo = json_decode(exec($cmd),true);
        if(isset($userInfo["message"])){
            $car_dealer = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first();
            $metadata = json_decode($car_dealer->deautos,true);
            $response = $this->loginQuestion($dealer_id,$metadata["user"],$metadata["password"],$request);
            if($response) $this->index($dealer_id,$request);
        }

        $ids = array();
        $dealers_vehicles = Vehicle::where("car_dealer_id",'=',$dealer_id)->get();
        foreach($dealers_vehicles as $dealer_vehicle){
            $ids[] = $dealer_vehicle->id;
        }
        $publications = VehicleDeautos::whereIn("vehicle_id",$ids)->get();
        foreach($publications as $publication){
            $cmd = 'curl -X GET https://api.deautos.com/users/'.$userInfo["id"].'/conversations?filterBy=Conversation.Direction:seller,PublicationId:'.$publication->publication_id.' -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
            $questions = json_decode(exec($cmd),true);
            foreach($questions["items"] as $question){
                $pregunta = new \stdClass();
                $pregunta->platform = "deAutos";
                $pregunta->id = $question["id"];
                $pregunta->vehicle_id = $publication->vehicle_id;
                $pregunta->vehicle = $question["description"];
                $date =  new Carbon($question["creationDate"]);
                $pregunta->date =  $date->format('d/m/Y'); ;
                $pregunta->email = $question["email"];
                $preguntas[] = $pregunta;
            }

        }
        return $this->response(200,$preguntas);
    }

    public function answer($dealer_id,$question_id,$vehicle_id,Request $request){
        $meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"));
        $mlUser = $request->session()->get('mlUser');
        $DAUser = $request->session()->get('DAUser');
        if(!$mlUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $ml_tokens = json_decode($dealer_token->mercadolibre,true);
                $mlUser = new \stdClass();
                $mlUser->access_token = $ml_tokens["access_token"];
            }
        }
        if(!$DAUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $da_tokens = json_decode($dealer_token->deautos,true);
                $DAUser = new \stdClass();
                $DAUser->access_token = $da_tokens["access_token"];
                $DAUser->user = $da_tokens["user"];
                $DAUser->password = $da_tokens["password"];
            }
        }
        $client = new GuzzleHttp\Client();
        $item = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first();
        $response = $client->request('GET', "https://api.mercadolibre.com/questions/search?item_id=".$item->item_id."&access_token=".$mlUser->access_token);
        $questions = json_decode($response->getBody(),true);
        foreach($questions["questions"] as $question){
            if($question["id"] == $question_id){
                $answer = array(
                    "question_id" => $question_id,
                    "text" => Input::get("answer")
                );
                $item = $meli->post("/answers", $answer, array('access_token' => $mlUser->access_token));
                return $this->response(200,$item);
            }
        }

        $cmd = 'curl -X GET https://api.deautos.com/security/userinfo -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
        $userInfo = json_decode(exec($cmd),true);
        $ids = array();
        $dealers_vehicles = Vehicle::where("car_dealer_id",'=',$dealer_id)->get();
        foreach($dealers_vehicles as $dealer_vehicle){
            $ids[] = $dealer_vehicle->id;
        }
        $publications = VehicleDeautos::whereIn("vehicle_id",$ids)->get();
        foreach($publications as $publication){
            $cmd = 'curl -X GET https://api.deautos.com/users/'.$userInfo["id"].'/conversations?filterBy=Conversation.Direction:seller,PublicationId:'.$publication->publication_id.' -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
            $questions = json_decode(exec($cmd),true);
            foreach($questions["items"] as $question){
                if($question["id"] == $question_id){
                    $answer = array(
                        "AuthorId" => $userInfo["id"],
                        "Content" => Input::get("answer"),
                        "SourceSection" => 13
                    );
                    $cmd = 'curl -X POST https://api.deautos.com/conversations/'.$question_id.' -d '."'".json_encode($answer)."'".' -H "Content-Type:application/json;charset=utf-8" -H "Authorization: Bearer '.$DAUser->access_token.'"';
                    $result = json_decode(exec($cmd),true);
                    if(isset($result[0]["type"]) === "Error"){
                        return $this->response(400,"Hubo un error al responder la pregunta");
                    }else{
                        return $this->response(200,'OK');
                    }

                }

            }

        }

    }

    public function answerAutocosmos($dealer_id,$question_id){
        $question = QuestionAutocosmo::where("query_id",'=',$question_id)->first();
        $question->answer = Input::get("text");
        $question->save();
        Mail::send('emails.respuestaAutocosmos', $question, function($message)use($question)
        {
            $message->to($question->email,$question->name)
                ->subject('Respuesta a tu consulta');
        });



    }

    public function ask($dealer_id,$vehicle_id,Request $request){
        $mlUser = $request->session()->get('mlUser');
        if(!$mlUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $ml_tokens = json_decode($dealer_token->mercadolibre,true);
                $mlUser = new \stdClass();
                $mlUser->access_token = $ml_tokens["access_token"];
            }
        }
        $client = new GuzzleHttp\Client();
        $question = new \stdClass();
        $question->text = Input::get("question");
        $item = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first();
        $question->item_id = $item->item_id;
        $client->request('POST', "https://api.mercadolibre.com/questions/".$item->item_id."?access_token=".$mlUser->access_token, [
            'json' => $question
        ]);

        return $this->response(200,'OK');
    }

    public function delete($dealer_id,$question_id,$vehicle_id, Request $request){
        $meli = new Meli(Config::get("portals.ml.app_ID"), Config::get("portals.ml.secret_key"));
        $mlUser = $request->session()->get('mlUser');
        if(!$mlUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $ml_tokens = json_decode($dealer_token->mercadolibre,true);
                $mlUser = new \stdClass();
                $mlUser->access_token = $ml_tokens["access_token"];
            }
        }
        $client = new GuzzleHttp\Client();
        $item = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first();
        $response = $client->request('GET', "https://api.mercadolibre.com/questions/search?item_id=".$item->item_id."&access_token=".$mlUser->access_token);
        $questions = json_decode($response->getBody(),true);
        foreach($questions["questions"] as $question){
            if($question["id"] == $question_id){
                $question = $meli->delete("/questions/".$question_id, array('access_token' => $mlUser->access_token));
                return $this->response(200,$question);
            }
        }

    }

    public function show($dealer_id,$question_id,$vehicle_id,Request $request){
        $mlUser = $request->session()->get('mlUser');
        $DAUser = $request->session()->get('DAUser');
        if(!$DAUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $da_tokens = json_decode($dealer_token->deautos,true);
                $DAUser = new \stdClass();
                $DAUser->access_token = $da_tokens["access_token"];
                $DAUser->user = $da_tokens["user"];
                $DAUser->password = $da_tokens["password"];
            }
        }
        if(!$mlUser){
            if($dealer_token = CarDealerToken::where("car_dealer_id",'=',$dealer_id)->first()){
                $ml_tokens = json_decode($dealer_token->mercadolibre,true);
                $mlUser = new \stdClass();
                $mlUser->access_token = $ml_tokens["access_token"];
            }
        }
        $client = new GuzzleHttp\Client();
        $preguntas = array();
        $item = VehicleMercadolibre::where("vehicle_id",'=',$vehicle_id)->first();
        $questionsAC = QuestionAutocosmo::all();
        $vehicle = DB::table("vehicles")->
        join("types_vehicles_brands",'types_vehicles_brands.id','=','vehicles.type_vehicle_brand_id')->
        join("types_vehicles_models",'types_vehicles_models.id','=','vehicles.type_vehicle_model_id')->
        leftJoin("types_vehicles_versions",'types_vehicles_versions.id','=','vehicles.type_vehicle_version_id')->
        where("vehicles.id",'=',$vehicle_id)->
        whereNull("vehicles.deleted_at")->
        first(array(
            "vehicles.id",
            "types_vehicles_models.name as model",
            "types_vehicles_brands.name as brand",
            "types_vehicles_versions.name as version",
        ));
        if(isset($item->item_id) and isset($mlUser->access_token)){
            $response = $client->request('GET', "https://api.mercadolibre.com/questions/search?item_id=" . $item->item_id . "&access_token=" . $mlUser->access_token);
            $questions = json_decode($response->getBody(), true);
            foreach($questions["questions"] as $question) {
                if($question["id"] == $question_id){
                    $pregunta = new \stdClass();
                    $pregunta->platform = "MercadoLibre";
                    $pregunta->id = $question["id"];
                    $pregunta->question = new \stdClass();
                    $pregunta->question->question = $question["text"];
                    $date = new Carbon($question["answer"]["date_created"]);
                    //$question["answer"]["date_created"] = $date->format('d/m/Y'); ;
                    //$pregunta->question->answer = isset($question["answer"]) ? (object)$question["answer"] : null;
                    $pregunta->question->answer = $question["answer"]["text"];
                    $pregunta->question->answer_date = $date->format('d/m/Y');
                    $pregunta->question->answer_status = $question["answer"]["status"];
                    $pregunta->vehicle_id = $vehicle->id;
                    $pregunta->vehicle = $vehicle->brand.' '.$vehicle->model.' '.$vehicle->version ?: '';
                    $date =  new Carbon($question["date_created"]);
                    $pregunta->date =  $date->format('d/m/Y'); ;
                    $pregunta->email = $question["from"]["email"];
                    $preguntas[] = $pregunta;
                }

            }
        }

        foreach($questionsAC as $questionAC){
            if($questionAC->query_id == $question_id){
                $pregunta = new \stdClass();
                $pregunta->platform = "Autocosmos";
                $pregunta->id = $questionAC->query_id;
                $pregunta->question = $questionAC->question ?:'';
                $pregunta->answer =$questionAC->answer ?: '';
                $pregunta->vehicle = $questionAC->brand .' '.$questionAC->model.' '.$questionAC->version ?:'';
                $pregunta->date =  $questionAC->date->format('d/m/Y'); ;
                $pregunta->email = $questionAC->email;
                $preguntas[] = $pregunta;
            }
        }

        $cmd = 'curl -X GET https://api.deautos.com/security/userinfo -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
        $userInfo = json_decode(exec($cmd),true);
        $ids = array();
        $dealers_vehicles = Vehicle::where("car_dealer_id",'=',$dealer_id)->get();
        foreach($dealers_vehicles as $dealer_vehicle){
            $ids[] = $dealer_vehicle->id;
        }
        $publications = VehicleDeautos::whereIn("vehicle_id",$ids)->get();
        foreach($publications as $publication){
            $cmd = 'curl -X GET https://api.deautos.com/users/'.$userInfo["id"].'/conversations?filterBy=Conversation.Direction:seller,PublicationId:'.$publication->publication_id.' -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
            $questions = json_decode(exec($cmd),true);
            foreach($questions["items"] as $question){
                if($question["id"] == $question_id){
                    $cmd = 'curl -X GET https://api.deautos.com/conversations/'.$question_id.' -H "Content-Type:application/json" -H "Authorization: Bearer '.$DAUser->access_token.'"';
                    $questionsInfo = json_decode(exec($cmd),true);
                    foreach($questionsInfo["messages"] as $questionInfo){
                        if($questionInfo["type"] == "Direct"){
                            $question["question"] = $questionInfo["content"];
                        }
                    }
                    $pregunta = new \stdClass();
                    $pregunta->platform = "deAutos";
                    $pregunta->id = $question["id"];
                    $pregunta->question = $question["question"];
                    $pregunta->answer = $question["lastMessage"]["content"] ?: '';
                    $pregunta->vehicle = $question["description"];
                    $date =  new Carbon($question["creationDate"]);
                    $pregunta->date =  $date->format('d/m/Y'); ;
                    $pregunta->email = $question["email"];
                    $preguntas[] = $pregunta;
                }

            }

        }

        return $this->response(200,$preguntas);
    }

}